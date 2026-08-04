<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::latest()->get();

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function fetchOg(Request $request)
    {
        $url = $request->query('url');
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['image' => null]);
        }

        // ── YouTube: langsung ambil thumbnail via ytimg.com ──────────────
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $match)) {
            $videoId = $match[1];
            // coba maxresdefault dulu, fallback ke hqdefault
            $maxres = "https://i.ytimg.com/vi/{$videoId}/maxresdefault.jpg";
            $hq     = "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg";
            // maxresdefault kadang 404 pada video lama, tapi hqdefault selalu ada
            return response()->json(['image' => $maxres, 'fallback' => $hq]);
        }

        // ── Instagram: coba scrape dengan header browser penuh ───────────
        // IG tidak blokir semua scraper - tergantung User-Agent & header.
        // Kita pakai header yang mirip browser mobile/desktop biasa.
        if (str_contains(strtolower($url), 'instagram.com')) {
            // pastikan URL adalah format embed atau post publik
            $embedUrl = rtrim($url, '/') . '/?__a=1&__d=dis';
            try {
                $igResponse = Http::timeout(10)
                    ->withoutVerifying()
                    ->withHeaders([
                        'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                        'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                        'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
                        'Accept-Encoding' => 'gzip, deflate, br',
                        'Cache-Control'   => 'no-cache',
                        'Referer'         => 'https://www.google.com/',
                    ])
                    ->get($url);

                $html = $igResponse->body();

                // coba ambil og:image dari HTML
                if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/', $html, $m)) {
                    return response()->json(['image' => $m[1]]);
                }
                if (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:image["\']/', $html, $m)) {
                    return response()->json(['image' => $m[1]]);
                }
            } catch (\Exception $e) {
                // gagal connect
            }

            return response()->json(['image' => null, 'message' => 'Tidak dapat mengambil gambar dari Instagram secara otomatis. Silakan upload gambar secara manual.']);
        }

        // ── URL lain: scrape og:image dengan header browser ──────────────
        try {
            $html = Http::timeout(8)
                ->withoutVerifying()
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                    'Accept'     => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ])
                ->get($url)
                ->body();

            if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/', $html, $m)) {
                return response()->json(['image' => $m[1]]);
            }
            if (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:image["\']/', $html, $m)) {
                return response()->json(['image' => $m[1]]);
            }
            return response()->json(['image' => null, 'message' => 'Tidak ada gambar ditemukan di URL ini.']);
        } catch (\Exception $e) {
            return response()->json(['image' => null, 'message' => 'Gagal mengambil gambar: ' . $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'category'     => ['required', 'string', 'in:Instagram,Youtube,Pengumuman,Edukasi,Dokumentasi'],
            'content'      => ['required', 'string'],
            'link_url'     => ['nullable', 'url'],
            'image_shape'  => ['nullable', 'string', 'in:square,landscape,portrait'],
            'published_at' => ['nullable', 'date'],
            'image'        => ['nullable', 'image', 'max:5120'],
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['title']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('articles', 'public');
        }

        Article::create($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil disimpan.');
    }

    public function show(Article $article)
    {
        return redirect()->route('admin.articles.edit', $article);
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'category'     => ['required', 'string', 'in:Instagram,Youtube,Pengumuman,Edukasi,Dokumentasi'],
            'content'      => ['required', 'string'],
            'link_url'     => ['nullable', 'url'],
            'image_shape'  => ['nullable', 'string', 'in:square,landscape,portrait'],
            'published_at' => ['nullable', 'date'],
            'image'        => ['nullable', 'image', 'max:5120'],
        ]);

        if ($validated['title'] !== $article->title) {
            $validated['slug'] = $this->uniqueSlug($validated['title'], $article->id);
        }

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $validated['image'] = $request->file('image')->store('articles', 'public');
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (
            Article::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
