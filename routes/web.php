<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Article;
use App\Models\Service;

/*
|--------------------------------------------------------------------------
| Guard sesi member — pengganti heuristik prefix URL
|--------------------------------------------------------------------------
| Route yang sebenarnya adalah "area member" (Proses, Ajukan Kalibrasi,
| dsb.) memanggil helper ini di baris pertama closure-nya. Selama user
| belum login (session Auth Laravel belum aktif), pengunjung otomatis
| diarahkan ke halaman Login beserta pesan singkat lewat flash
| session('notice').
|
| Dipanggil langsung di dalam closure (bukan lewat ->middleware()) supaya
| tidak bergantung pada registrasi middleware alias di bootstrap/app.php —
| jadi tetap berfungsi persis begitu file ini di-copy ke project manapun.
*/
if (!function_exists('guardMember')) {
    function guardMember(string $message)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('notice', $message);
        }
        
        $user = auth()->user();
        if ($user && in_array($user->role, ['admin', 'super_admin'])) {
            return redirect()->route('admin.dashboard');
        }
        
        return null;
    }
}

/*
|--------------------------------------------------------------------------
| Data pengguna member — dipakai navbar & seluruh halaman area member
|--------------------------------------------------------------------------
| Satu sumber tunggal supaya nama/email/inisial yang tampil di navbar,
| dashboard, riwayat, dan profil SELALU sama persis — diambil dari
| Auth::user() yang sedang login, bukan dari data dummy manapun.
| Nomor HP baru terisi begitu user pernah mengajukan kalibrasi (field
| 'phone' pada tabel users), sebelum itu tetap null/"-" secara jujur.
*/
if (!function_exists('currentMemberUser')) {
    function currentMemberUser(): ?array
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }
        return [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'initial' => mb_strtoupper(mb_substr($user->name, 0, 1)),
            'member_since' => $user->created_at,
        ];
    }
}

/*
|--------------------------------------------------------------------------
| Data dummy — Riwayat Pengajuan Kalibrasi
|--------------------------------------------------------------------------
| Dipusatkan di satu fungsi (pola sama seperti uptdBeritaData()) supaya
| Dashboard, Riwayat Pengajuan, dan Status Terakhir membaca sumber yang
| sama persis — tidak ada lagi nomor pengajuan berbeda-beda di tiap
| halaman. Nomor dibuat OTOMATIS dari urutan tanggal pengajuan (bukan
| ditulis manual satu-satu), format: 001-DDMMYYYY, 002-DDMMYYYY, dst.
| Nanti tinggal diganti Pengajuan::where('user_id', ...)->get() — cukup
| pastikan kolom 'kode' dihasilkan dengan pola yang sama.
*/
if (!function_exists('uptdPengajuanData')) {
    function uptdPengajuanData(): array
    {
        $raw = [
            ['instansi' => 'Klinik Bersalin Mutiara Bunda', 'tanggal' => \Carbon\Carbon::parse('2025-06-25 11:20:00'), 'status' => 'ditolak', 'jumlah_alat' => 118],
            ['instansi' => 'RSUD Abdul Moeloek', 'tanggal' => \Carbon\Carbon::parse('2025-07-02 13:45:00'), 'status' => 'selesai', 'jumlah_alat' => 376],
            ['instansi' => 'Puskesmas Rawat Inap Kedaton', 'tanggal' => \Carbon\Carbon::parse('2025-07-12 09:30:00'), 'status' => 'diproses', 'jumlah_alat' => 214],
            ['instansi' => 'Klinik Pratama Sehat Mandiri', 'tanggal' => \Carbon\Carbon::parse('2025-07-15 14:00:00'), 'status' => 'jadwal', 'jumlah_alat' => 152],
            ['instansi' => 'Laboratorium Klinik Prodia', 'tanggal' => \Carbon\Carbon::parse('2025-07-18 10:15:00'), 'status' => 'menunggu', 'jumlah_alat' => 589],
        ];

        // Urutkan dari yang paling lama supaya nomor urut (001, 002, ...)
        // konsisten mengikuti kronologi pengajuan, baru kode dibentuk.
        usort($raw, fn ($a, $b) => $a['tanggal'] <=> $b['tanggal']);
        foreach ($raw as $i => &$item) {
            $item['kode'] = sprintf('%03d-%s', $i + 1, $item['tanggal']->format('dmY'));
        }
        unset($item);

        // Tampilan (dashboard/riwayat) mengharapkan yang terbaru di atas.
        return array_reverse($raw);
    }
}


/*
|--------------------------------------------------------------------------
| Data dummy — Berita & Informasi (Media Center UPTD)
|--------------------------------------------------------------------------
| Dipusatkan di satu fungsi supaya route index & detail membaca sumber
| yang sama persis. Nanti tinggal diganti Berita::all() /
| Berita::where('slug', $slug)->first() tanpa perlu mengubah view sama
| sekali — key array sengaja meniru nama kolom tabel.
|
| Field 'tanggal' sengaja berupa objek Carbon (bukan string) supaya bisa
| ditampilkan sebagai waktu relatif ("2 jam lalu") di kartu dan tanggal
| lengkap di halaman detail — tinggal panggil ->diffForHumans() atau
| ->translatedFormat() sesuai kebutuhan tampilan.
|
| SELURUH item di bawah ini bersumber dari postingan Instagram resmi
| @uptdifka atau artikel resmi Dinas Kesehatan Provinsi Lampung —
| tidak ada berita/gambar buatan sendiri. Judul & ringkasan ditulis
| ulang dengan bahasa sendiri dari caption aslinya (bukan kutipan
| mentah), gambar memakai foto asli dari postingan/artikel sumber,
| dan 'sumber_url' selalu mengarah ke postingan/artikel aslinya.
|
| Karena sistem ini belum tersambung ke Instagram Graph API (dan
| scraping otomatis diblokir Instagram), penambahan berita baru saat
| ini masih manual: tambahkan entri baru di sini dengan foto yang
| diunduh langsung dari postingan resminya, taruh di
| public/images/berita/, lalu isi 'gambar' dengan asset()-nya.
| Begitu integrasi API/admin CMS tersedia, tinggal diganti
| Berita::latest()->get() tanpa perlu mengubah view sama sekali.
|
| 'featured_social'  → dipakai di section "Update Terbaru Media Sosial"
| 'featured_website' → dipakai di section "Berita Website" (maks. 3)
*/
if (! function_exists('uptdBeritaData')) {
    function uptdBeritaData(): array
    {
        $socialSources = ['Instagram'];

        $manualItems = [
            'seragam-uptd-ifka' => [
                'judul' => 'Seragam UPTD Balai Pengujian dan Kalibrasi',
                'ringkasan' => 'Momen kebersamaan pegawai UPTD Balai Pengujian dan Kalibrasi mengenakan seragam dinas, sebagai bagian dari identitas dan kekompakan dalam memberikan pelayanan.',
                'isi' => [
                    'UPTD Balai Pengujian dan Kalibrasi membagikan momen kebersamaan pegawai yang mengenakan seragam dinas melalui akun Instagram resminya.',
                    'Seragam dinas menjadi salah satu identitas visual pegawai UPTD Balai Pengujian dan Kalibrasi dalam menjalankan tugas pelayanan pengujian dan kalibrasi alat kesehatan sehari-hari.',
                ],
                'tanggal' => now()->subHours(6),
                'kategori' => 'Dokumentasi',
                'sumber' => 'Instagram',
                'sumber_url' => 'https://www.instagram.com/p/Da3KWrvD8MC/?img_index=1',
                'gambar' => asset('images/berita/seragam-uptd-ifka.jpg'),
                'icon' => 'bi-person-badge',
                'real' => true,
            ],
            'profil-uptd-ifka-reel' => [
                'judul' => 'Mengenal UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan',
                'ringkasan' => 'Video profil singkat yang memperkenalkan tugas dan fungsi UPTD Balai Pengujian dan Kalibrasi dalam melayani pengujian serta kalibrasi alat kesehatan di Provinsi Lampung.',
                'isi' => [
                    'Melalui akun Instagram resminya, UPTD Balai Pengujian dan Kalibrasi membagikan video profil singkat yang memperkenalkan tugas dan fungsi utamanya kepada masyarakat, yaitu memberikan pelayanan pengujian dan kalibrasi alat kesehatan bagi fasilitas kesehatan di Provinsi Lampung.',
                    'Video ini menjadi salah satu upaya edukasi publik agar rumah sakit, puskesmas, klinik, dan laboratorium lebih mengenal layanan resmi yang tersedia sebelum menggunakan jasa kalibrasi dari pihak lain.',
                ],
                'tanggal' => now()->subDays(2)->subHours(4),
                'kategori' => 'Dokumentasi',
                'sumber' => 'Instagram',
                'sumber_url' => 'https://www.instagram.com/reel/DMWh-ZYPXj2/',
                'gambar' => asset('images/berita/mengenal-uptd-ifka.jpg'),
                'icon' => 'bi-camera-reels',
                'featured_social' => true,
                'real' => true,
            ],
            'sosialisasi-kalibrasi-uptd' => [
                'judul' => 'Sosialisasi Kalibrasi UPTD',
                'ringkasan' => 'Kegiatan sosialisasi alur pelayanan kalibrasi kepada instansi mitra, menuju layanan farmasi dan kalibrasi alat kesehatan yang berkualitas dan terpercaya.',
                'isi' => [
                    'UPTD Balai Pengujian dan Kalibrasi menyelenggarakan kegiatan sosialisasi alur pelayanan kalibrasi alat kesehatan kepada instansi mitra.',
                    'Kegiatan ini mengusung visi menjadi institusi layanan farmasi dan kalibrasi alat kesehatan yang berkualitas dan terpercaya menuju masyarakat sehat dan mandiri di bidang kesehatan.',
                ],
                'tanggal' => now()->subDays(4),
                'kategori' => 'Dokumentasi',
                'sumber' => 'Instagram',
                'sumber_url' => 'https://www.instagram.com/p/DaQGSrbj95A/?img_index=1',
                'gambar' => asset('images/berita/kalibrasi-sosialisasi.jpg'),
                'icon' => 'bi-mic',
                'real' => true,
            ],
            'kaji-banding-dinkes-sumsel' => [
                'judul' => 'Kaji Banding Dinkes Sumsel ke UPTD Balai Pengujian dan Kalibrasi Lampung',
                'ringkasan' => 'Dinas Kesehatan Provinsi Sumatera Selatan berkunjung ke UPTD Balai Pengujian dan Kalibrasi untuk mempelajari persiapan pembentukan unit serupa di wilayahnya.',
                'isi' => [
                    'Dinas Kesehatan Provinsi Lampung menerima kunjungan kerja dari Dinas Kesehatan Provinsi Sumatera Selatan dalam rangka kaji banding pembentukan UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan (dahulu bernama UPTD IFKA).',
                    'Rombongan diterima jajaran pimpinan UPTD Balai Pengujian dan Kalibrasi dan dilanjutkan dengan peninjauan langsung ke fasilitas pengujian dan kalibrasi alat kesehatan.',
                    'Kegiatan ini menjadi bagian dari upaya berbagi pengalaman antar provinsi dalam membangun layanan kalibrasi alat kesehatan yang lebih merata di Indonesia.',
                ],
                'tanggal' => \Carbon\Carbon::parse('2025-01-15 10:00:00'),
                'kategori' => 'Berita',
                'sumber' => 'Website',
                'sumber_url' => 'https://dinkes.lampungprov.go.id/rencana-pembentukan-uptd-ifka-dinkes-sumsel-kaji-banding-ke-dinkes-lampung/',
                'gambar' => 'https://dinkes.lampungprov.go.id/wp-content/uploads/2025/01/WhatsApp-Image-2025-01-15-at-18.54.58.jpeg',
                'icon' => 'bi-people',
                'featured_website' => true,
                'real' => true,
            ],
            'kunjungan-rotary-ripe' => [
                'judul' => 'Kunjungan Rotary International President Elect ke UPTD Balai Pengujian dan Kalibrasi',
                'ringkasan' => 'UPTD Balai Pengujian dan Kalibrasi turut menjadi lokasi kunjungan rangkaian agenda Rotary International President Elect bersama jajaran Dinas Kesehatan Provinsi Lampung.',
                'isi' => [
                    'UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan menjadi salah satu lokasi kunjungan dalam rangkaian agenda Rotary International President Elect (RIPE) di Provinsi Lampung.',
                    'Kunjungan ini melengkapi agenda peninjauan program kemitraan Rotary Club dengan Pemerintah Provinsi Lampung di bidang kesehatan.',
                    'Kepala Dinas Kesehatan Provinsi Lampung turut mendampingi jalannya kunjungan bersama jajaran pengurus Rotary Club setempat.',
                ],
                'tanggal' => \Carbon\Carbon::parse('2024-08-22 09:00:00'),
                'kategori' => 'Berita',
                'sumber' => 'Website',
                'sumber_url' => 'https://dinkes.lampungprov.go.id/pemerintah-provinsi-lampung-bersama-rotary-club-wujudkan-sinergitas-bidang-kesehatanq/',
                'gambar' => 'https://dinkes.lampungprov.go.id/wp-content/uploads/2024/08/PRM_1970.jpg',
                'icon' => 'bi-globe-asia-australia',
                'featured_website' => true,
                'real' => true,
            ],
        ];

        // Tandai setiap item apakah bersumber dari media sosial atau tidak,
        // dipakai untuk filter "Media Sosial" tanpa perlu mengecek berulang.
        foreach ($manualItems as &$item) {
            $item['is_social'] = in_array($item['sumber'], $socialSources, true);
        }
        unset($item);

        $dbItems = Article::orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->get()
            ->mapWithKeys(function ($article) {
                $contentText = trim(strip_tags($article->content));
                $paragraphs = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $contentText))));

                return [$article->slug => [
                    'judul' => $article->title,
                    'ringkasan' => Str::limit($contentText, 160),
                    'isi' => $paragraphs ?: [$contentText],
                    'tanggal' => $article->published_at ?? $article->created_at,
                    'kategori' => $article->category,
                    'sumber' => 'Website',
                    'sumber_url' => $article->link_url,
                    'gambar' => $article->image ? asset('storage/' . $article->image) : null,
                    'icon' => 'bi-globe2',
                    'is_social' => false,
                    'featured_website' => true,
                    'real' => false,
                ]];
            })
            ->all();

        return collect($dbItems)
            ->merge($manualItems)
            ->sortByDesc(fn ($item) => $item['tanggal'])
            ->all();
    }
}

/*
|--------------------------------------------------------------------------
| Web Routes — UPTD Balai Pengujian dan Kalibrasi Alat Kesehatan
| Provinsi Lampung
|--------------------------------------------------------------------------
|
| Arsitektur multi-page: setiap menu di navbar punya route dan view
| sendiri. Semua halaman selain Beranda masih berupa placeholder
| ("Sedang Dikembangkan") menggunakan komponen <x-coming-soon>, supaya
| seluruh navigasi bisa langsung diuji tanpa 404 sejak tahap ini.
|
| Ganti isi Closure di bawah dengan Controller@method saat masing-masing
| halaman mulai dikembangkan penuh — nama & signature route TIDAK perlu
| berubah, jadi tidak akan merusak link yang sudah ada di navbar/footer.
|
*/

Route::get('/', function () {
    return view('home.index');
})->name('home');

Route::get('/profil', function () {
    return view('pages.profil');
})->name('profil');

Route::get('/layanan', function () {
    $services = Service::latest()->get();
    return view('pages.layanan', compact('services'));
})->name('layanan');

// ===== ADMIN =====
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/document', [\App\Http\Controllers\Admin\DashboardController::class, 'updateDocument'])->name('dashboard.document.update');
    Route::get('/dashboard/export/download', [\App\Http\Controllers\Admin\DashboardController::class, 'exportExcel'])->name('dashboard.export.download');
    Route::resource('articles',           \App\Http\Controllers\Admin\ArticleController::class);
    Route::resource('service-categories', \App\Http\Controllers\Admin\ServiceCategoryController::class);
    Route::resource('services',           \App\Http\Controllers\Admin\ServiceController::class);
    Route::resource('calibrations',       \App\Http\Controllers\Admin\CalibrationController::class);
    Route::post('/calibrations/{calibration}/reply-chat', [\App\Http\Controllers\Admin\CalibrationController::class, 'replyChat'])->name('calibrations.reply-chat');
    Route::resource('users',              \App\Http\Controllers\Admin\UserController::class)->except(['create', 'store']);
    Route::post('/users/store-admin',        [\App\Http\Controllers\Admin\UserController::class, 'storeAdmin'])->name('users.storeAdmin');
    Route::delete('/users/{admin}/destroy-admin', [\App\Http\Controllers\Admin\UserController::class, 'destroyAdmin'])->name('users.destroyAdmin');

    // Chat admin
    Route::get('/chat',  [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/broadcast', [\App\Http\Controllers\Admin\ChatController::class, 'broadcast'])->name('chat.broadcast');
    Route::get('/chat/unread-count', [\App\Http\Controllers\Admin\ChatController::class, 'unreadCount'])->name('chat.unread-count');
    Route::delete('/chat/messages/{message}', [\App\Http\Controllers\Admin\ChatController::class, 'destroy'])->name('chat.messages.destroy');
    Route::get('/chat/{user}/messages', [\App\Http\Controllers\Admin\ChatController::class, 'messages'])->name('chat.messages');
    Route::post('/chat/{user}', [\App\Http\Controllers\Admin\ChatController::class, 'store'])->name('chat.store');
});

// ===== USER / PELANGGAN — Proses Kalibrasi (menggantikan placeholder /proses lama) =====
Route::prefix('proses')->name('user.')->middleware(['auth'])->group(function () {
    Route::get('/',          [\App\Http\Controllers\User\CalibrationController::class, 'index'])->name('calibrations.index');
    Route::get('/ajukan',   [\App\Http\Controllers\User\CalibrationController::class, 'create'])->name('calibrations.create');
    Route::post('/ajukan',  [\App\Http\Controllers\User\CalibrationController::class, 'store'])->name('calibrations.store');
    Route::get('/{calibration}', [\App\Http\Controllers\User\CalibrationController::class, 'show'])->name('calibrations.show');
    Route::post('/{calibration}/resubmit-dokumen', [\App\Http\Controllers\User\CalibrationController::class, 'resubmitDokumen'])->name('calibrations.resubmit-dokumen');
    Route::delete('/{calibration}/bukti-pembayaran', [\App\Http\Controllers\User\CalibrationController::class, 'destroyBuktiPembayaran'])->name('calibrations.bukti-pembayaran.delete');

    Route::post('/{calibration}/dismiss-cert-notif', function (\App\Models\CalibrationRequest $calibration) {
        abort_if($calibration->user_id !== auth()->id(), 403);
        $calibration->update(['cert_ready_notif_dismissed_at' => now()]);
        return response()->json(['success' => true]);
    })->name('calibrations.dismiss-cert-notif');
});

Route::prefix('chat')->name('user.chat.')->middleware(['auth'])->group(function () {
    Route::get('/',         [\App\Http\Controllers\User\ChatController::class, 'index'])->name('index');
    Route::post('/',        [\App\Http\Controllers\User\ChatController::class, 'store'])->name('store');
    Route::get('/messages', [\App\Http\Controllers\User\ChatController::class, 'messages'])->name('messages');
    Route::get('/unread-count', [\App\Http\Controllers\User\ChatController::class, 'unreadCount'])->name('unread-count'); // TAMBAHAN
    Route::delete('/messages/{message}', [\App\Http\Controllers\User\ChatController::class, 'destroy'])->name('destroy');
});

Route::get('/berita', function () {
    $beritaList = uptdBeritaData();

    return view('pages.berita', compact('beritaList'));
})->name('berita');

Route::get('/berita/{slug}', function (string $slug) {
    $item = uptdBeritaData()[$slug] ?? null;

    return view('pages.berita-detail', [
        'item' => $item,
    ]);
})->name('berita.show');

Route::get('/kontak', function () {
    return view('pages.kontak');
})->name('kontak');

Route::get('/chatbot', function () {
    if ($redirect = guardMember('Silakan login untuk menggunakan Chatbot.')) {
        return $redirect;
    }
    return redirect()->route('user.chat.index');
})->name('chatbot');

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Login Admin — form & identitas visual PERSIS SAMA dengan /login (view
// auth.login tidak diubah), hanya endpoint submit-nya beda nama route
// supaya URL /admin/login bisa dipakai terpisah sesuai permintaan.
Route::get('/admin/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('admin.login.submit');

Route::get('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])->name('register.submit');

Route::get('/lupa-password', function () {
    return view('auth.forgot-password');
})->name('lupa-password');

/*
|--------------------------------------------------------------------------
| Area Member — dilindungi session Auth Laravel sungguhan
|--------------------------------------------------------------------------
| guardMember() memanggil auth()->check() — status login memakai session
| Laravel asli yang diaktifkan lewat Auth::attempt() di
| AuthenticatedSessionController, jadi konsisten di halaman mana pun
| (Berita, Layanan, Kontak, dst.) selama sesi masih aktif, dan tidak
| pernah otomatis kembali ke versi guest sebelum user logout sendiri.
*/

Route::get('/dashboard', function () {
    if ($redirect = guardMember('Silakan login terlebih dahulu untuk mengakses Dashboard.')) {
        return $redirect;
    }
    $pengajuanList = uptdPengajuanData();

    // Ambil kalibrasi yang ditolak karena dokumen dan masih bisa diupload ulang
    \App\Models\CalibrationRequest::autoExpireResubmitWindow();
    $rejectedDocCalibrations = \App\Models\CalibrationRequest::where('user_id', auth()->id())
        ->where('status', 'Ditolak')
        ->where('rejection_reason', 'Dokumen')
        ->where('allow_resubmit', true)
        ->whereNotNull('resubmit_deadline')
        ->where('resubmit_deadline', '>', now())
        ->latest()
        ->get();

    return view('member.dashboard', [
        'memberUser'              => currentMemberUser(),
        'riwayatTerbaru'          => array_slice($pengajuanList, 0, 5),
        'statusTerakhir'          => $pengajuanList[0] ?? null,
        'rejectedDocCalibrations' => $rejectedDocCalibrations,
    ]);
})->name('dashboard');

Route::get('/dashboard/profile', function () {
    if ($redirect = guardMember('Silakan login terlebih dahulu untuk mengakses halaman ini.')) {
        return $redirect;
    }
    return view('member.profile', [
        'memberUser' => currentMemberUser(),
    ]);
})->name('dashboard.profile');

Route::get('/dashboard/riwayat', function () {
    if ($redirect = guardMember('Silakan login terlebih dahulu untuk melihat riwayat pengajuan.')) {
        return $redirect;
    }
    return view('member.riwayat', [
        'riwayatList' => uptdPengajuanData(),
    ]);
})->name('dashboard.riwayat');