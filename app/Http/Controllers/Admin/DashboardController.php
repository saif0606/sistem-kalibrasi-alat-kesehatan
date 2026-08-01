<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalibrationRequest;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        CalibrationRequest::autoPromoteScheduled();
        CalibrationRequest::autoExpireResubmitWindow();

        $pengajuanBaru  = CalibrationRequest::where('status', 'Pengajuan')->count();
        $totalPesanan   = CalibrationRequest::whereIn('status', [
            'Penjadwalan', 'Kalibrasi', 'Pembayaran', 'Sertifikat'
        ])->count();
        $selesai        = CalibrationRequest::where('status', 'Selesai')->count();
        $ditolak        = CalibrationRequest::where('status', 'Ditolak')->count();
        $totalUser      = User::where('role', 'user')->count();

        // Pengajuan terbaru (10 data)
        $recentCalibrations  = CalibrationRequest::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Statistik per status untuk chart donat / bar mini
        $statusStats = CalibrationRequest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Data untuk grafik garis (All time)
        $chartDataRaw = CalibrationRequest::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_year"),
                DB::raw("YEAR(created_at) as year"),
                DB::raw("DATE_FORMAT(created_at, '%m') as month"),
                DB::raw('count(*) as total')
            )
            ->groupBy('month_year', 'year', 'month')
            ->orderBy('month_year')
            ->get();

        $setting = Setting::current();

        return view('admin.dashboard', compact(
            'pengajuanBaru',
            'totalPesanan',
            'selesai',
            'ditolak',
            'totalUser',
            'recentCalibrations',
            'statusStats',
            'chartDataRaw',
            'setting'
        ));
    }

    public function updateDocument(Request $request)
    {
        $validated = $request->validate([
            'sertifikat_kan'    => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'surat_operasional' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'spreadsheet_url'   => ['nullable', 'url', 'max:2048'],
        ]);

        $setting = Setting::current();

        if ($request->hasFile('sertifikat_kan')) {
            if ($setting->sertifikat_kan) {
                Storage::disk('public')->delete($setting->sertifikat_kan);
            }
            $setting->sertifikat_kan = $request->file('sertifikat_kan')->store('documents', 'public');
        }

        if ($request->hasFile('surat_operasional')) {
            if ($setting->surat_operasional) {
                Storage::disk('public')->delete($setting->surat_operasional);
            }
            $setting->surat_operasional = $request->file('surat_operasional')->store('documents', 'public');
        }

        if ($request->filled('spreadsheet_url')) {
            $setting->spreadsheet_url = $validated['spreadsheet_url'];
        }

        $setting->save();

        return back()->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function exportExcel()
    {
        // Placeholder — bisa dikembangkan untuk export data ke Excel
        $calibrations = CalibrationRequest::with('user')->latest()->get();

        $csv = "No.,Tanggal Pengajuan,Nama FASYANKES,Alamat Pengerjaan,Nama PIC / Jabatan,Tanggal Kalibrasi,Sertifikat Dibuat,Sertifikat Diambil\n";
        $no = 1;
        foreach ($calibrations as $c) {
            $csv .= implode(',', [
                $no++,
                $c->created_at->format('d/m/Y'),
                '"' . str_replace('"', '""', $c->nama_instansi) . '"',
                '"' . str_replace('"', '""', $c->alamat_lengkap ?? '-') . '"',
                '"' . str_replace('"', '""', $c->nama_kontak) . '"',
                $c->tanggal_kalibrasi ? $c->tanggal_kalibrasi->format('d/m/Y') : '',
                '', // Dikosongkan agar bisa diisi manual di excel/spreadsheet
                '', // Dikosongkan agar bisa diisi manual di excel/spreadsheet
            ]) . "\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="data-kalibrasi.csv"',
        ]);
    }
}