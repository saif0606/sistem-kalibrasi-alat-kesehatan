<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalibrationRequest;
use Illuminate\Http\Request;

class CalibrationController extends Controller
{
    public function index(Request $request)
    {
        // Naikkan status "Penjadwalan" -> "Kalibrasi" untuk semua pengajuan yang
        // tanggal_kalibrasi-nya sudah tiba, setiap kali halaman ini dibuka.
        CalibrationRequest::autoPromoteScheduled();
        // Tutup jendela resubmit dokumen yang sudah lewat 1x24 jam.
        CalibrationRequest::autoExpireResubmitWindow();

        $query = CalibrationRequest::with(['user', 'certificate']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('registration_number', 'like', "%{$search}%")
                  ->orWhere('nama_instansi', 'like', "%{$search}%")
                  ->orWhere('nama_kontak', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $calibrations = $query->latest()->paginate(10)->withQueryString();

        return view('admin.calibrations.index', compact('calibrations'));
    }

    public function create()
    {
        // Pengajuan biasanya dibuat oleh User, tapi jika admin butuh, bisa ditambahkan di sini.
        abort(404);
    }

    public function store(Request $request)
    {
        abort(404);
    }

    public function show(CalibrationRequest $calibration)
    {
        CalibrationRequest::autoPromoteScheduled();
        CalibrationRequest::autoExpireResubmitWindow();
        $calibration->refresh();
        $calibration->load(['user', 'certificate']);
        return view('admin.calibrations.show', compact('calibration'));
    }

    public function edit(CalibrationRequest $calibration)
    {
        $calibration->load(['user']);
        return view('admin.calibrations.edit', compact('calibration'));
    }

    public function update(Request $request, CalibrationRequest $calibration)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pengajuan,Penjadwalan,Kalibrasi,Pembayaran,Sertifikat,Selesai,Ditolak',
            'admin_note' => 'nullable|string',
            'tanggal_kalibrasi' => 'nullable|date',
            'waktu_kalibrasi' => 'nullable|date_format:H:i',
            'lokasi_kalibrasi' => 'nullable|string|in:Klinik / Faskes,Lab UPTD',
            'certificate' => 'nullable|file|mimes:pdf|max:10240', // max 10MB
            'draft_harga' => 'nullable|file|max:10240', // max 10MB
            // Hanya relevan saat status yang dikirim = Ditolak
            'rejection_reason' => 'nullable|string|in:Dokumen,Lainnya',
            'allow_resubmit'   => 'nullable|boolean',
        ]);

        // Jika admin baru saja menetapkan tanggal kalibrasi dan tanggal itu
        // ternyata sudah hari ini/lewat, langsung set status "Pembayaran"
        // supaya tidak perlu menunggu request berikutnya.
        if (
            !empty($validated['tanggal_kalibrasi']) &&
            in_array($validated['status'], ['Penjadwalan', 'Kalibrasi']) &&
            \Illuminate\Support\Carbon::parse($validated['tanggal_kalibrasi'])->lte(now())
        ) {
            $validated['status'] = 'Pembayaran';
        }

        // Jika statusnya Sertifikat, pastikan ada record certificate supaya bisa track email
        if ($validated['status'] === 'Sertifikat') {
            $certData = [];
            
            // Jika ada file yang diupload, siapkan data update
            if ($request->hasFile('certificate')) {
                $certData['file_path'] = $request->file('certificate')->store('certificates', 'public');
            }

            // Buat record certificate dasar jika belum ada
            $certificate = $calibration->certificate()->firstOrCreate(
                ['calibration_request_id' => $calibration->id],
                [
                    'certificate_number' => 'CERT-' . strtoupper(uniqid()),
                    'issued_at' => now(),
                ]
            );
            
            // Update dengan file path (jika ada upload file baru)
            if (!empty($certData)) {
                $certificate->update($certData);
            }

            // Kirim notifikasi email ke klien HANYA sekali (belum pernah terkirim
            // untuk sertifikat ini), supaya admin bisa edit ulang data lain tanpa
            // klien di-spam email berulang kali.
            if (!$certificate->email_sent_at) {
                try {
                    \Illuminate\Support\Facades\Mail::to($calibration->email)
                        ->send(new \App\Mail\CertificateReadyMail($calibration));
                    $certificate->update(['email_sent_at' => now()]);
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Gagal kirim email sertifikat: ' . $e->getMessage());
                }
            }
        }

        $draftHargaPath = $calibration->draft_harga;

        if ($request->input('delete_draft_harga') == '1') {
            if ($calibration->draft_harga) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($calibration->draft_harga);
            }
            $draftHargaPath = null;
        } elseif ($request->hasFile('draft_harga')) {
            $draftHargaPath = $request->file('draft_harga')->store('draft_harga', 'public');
        }

        $isCertStatus = in_array($validated['status'], ['Sertifikat', 'Selesai']);

        if ($request->input('delete_certificate') == '1' || !$isCertStatus) {
            if ($calibration->certificate) {
                if ($calibration->certificate->file_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($calibration->certificate->file_path);
                }
                $calibration->certificate()->delete();
            }
        }

        // ============================================================
        // LOGIKA KHUSUS PENOLAKAN (status baru = Ditolak)
        //
        // - Alasan "Dokumen" + admin izinkan resubmit:
        //     nomor pesanan & data lain TETAP, dokumen lama dihapus,
        //     user diberi waktu 1x24 jam untuk upload dokumen baru.
        // - Selain itu (alasan lain / admin tidak izinkan resubmit):
        //     nomor pesanan ini "mati", user wajib membuat pengajuan baru.
        // ============================================================
        $daftarAlatAfterUpdate = $calibration->daftar_alat;
        $rejectionReason  = $calibration->rejection_reason;
        $allowResubmit    = $calibration->allow_resubmit;
        $resubmitDeadline = $calibration->resubmit_deadline;
        $rejectedAt       = $calibration->rejected_at;

        $isNewRejection = $validated['status'] === 'Ditolak' && $calibration->status !== 'Ditolak';
$isDitolakNow   = $validated['status'] === 'Ditolak';

if ($isDitolakNow) {
    // Selalu pakai alasan & pilihan izin-resubmit yang BARU disubmit admin —
    // baik ini penolakan baru, MAUPUN admin sedang mengedit ulang pilihan
    // sebelumnya (mis. tadinya "Lainnya", sekarang diubah ke "Dokumen"
    // + izinkan resubmit). Sebelumnya ini cuma jalan kalau statusnya baru
    // pertama kali pindah ke Ditolak, jadi perubahan susulan gak kesimpen.
    $rejectionReason = $validated['rejection_reason'] ?? 'Lainnya';

    if ($isNewRejection) {
        $rejectedAt = now();
    }

    if ($rejectionReason === 'Dokumen' && $request->boolean('allow_resubmit')) {
        $allowResubmit    = true;
        $resubmitDeadline = now()->addDay(); // (ulang) hitung 1x24 jam dari sekarang

        $rawAlat = $calibration->daftar_alat;
        $decodedAlat = is_array($rawAlat) ? $rawAlat : (is_string($rawAlat) ? (json_decode($rawAlat, true) ?? []) : []);
        foreach ($decodedAlat as $item) {
            if (is_string($item) && $item !== '') {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($item);
            }
        }
        $daftarAlatAfterUpdate = '[]';
    } else {
        $allowResubmit    = false;
        $resubmitDeadline = null;
    }
} else {
    // Status dipindah keluar dari "Ditolak" -> reset semua flag penolakan.
    $rejectionReason  = null;
    $allowResubmit    = false;
    $resubmitDeadline = null;
    $rejectedAt       = null;
}

        $calibration->update([
            'status'             => $validated['status'],
            'rejected_at_status' => $validated['status'] === 'Ditolak'
                ? ($calibration->rejected_at_status ?? $calibration->status) // preserve old status at time of rejection
                : null,
            'rejection_reason'   => $rejectionReason,
            'allow_resubmit'     => $allowResubmit,
            'resubmit_deadline'  => $resubmitDeadline,
            'rejected_at'        => $rejectedAt,
            'admin_note'         => $validated['admin_note'] ?? null,
            'tanggal_kalibrasi'  => $validated['tanggal_kalibrasi'] ?? $calibration->tanggal_kalibrasi,
            'lokasi_kalibrasi'   => $validated['lokasi_kalibrasi'] ?? $calibration->lokasi_kalibrasi,
            'draft_harga'        => $draftHargaPath,
            'daftar_alat'        => $daftarAlatAfterUpdate,
        ]);

        return redirect()->route('admin.calibrations.index')->with('success', 'Status Kalibrasi berhasil diupdate');
    }

    public function destroy(CalibrationRequest $calibration)
    {
        $calibration->delete();
        return redirect()->route('admin.calibrations.index')->with('success', 'Data pengajuan berhasil dihapus');
    }

    /**
     * Kirim satu atau beberapa dokumen "Daftar Alat" milik pengajuan ini
     * langsung sebagai lampiran chat ke pelanggan (tanpa upload ulang).
     */
    public function replyChat(Request $request, CalibrationRequest $calibration)
    {
        $validated = $request->validate([
            'attachments'   => 'required|array|min:1',
            'attachments.*' => 'string',
            'message'       => 'nullable|string|max:2000',
        ]);

        $raw = $calibration->daftar_alat;
        $decoded = is_array($raw) ? $raw : (is_string($raw) ? (json_decode($raw, true) ?? []) : []);
        $ownedPaths = collect($decoded)->filter(fn ($item) => is_string($item))->values()->all();

        $toSend = array_values(array_intersect($validated['attachments'], $ownedPaths));

        if (empty($toSend)) {
            return back()->with('error', 'Dokumen yang dipilih tidak valid.');
        }

        foreach ($toSend as $path) {
            \App\Models\ChatMessage::create([
                'user_id'     => $calibration->user_id,
                'admin_id'    => auth()->id(),
                'sender_role' => 'admin',
                'message'     => null,
                'attachment'  => $path,
                'is_read'     => true,
            ]);
        }

        if (!empty($validated['message'])) {
            \App\Models\ChatMessage::create([
                'user_id'     => $calibration->user_id,
                'admin_id'    => auth()->id(),
                'sender_role' => 'admin',
                'message'     => $validated['message'],
                'attachment'  => null,
                'is_read'     => true,
            ]);
        }

        return redirect()->route('admin.chat.index', ['user' => $calibration->user_id])
            ->with('success', count($toSend) . ' dokumen berhasil dikirim ke chat pelanggan.');
    }
}