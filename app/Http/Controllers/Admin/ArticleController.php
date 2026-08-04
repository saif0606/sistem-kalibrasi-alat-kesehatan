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

        $imageUrl = $this->resolveThumbnailUrl($url);

        if (!$imageUrl) {
            return response()->json([
                'image'   => null,
                'message' => str_contains(strtolower($url), 'instagram.com')
                    ? 'Tidak dapat mengambil gambar dari Instagram secara otomatis. Silakan upload gambar secara manual.'
                    : 'Tidak ada gambar ditemukan di URL ini.',
            ]);
        }

        $response = ['image' => $imageUrl];

        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $match)) {
            $videoId = $match[1];
            $response['fallback'] = "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg";
        }

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'category'     => ['required', 'string', 'in:Instagram,Youtube,Pengumuman,Edukasi,Dokumentasi'],
            'content'      => ['nullable', 'string'],
            'link_url'     => ['nullable', 'url'],
            'image_shape'  => ['nullable', 'string', 'in:square,landscape,portrait'],
            'published_at' => ['nullable', 'date'],
            'image'        => ['nullable', 'image', 'max:5120'],
        ]);

        $validated['slug']    = $this->uniqueSlug($validated['title']);
        $validated['content'] = $validated['content'] ?? '';

        $validated['image'] = $this->resolveArticleImage(
            $request,
            $validated['link_url'] ?? null,
            null
        );

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
            'content'      => ['nullable', 'string'],
            'link_url'     => ['nullable', 'url'],
            'image_shape'  => ['nullable', 'string', 'in:square,landscape,portrait'],
            'published_at' => ['nullable', 'date'],
            'image'        => ['nullable', 'image', 'max:5120'],
        ]);

        if ($validated['title'] !== $article->title) {
            $validated['slug'] = $this->uniqueSlug($validated['title'], $article->id);
        }

        $validated['content'] = $validated['content'] ?? '';

        $newImage = $this->resolveArticleImage(
            $request,
            $validated['link_url'] ?? null,
            $article
        );

        if ($newImage !== null) {
            if ($article->image && $newImage !== $article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $validated['image'] = $newImage;
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

    private function resolveArticleImage(Request $request, ?string $linkUrl, ?Article $existing): ?string
    {
        if ($request->hasFile('image')) {
            return $request->file('image')->store('articles', 'public');
        }

        if (!$linkUrl) {
            return $existing?->image;
        }

        $linkChanged = !$existing || $existing->link_url !== $linkUrl;

        if ($existing?->image && !$linkChanged) {
            return null;
        }

        $thumbUrl = $this->resolveThumbnailUrl($linkUrl);
        if (!$thumbUrl) {
            return $existing?->image;
        }

        return $this->downloadAndStoreThumbnail($thumbUrl) ?? $existing?->image;
    }

    private function resolveThumbnailUrl(string $url): ?string
    {
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $match)) {
            $videoId = $match[1];
            $maxres  = "https://i.ytimg.com/vi/{$videoId}/maxresdefault.jpg";
            $hq      = "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg";

            try {
                $head = Http::timeout(5)->head($maxres);
                if ($head->successful()) {
                    return $maxres;
                }
            } catch (\Exception $e) {
                // fallback ke hqdefault
            }

            return $hq;
        }

        if (str_contains(strtolower($url), 'instagram.com')) {
            return $this->scrapeOgImage($url);
        }

        return $this->scrapeOgImage($url);
    }

    private function scrapeOgImage(string $url): ?string
    {
        try {
            $html = Http::timeout(10)
                ->withoutVerifying()
                ->withHeaders([
                    'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                    'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
                    'Referer'         => 'https://www.google.com/',
                ])
                ->get($url)
                ->body();

            if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/', $html, $m)) {
                return html_entity_decode($m[1]);
            }
            if (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:image["\']/', $html, $m)) {
                return html_entity_decode($m[1]);
            }
        } catch (\Exception $e) {
            // gagal scrape
        }

        return null;
    }

    private function downloadAndStoreThumbnail(string $imageUrl): ?string
    {
        try {
            $response = Http::timeout(15)
                ->withoutVerifying()
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                ])
                ->get($imageUrl);

            if (!$response->successful()) {
                return null;
            }

            $contentType = $response->header('Content-Type', '');
            $extension   = 'jpg';
            if (str_contains($contentType, 'png')) {
                $extension = 'png';
            } elseif (str_contains($contentType, 'webp')) {
                $extension = 'webp';
            }

            $filename = 'articles/' . Str::uuid() . '.' . $extension;
            Storage::disk('public')->put($filename, $response->body());

            return $filename;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i    = 1;

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
