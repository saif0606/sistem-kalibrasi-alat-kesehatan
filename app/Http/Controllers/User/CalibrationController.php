<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CalibrationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalibrationController extends Controller
{
    public function index()
    {
        CalibrationRequest::autoPromoteScheduled();
        CalibrationRequest::autoExpireResubmitWindow();

        $calibrations = CalibrationRequest::where('user_id', Auth::id())->latest()->paginate(10);
        return view('user.calibrations.index', compact('calibrations'));
    }

    public function create()
    {
        return view('user.calibrations.create');
    }

    public function store(Request $request)
    {
        // BENAR
        $validated = $request->validate([
    'nama_instansi'       => 'required|string|max:255',
    'nama_kontak'         => 'required|string|max:255',
    'nomor_telepon'       => 'required|string|max:50',
    'email'               => 'required|email|max:255',
    'alamat_lengkap'      => 'required|string',
    'metode_kalibrasi'    => 'required|string|in:Kirim UPTD,Kunjungan UPTD',
    'konfirmasi_alamat'   => 'nullable|boolean',
    'daftar_alat'         => 'nullable|array',
    'daftar_alat.*'       => 'file|max:10240',
    'catatan_tambahan'    => 'nullable|string',
]);

$daftarAlatPaths = [];
$deviceName = 'Alat Kesehatan';
if ($request->hasFile('daftar_alat')) {
    foreach ($request->file('daftar_alat') as $file) {
        if ($file && $file->isValid()) {
            $daftarAlatPaths[] = $file->store('daftar_alat', 'public');
        }
    }
    if (!empty($daftarAlatPaths) && $request->file('daftar_alat')[0]) {
        $deviceName = pathinfo($request->file('daftar_alat')[0]->getClientOriginalName(), PATHINFO_FILENAME);
    }
}

        $calibration = CalibrationRequest::create([
            'user_id'             => Auth::id(),
            'registration_number' => 'TEMP-' . uniqid('', true),
            'device_name'         => $deviceName,
            'nama_instansi'       => $validated['nama_instansi'],
            'nama_kontak'         => $validated['nama_kontak'],
            'nomor_telepon'       => $validated['nomor_telepon'],
            'email'               => $validated['email'],
            'alamat_lengkap'      => $validated['alamat_lengkap'],
            'metode_kalibrasi'    => $validated['metode_kalibrasi'],
            'konfirmasi_alamat'   => $request->has('konfirmasi_alamat') ? true : false,
            'daftar_alat'         => !empty($daftarAlatPaths) ? json_encode($daftarAlatPaths) : '[]',
            'catatan_tambahan'    => $validated['catatan_tambahan'],
            'status'              => 'Pengajuan',
            'tanggal_mulai'       => now()->toDateString(),
            'tanggal_selesai'     => null,
            'selesai_menyesuaikan_kapasitas' => false,
            'request_date'        => now()->toDateString(),
        ]);

        $registrationNumber = str_pad($calibration->id, 3, '0', STR_PAD_LEFT) . '-' . now()->format('d-m-Y');
        $calibration->update(['registration_number' => $registrationNumber]);

        return redirect()->route('user.calibrations.index')->with('success', 'Pengajuan kalibrasi berhasil dikirim! Nomor registrasi Anda: ' . $registrationNumber);
    }

    public function show(CalibrationRequest $calibration)
    {
        if (Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')) {
            return redirect()->route('admin.calibrations.show', $calibration);
        }

        abort_if($calibration->user_id !== Auth::id(), 403);
        CalibrationRequest::autoPromoteScheduled();
        CalibrationRequest::autoExpireResubmitWindow();
        $calibration->refresh();
        $calibration->load('certificate');
        return view('user.calibrations.show', compact('calibration'));
    }

    /**
     * Upload ulang dokumen "Daftar Alat" setelah pengajuan ditolak admin
     * KHUSUS karena alasan dokumen & admin mengizinkan resubmit dalam
     * jendela waktu 1x24 jam. Data instansi/kontak & nomor registrasi
     * tetap sama, hanya dokumen yang diganti, status kembali "Pengajuan".
     */
    public function resubmitDokumen(Request $request, CalibrationRequest $calibration)
    {
        abort_if($calibration->user_id !== Auth::id(), 403);

        // Pastikan jendela resubmit yang sudah lewat waktu ditutup dulu,
        // baru dicek apakah pengajuan ini masih boleh di-resubmit.
        CalibrationRequest::autoExpireResubmitWindow();
        $calibration->refresh();

        abort_if(!$calibration->canResubmitDocuments(), 403, 'Batas waktu upload ulang dokumen sudah habis. Silakan buat pengajuan baru.');

        $validated = $request->validate([
            'daftar_alat'   => 'required|array|min:1',
            'daftar_alat.*' => 'file|max:10240',
        ]);

        $daftarAlatPaths = [];
        foreach ($request->file('daftar_alat') as $file) {
            if ($file && $file->isValid()) {
                $daftarAlatPaths[] = $file->store('daftar_alat', 'public');
            }
        }

        $calibration->update([
            'daftar_alat'         => json_encode($daftarAlatPaths),
            'status'              => 'Pengajuan',
            'rejected_at_status'  => null,
            'rejection_reason'    => null,
            'allow_resubmit'      => false,
            'resubmit_deadline'   => null,
            'rejected_at'         => null,
            'admin_note'          => null,
        ]);

        return redirect()->route('user.calibrations.show', $calibration)
            ->with('success', 'Dokumen baru berhasil dikirim! Pengajuan Anda akan direview ulang oleh admin.');
    }
}