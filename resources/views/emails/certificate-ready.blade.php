<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; background:#f3fbf6; padding:24px; margin:0;">
    <div style="max-width:520px; margin:0 auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 4px 16px rgba(0,0,0,0.06);">
        <div style="background:linear-gradient(135deg,#17a45c,#2b6ff0); padding:28px 24px; text-align:center;">
            <div style="margin-bottom:12px;">
                <img src="{{ $logoSrc }}" alt="Logo UPTD Kalibrasi" style="height:72px; width:auto; object-fit:contain;">
            </div>
            <h1 style="color:#fff; font-size:20px; margin:0;">Sertifikat Kalibrasi Anda Sudah Terbit!</h1>
        </div>
        <div style="padding:28px 24px; color:#0c2438;">
            <p style="font-size:15px; line-height:1.6;">
                Halo <strong>{{ $calibration->nama_kontak }}</strong>,
            </p>
            <p style="font-size:15px; line-height:1.6;">
                Sertifikat kalibrasi untuk pengajuan Anda dengan nomor
                <strong style="font-family:monospace;">{{ $calibration->registration_number }}</strong>
                ({{ $calibration->device_name }}) telah selesai diterbitkan.
            </p>
            <div style="background:rgba(23,164,92,0.08); border:1px solid rgba(23,164,92,0.2); border-radius:12px; padding:16px; margin:20px 0; font-size:14px;">
                <strong>Silakan ambil sertifikat fisik Anda langsung di kantor UPTD Balai Pengujian & Kalibrasi</strong> pada jam kerja.
            </div>
            <p style="font-size:14px; color:#3d5468; line-height:1.6;">
                Anda bisa melihat rincian pengajuan dan riwayat proses melalui portal kami:
            </p>
            <div style="text-align:center; margin:24px 0;">
                <a href="{{ route('user.calibrations.show', $calibration->id) }}"
                   style="display:inline-block; background:linear-gradient(135deg,#17a45c,#2b6ff0); color:#fff; text-decoration:none; font-weight:700; padding:12px 28px; border-radius:999px; font-size:14px;">
                    Lihat Detail Pesanan
                </a>
            </div>
            <p style="font-size:12.5px; color:#7189a0; margin-top:24px;">
                Email ini dikirim otomatis oleh sistem UPTD Kalibrasi. Mohon tidak membalas email ini — hubungi kami via chat di portal atau WhatsApp jika ada pertanyaan.
            </p>
        </div>
    </div>
</body>
</html>