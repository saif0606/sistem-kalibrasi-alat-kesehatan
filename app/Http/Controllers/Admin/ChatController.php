<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Daftar seluruh percakapan pelanggan + thread pesan pelanggan yang
     * sedang dipilih (lewat ?user=ID).
     */
    public function index(Request $request)
    {
        $conversations = User::where('role', 'user')
            ->whereHas('chatMessages')
            ->withCount(['chatMessages as unread_count' => function ($q) {
                $q->where('sender_role', 'user')->where('is_read', false);
            }])
            ->with(['chatMessages' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->get()
            ->sortByDesc(function ($u) {
                return optional($u->chatMessages->first())->created_at;
            })
            ->values();

        $selectedUser = null;
        $messages = collect();
        $refCalibration = null;
        $userCalibrations = collect();

        if ($request->filled('user')) {
            $selectedUser = User::where('role', 'user')->find($request->user);
            if ($selectedUser) {
                $messages = ChatMessage::where('user_id', $selectedUser->id)
                    ->with('admin')
                    ->oldest()
                    ->get();

                // Tandai semua pesan dari pelanggan ini sebagai sudah dibaca
                ChatMessage::where('user_id', $selectedUser->id)
                    ->where('sender_role', 'user')
                    ->where('is_read', false)
                    ->update(['is_read' => true]);

                // Lookup calibration reference if ref param provided
                if ($request->filled('ref')) {
                    $refCalibration = \App\Models\CalibrationRequest::where('registration_number', $request->ref)
                        ->where('user_id', $selectedUser->id)
                        ->first();
                }

                $userCalibrations = \App\Models\CalibrationRequest::where('user_id', $selectedUser->id)
                    ->whereNotNull('draft_harga')
                    ->get(['registration_number', 'draft_harga'])
                    ->keyBy('registration_number');
            }
        }

        // Daftar semua pelanggan (untuk memulai chat baru / broadcast)
        $allCustomers = User::where('role', 'user')->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.chat.index', compact('conversations', 'selectedUser', 'messages', 'allCustomers', 'refCalibration', 'userCalibrations'));
    }

    /**
     * Kirim balasan admin ke satu pelanggan.
     */
    public function store(Request $request, User $user)
    {
        abort_if($user->role !== 'user', 404);

        $validated = $request->validate([
            'message'    => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|max:5120', // Max 5MB
            'parent_id'  => 'nullable|exists:chat_messages,id'
        ]);

        if (empty($validated['message']) && !$request->hasFile('attachment')) {
            return response()->json(['success' => false, 'error' => 'Pesan atau file tidak boleh kosong.'], 400);
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('chat_attachments', 'public');
        }

        $msg = ChatMessage::create([
            'parent_id'   => $validated['parent_id'] ?? null,
            'user_id'     => $user->id,
            'admin_id'    => auth()->id(),
            'sender_role' => 'admin',
            'message'     => $validated['message'] ?? null,
            'attachment'  => $attachmentPath,
            'is_read'     => true, // Admin is sending, so it's read by admin (but unread by user logic relies on sender_role=admin).
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            $msg->load('parent');
            return response()->json([
                'success' => true,
                'message' => [
                    'id'          => $msg->id,
                    'message'     => $msg->message ? e($msg->message) : null,
                    'sender_role' => 'admin',
                    'time'        => $msg->created_at->format('H:i'),
                    'date'        => $msg->created_at->format('Y-m-d'),
                    'date_label'  => 'Hari ini',
                    'attachment'  => $msg->attachment ? asset('storage/' . $msg->attachment) : null,
                    'parent'      => $msg->parent_id ? [
                        'message'     => $msg->parent->message ? e($msg->parent->message) : null,
                        'attachment'  => $msg->parent->attachment ? asset('storage/' . $msg->parent->attachment) : null,
                        'sender_role' => $msg->parent->sender_role,
                    ] : null,
                ],
            ]);
        }

        return redirect()->route('admin.chat.index', ['user' => $user->id]);
    }

    /**
     * Kirim pesan ke banyak pelanggan sekaligus (pilih beberapa / semua).
     */
    public function broadcast(Request $request)
    {
        $validated = $request->validate([
            'message'    => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|max:20480', // Max 20MB
            'parent_id'  => 'nullable|exists:chat_messages,id'
        ], [
            'attachment.max' => 'Ukuran file maksimal 20MB.',
            ]);

        if ($validated['target'] === 'all') {
            $userIds = User::where('role', 'user')->pluck('id');
        } else {
            $userIds = collect($validated['user_ids']);
        }

        $now = now();
        $message = $validated['message'];
        $rows = $userIds->map(function ($id) use ($now, $message) {
            return [
                'user_id'     => $id,
                'admin_id'    => auth()->id(),
                'sender_role' => 'admin',
                'message'     => $message,
                'is_read'     => true,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        });

        ChatMessage::insert($rows->toArray());

        return back()->with('success', 'Pesan berhasil dikirim ke ' . $userIds->count() . ' pelanggan.');
    }

    /**
     * Endpoint ringan untuk polling jumlah pesan belum dibaca (badge notifikasi & nav chat).
     */
    public function unreadCount()
    {
        $count = ChatMessage::where('sender_role', 'user')->where('is_read', false)->count();

        return response()->json(['count' => $count]);
    }

    /**
     * AJAX: Return messages for a specific user as JSON for polling
     */
    public function messages(Request $request, User $user)
    {
        abort_if($user->role !== 'user', 404);

        $messages = ChatMessage::with('parent')->where('user_id', $user->id)->oldest()->get()->map(function ($msg) {
            return [
                'id'          => $msg->id,
                'message'     => $msg->message ? e($msg->message) : null,
                'sender_role' => $msg->sender_role,
                'time'        => $msg->created_at->format('H:i'),
                'date'        => $msg->created_at->format('Y-m-d'),
                'date_label'  => $msg->created_at->isToday()
                    ? 'Hari ini'
                    : ($msg->created_at->isYesterday() ? 'Kemarin' : $msg->created_at->format('d M Y')),
                'attachment'  => $msg->attachment ? asset('storage/' . $msg->attachment) : null,
                'parent'      => $msg->parent_id ? [
                    'message'     => $msg->parent->message ? e($msg->parent->message) : null,
                    'attachment'  => $msg->parent->attachment ? asset('storage/' . $msg->parent->attachment) : null,
                    'sender_role' => $msg->parent->sender_role,
                ] : null,
            ];
        });

        return response()->json(['messages' => $messages]);
    }

    /**
     * Hapus pesan chat oleh admin
     */
    public function destroy(ChatMessage $message)
    {
        // Delete attachment if exists
        if ($message->attachment) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($message->attachment);
        }
        
        $message->delete();
        
        return response()->json(['success' => true]);
    }
}