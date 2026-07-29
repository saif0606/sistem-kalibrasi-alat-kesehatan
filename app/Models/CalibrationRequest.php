<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id', 'registration_number', 'device_name', 'status', 'rejected_at_status', 'admin_note', 'request_date',
    'nama_instansi', 'nama_kontak', 'nomor_telepon', 'email', 'alamat_lengkap', 'service_category_id', 'daftar_alat', 'catatan_tambahan',
    'metode_kalibrasi', 'konfirmasi_alamat', 'tanggal_mulai', 'tanggal_selesai', 'selesai_menyesuaikan_kapasitas',
    'tanggal_kalibrasi', 'waktu_kalibrasi', 'lokasi_kalibrasi', 'draft_harga', 'bukti_pembayaran',
    'rejection_reason', 'allow_resubmit', 'resubmit_deadline', 'rejected_at',
    'cert_ready_email_sent_at', 'cert_ready_notif_dismissed_at',
])]
class CalibrationRequest extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'request_date' => 'date',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'tanggal_kalibrasi' => 'date',
            'daftar_alat' => 'array',
            'konfirmasi_alamat' => 'boolean',
            'selesai_menyesuaikan_kapasitas' => 'boolean',
            'allow_resubmit' => 'boolean',
            'allow_resubmit' => 'boolean',
            'resubmit_deadline' => 'datetime',
            'rejected_at' => 'datetime',
            'cert_ready_email_sent_at' => 'datetime',
            'cert_ready_notif_dismissed_at' => 'datetime',
        ];
    }

    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }

    /**
     * Naikkan status secara otomatis dari "Penjadwalan" menjadi "Pembayaran"
     * begitu tanggal_kalibrasi yang diinput admin sudah tiba/lewat.
     *
     * Dipanggil dari halaman admin & halaman pelanggan setiap kali daftar
     * pengajuan dibuka, sehingga perubahan status langsung terlihat oleh
     * pelanggan tanpa perlu admin membuka & menyimpan ulang, dan tanpa
     * bergantung pada cron job/scheduler server yang mungkin belum aktif.
     */
    public static function autoPromoteScheduled(): void
    {
        static::whereIn('status', ['Penjadwalan', 'Kalibrasi'])
            ->whereNotNull('tanggal_kalibrasi')
            ->whereDate('tanggal_kalibrasi', '<=', now()->toDateString())
            ->update(['status' => 'Pembayaran']);
    }

    /**
     * Tutup jendela resubmit dokumen begitu batas waktu 1x24 jam terlewati.
     * Setelah ini, allow_resubmit jadi false dan nomor pesanan tsb "hangus"
     * — user tidak bisa lagi upload ulang dokumen untuk pengajuan ini dan
     * wajib membuat pengajuan baru dari awal.
     *
     * Sama seperti autoPromoteScheduled(), dipanggil setiap halaman terkait
     * dibuka (bukan cron), supaya tidak bergantung scheduler server.
     */
    public static function autoExpireResubmitWindow(): void
    {
        static::where('status', 'Ditolak')
            ->where('allow_resubmit', true)
            ->whereNotNull('resubmit_deadline')
            ->where('resubmit_deadline', '<', now())
            ->update(['allow_resubmit' => false]);
    }

    /**
     * True kalau pengajuan ini ditolak karena dokumen, admin mengizinkan
     * resubmit, dan batas waktu 1x24 jam-nya belum lewat.
     */
    public function canResubmitDocuments(): bool
    {
        return $this->status === 'Ditolak'
            && $this->rejection_reason === 'Dokumen'
            && $this->allow_resubmit
            && $this->resubmit_deadline
            && now()->lt($this->resubmit_deadline);
    }

    /**
     * True kalau pengajuan ini SEMPAT diizinkan resubmit dokumen, tapi
     * batas waktunya sudah lewat (nomor pesanan sudah hangus).
     */
    public function isResubmitExpired(): bool
    {
        return $this->status === 'Ditolak'
            && $this->rejection_reason === 'Dokumen'
            && !$this->allow_resubmit
            && $this->resubmit_deadline
            && now()->gte($this->resubmit_deadline);
    }
}