<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\FaqAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    // BENAR
    public function index()
    {
    // Tandai semua balasan admin yang belum dibaca user sebagai sudah dibaca
    ChatMessage::where('user_id', Auth::id())
        ->where('sender_role', 'admin')
        ->whereNull('read_by_user_at')
        ->update(['read_by_user_at' => now()]);
        
        $messages = ChatMessage::where('user_id', Auth::id())->oldest()->get();

        $calibrations = \App\Models\CalibrationRequest::where('user_id', Auth::id())
            ->whereNotNull('draft_harga')
            ->get(['id', 'registration_number', 'draft_harga'])
            ->keyBy('registration_number');

        // Map registration_number -> id for all user calibrations (for detail links)
        $calibrationIdMap = \App\Models\CalibrationRequest::where('user_id', Auth::id())
            ->get(['id', 'registration_number'])
            ->pluck('id', 'registration_number');

        return view('user.chat', compact('messages', 'calibrations', 'calibrationIdMap'));
    }

    /**
 * Endpoint ringan buat bell notifikasi: jumlah balasan admin yang belum dibaca user.
 */
public function unreadCount()
{
    $count = ChatMessage::where('user_id', Auth::id())
        ->where('sender_role', 'admin')
        ->whereNull('read_by_user_at')
        ->count();

    return response()->json(['count' => $count]);
}

    public function messages()
    {
        $messages = ChatMessage::with('parent')->where('user_id', Auth::id())->oldest()->get()->map(function ($msg) {
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message'    => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|max:20480', // Max 20MB
            'parent_id'  => 'nullable|exists:chat_messages,id'
            ], [
                'attachment.max' => 'Ukuran file maksimal 20MB.',
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
            'user_id'     => Auth::id(),
            'admin_id'    => null,
            'sender_role' => 'user',
            'message'     => $validated['message'] ?? null,
            'attachment'  => $attachmentPath,
            'is_read'     => false,
        ]);

        // ============ panggil bot, hanya kalau ada teks & tidak ada attachment ============
        // Jangan panggil bot jika pesan merupakan stiker (format: [STICKER:namafile])
        $isSticker = str_starts_with(trim($validated['message'] ?? ''), '[STICKER:');
        
        if (!empty($validated['message']) && !$attachmentPath && !$isSticker && empty($validated['parent_id'])) {
            $this->prosesBot($validated['message']);
        }
        // ====================================================================================

        if ($request->wantsJson()) {
            $msg->load('parent');
            return response()->json([
                'success' => true,
                'message' => [
                    'id'          => $msg->id,
                    'message'     => $msg->message ? e($msg->message) : null,
                    'sender_role' => $msg->sender_role,
                    'time'        => $msg->created_at->format('H:i'),
                    'date'        => $msg->created_at->format('Y-m-d'),
                    'date_label'  => 'Hari ini',
                    'attachment'  => $msg->attachment ? asset('storage/' . $msg->attachment) : null,
                    'parent'      => $msg->parent_id ? [
                        'message'     => $msg->parent->message ? e($msg->parent->message) : null,
                        'attachment'  => $msg->parent->attachment ? asset('storage/' . $msg->parent->attachment) : null,
                        'sender_role' => $msg->parent->sender_role,
                    ] : null,
                ]
            ]);
        }

        return redirect()->route('user.chat.index');
    }

    // ============ METHOD: proses jawaban bot ============
    private function prosesBot(string $pesanUser)
    {
        $THRESHOLD = 0.6;

        try {
            $response = Http::timeout(15)
                ->withoutVerifying() // Bypass SSL verification for local dev with ngrok
                ->post(
                'https://subradiative-neoma-unnibbled.ngrok-free.dev/predict',
                ['text' => $pesanUser]
            );

            if (!$response->successful()) {
                throw new \Exception('API tidak merespons dengan status: ' . $response->status());
            }

           $hasil = $response->json();
           $intent = $hasil['intent'] ?? null;
           $confidence = $hasil['confidence'] ?? 0;
           $attachment = null; // dipakai kalau ada intent yang perlu kirim file (mis. panduan PDF)
           
           $fallbackMessage = "Maaf, saya kurang yakin dengan pertanyaan Anda. Pesan ini akan diteruskan ke admin kami untuk dibantu langsung.";

           if ($confidence < $THRESHOLD || !$intent) {
               // Cek apakah bot sudah mengirim pesan fallback ini dalam 1 jam terakhir
               $recentFallback = ChatMessage::where('user_id', Auth::id())
                   ->where('sender_role', 'bot')
                   ->where('message', $fallbackMessage)
                   ->where('created_at', '>=', now()->subHour())
                   ->exists();

               if ($recentFallback) {
                   // Jika sudah pernah, jangan balas apa-apa, agar tidak spam.
                   // Pesan user tetap tersimpan dari fungsi store() sebelumnya.
                   return;
               }

               $jawabanBot = $fallbackMessage;
            } elseif ($intent === 'harga_kalibrasi_alat') {
                $jawabanBot = $this->jawabHargaAlat($pesanUser);
                } elseif (in_array($intent, ['cara_bayar_saibara', 'sistem_pembayaran', 'wajib_pakai_saibara'])) {
                    // intent tentang pembayaran -> balas dengan panduan + lampirkan PDF panduan SAIBARA
                    $jawabanBot = "Berikut panduan untuk aplikasi saibara";
                    $attachment = 'documents/panduan-saibara.pdf';
                    
                } else {   
                // ── Pre-check: meskipun intent bukan harga_kalibrasi_alat, cek apakah
                //   user menyebut nama alat spesifik. Kalau ada -> override ke jawabHargaAlat
                //   supaya jawaban tetap spesifik, tidak generik dari FAQ intent lain.
                $jawabanSpesifik = $this->jawabHargaAlatJikaAda($pesanUser);
                if ($jawabanSpesifik !== null) {
                    $jawabanBot = $jawabanSpesifik;
                } else {
                    $faq = FaqAnswer::where('intent', $intent)->first();
                    $jawabanBot = $faq ? $faq->jawaban : "Maaf, jawaban untuk topik ini belum tersedia. Admin kami akan membantu Anda.";
                }
            }
            
            \Log::info("Bot API Result: ", ['intent' => $intent, 'confidence' => $confidence, 'jawaban' => $jawabanBot]);

            // SESUDAH
            ChatMessage::create([
                'user_id'     => Auth::id(),
                'admin_id'    => null,
                'sender_role' => 'bot',
                'message'     => $jawabanBot,
                'attachment'  => $attachment,
                'intent'      => $intent,
                'confidence'  => $confidence,
                'is_read'     => true,
                ]);

        } catch (\Throwable $e) {
            // API down/timeout -> jangan error ke user, biarkan admin balas manual
            Log::warning('Bot API gagal: ' . $e->getMessage());

            $offlineMessage = "Maaf, sistem Asisten Bot saat ini sedang offline. Pesan Anda telah kami terima dan akan dibalas langsung oleh Admin kami segera.";
            $recentOffline = ChatMessage::where('user_id', Auth::id())
                ->where('sender_role', 'bot')
                ->where('message', $offlineMessage)
                ->where('created_at', '>=', now()->subHour())
                ->exists();

            if (! $recentOffline) {
                ChatMessage::create([
                    'user_id'     => Auth::id(),
                    'admin_id'    => null,
                    'sender_role' => 'bot',
                    'message'     => $offlineMessage,
                    'is_read'     => true,
                ]);
            }
        }
    }

    /**
     * Cari nama alat yang paling cocok dari teks user, pakai str_contains
     * (exact substring) dulu, baru fallback ke similar_text (buat typo/singkatan).
     * Lalu balikin jawaban harga sesuai alat yang ketemu.
     */
    private function jawabHargaAlat(string $pesanUser): string
    {
        // Keyword aliases: kata pendek/umum yg dipakai user -> kata kunci nama di DB
        $aliases = [
            'usg'           => 'ultrasonography',
            'ultrasonografi'=> 'ultrasonography',
            'tensimeter'    => 'sphygmomanometer',
            'sphygmo'       => 'sphygmomanometer',
            'tensi'         => 'sphygmomanometer',
            'spo2'          => 'pulse oximetri',
            'oximeter'      => 'pulse oximetri',
            'oksimeter'     => 'pulse oximetri',
            'nebulizer'     => 'nebulizer',
            'nebuliser'     => 'nebulizer',
            'bpm'           => 'blood pressure',
            'blood pressure'=> 'blood pressure',
            'timbangan bayi'=> 'timbangan bayi',
            'doppler'       => 'doppler',
            'fetal'         => 'doppler',
            'suction'       => 'suction pump',
            'hisap'         => 'suction pump',
            'ecg'           => 'electrocardiograph',
            'ekg'           => 'electrocardiograph',
            'elektrokardiogram' => 'electrocardiograph',
            'centrifuge'    => 'centrifuge',
            'sentrifuge'    => 'centrifuge',
            'autoclave'     => 'autoclave',
            'autoklaf'      => 'autoclave',
            'monitor pasien'=> 'monitor pasien',
            'patient monitor'=> 'monitor pasien',
            'elektrolit'    => 'elektrolit',
            'glukosa'       => 'glukosa',
            'glucometer'    => 'glukosa',
            'oven'          => 'oven',
            'inkubator'     => 'inkubator',
            'inkubasi'      => 'inkubator',
            'incubator'     => 'inkubator',
            'infus'         => 'infus',
            'infusion'      => 'infus',
            'syringe'       => 'syringe',
            'vial'          => 'vial',
            'spirometer'    => 'spirometer',
            'spirometri'    => 'spirometer',
            'timbangan'     => 'timbangan',
            'bedah'         => 'bedah',
            'sterilisasi'   => 'sterilisasi',
        ];

        $daftarAlat = \App\Models\Service::all(['name', 'price']);
        $pesanLower = strtolower($pesanUser);

        // Terapkan alias dulu — ganti kata pendek ke kata kunci nama di DB
        foreach ($aliases as $alias => $keyword) {
            if (str_contains($pesanLower, $alias)) {
                $pesanLower = str_replace($alias, $keyword, $pesanLower);
            }
        }

        // Cari SEMUA alat yang disebutkan dalam pesan (multi-match)
        $matched = [];
        foreach ($daftarAlat as $service) {
            $namaLower = strtolower($service->name);
            // Cek apakah kata kunci utama nama alat ada di pesan
            // Ambil token kata terpanjang (hindari false-match dari kata pendek)
            $tokens = array_filter(explode(' ', preg_replace('/[^a-z0-9 ]/i', ' ', $namaLower)));
            foreach ($tokens as $token) {
                if (strlen($token) >= 4 && str_contains($pesanLower, $token)) {
                    $matched[$service->name] = (float) $service->price;
                    break;
                }
            }
        }

        if (empty($matched)) {
            // Tidak ada alat spesifik yang cocok -> fallback ke jawaban generik dari FAQ
            $faq = FaqAnswer::where('intent', 'harga_kalibrasi_alat')->first();
            return $faq ? $faq->jawaban : "Silakan sebutkan nama alat kesehatan yang ingin Anda ketahui tarifnya.";
        }

        if (count($matched) === 1) {
            $nama  = array_key_first($matched);
            $harga = $matched[$nama];
            return "Tarif kalibrasi untuk **{$nama}** adalah **Rp" . number_format($harga, 0, ',', '.') .
                   "** (berdasarkan Perda Provinsi Lampung No. 4 Tahun 2024). " .
                   "Harga tersebut belum termasuk biaya akomodasi tenaga teknis.";
        }

        // Lebih dari 1 alat disebutkan -> tampilkan daftar khusus alat yang disebut
        $lines = [];
        foreach ($matched as $nama => $harga) {
            $lines[] = "• {$nama}: Rp" . number_format($harga, 0, ',', '.');
        }
        return "Berikut tarif kalibrasi untuk alat yang Anda sebutkan (Perda Lampung No. 4 Tahun 2024):\n" .
               implode("\n", $lines) .
               "\n\nHarga di atas belum termasuk biaya akomodasi tenaga teknis.";
    }

    /**
     * Coba cari alat dalam pesan. Kalau ketemu -> kembalikan jawaban harga spesifik.
     * Kalau tidak ada alat yang disebut -> kembalikan null (biarkan FAQ biasa menjawab).
     */
    private function jawabHargaAlatJikaAda(string $pesanUser): ?string
    {
        $jawaban = $this->jawabHargaAlat($pesanUser);

        // jawabHargaAlat mengembalikan fallback FAQ jika tidak ada alat cocok;
        // kita perlu tahu apakah itu jawaban spesifik atau fallback.
        // Triknya: jika matched array di dalam jawabHargaAlat kosong, jawaban adalah teks FAQ.
        // Daripada duplikasi logic, kita check apakah string dimulai dari kata khas respons spesifik.
        if (
            str_starts_with($jawaban, 'Tarif kalibrasi untuk') ||
            str_starts_with($jawaban, 'Berikut tarif kalibrasi untuk alat yang')
        ) {
            return $jawaban; // jawaban spesifik -> pakai ini
        }

        return null; // fallback generik -> biarkan intent asli menjawab
    }
    // ===========================================================

    public function destroy(ChatMessage $message)
    {
        abort_if($message->user_id !== Auth::id(), 403);
        // User only deletes text, bubble/attachment stays
        $message->update(['message' => '<i>Pesan ini telah dihapus</i>']);

        return response()->json(['success' => true, 'action' => 'update_text', 'text' => '<i>Pesan ini telah dihapus</i>']);
    }
}