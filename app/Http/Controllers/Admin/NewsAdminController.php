<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\RunScrapingJob;
use App\Models\News;
use App\Models\NewsType;
use App\Models\RecommendationType;
use App\Models\ScrapingSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsAdminController extends Controller
{
    /**
     * Panel administrativo: noticias, fuentes de scraping y estado del servicio.
     */
    public function index()
    {
        $news = News::with('newsType')->orderByDesc('created_at')->paginate(12);
        $sources = ScrapingSource::orderBy('module')->orderBy('name')->get();
        $newsTypes = NewsType::orderBy('name')->get();
        $recommendationTypes = RecommendationType::orderBy('name')->get();

        // Resumen de estado del servicio (panel en vivo).
        $serviceStatus = [
            'total'    => $sources->count(),
            'active'   => $sources->where('is_active', true)->count(),
            'ok'       => $sources->where('last_status', 'ok')->count(),
            'error'    => $sources->where('last_status', 'error')->count(),
            'never'    => $sources->where('last_status', 'never')->count(),
            'last_run' => $sources->whereNotNull('last_run_at')->max('last_run_at'),
        ];

        return view('news.admin', compact(
            'news', 'sources', 'newsTypes', 'recommendationTypes', 'serviceStatus'
        ));
    }

    /* ===================== Noticias (carga manual) ===================== */

    private function newsRules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'content'       => ['nullable', 'string'],
            'news_type_id'  => ['required', 'exists:news_type,id'],
            'external_link' => ['nullable', 'url', 'max:255'],
            'image_url'     => ['nullable', 'url', 'max:255'],
            'image_file'    => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:4096'],
        ];
    }

    public function storeNews(Request $request)
    {
        $data = $request->validate($this->newsRules());
        $news = new News();
        $this->fillNews($news, $request, $data);
        $news->is_scraped = false;
        $news->save();

        return back()->with('status', 'Noticia creada correctamente.');
    }

    public function updateNews(Request $request, News $news)
    {
        $data = $request->validate($this->newsRules());
        $this->fillNews($news, $request, $data);
        $news->save();

        return back()->with('status', 'Noticia actualizada correctamente.');
    }

    public function destroyNews(News $news)
    {
        if ($news->image && Storage::disk('public')->exists('news-images/' . $news->image)) {
            Storage::disk('public')->delete('news-images/' . $news->image);
        }
        $news->delete();

        return back()->with('status', 'Noticia eliminada correctamente.');
    }

    private function fillNews(News $news, Request $request, array $data): void
    {
        $news->fill([
            'title'         => $data['title'],
            'description'   => $data['description'] ?? null,
            'content'       => $data['content'] ?? null,
            'news_type_id'  => $data['news_type_id'],
            'external_link' => $data['external_link'] ?? null,
            'image_url'     => $data['image_url'] ?? null,
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $name = uniqid('news_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('news-images', $name, 'public');

            if ($news->image && Storage::disk('public')->exists('news-images/' . $news->image)) {
                Storage::disk('public')->delete('news-images/' . $news->image);
            }
            $news->image = $name;
        }
    }

    /* ===================== Fuentes de scraping ===================== */

    private function sourceRules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'module'    => ['required', 'in:news,recommendations'],
            'feed_type' => ['required', 'in:rss,html'],
            'url'       => ['required', 'url', 'max:1000'],
            'type_id'   => ['nullable', 'integer'],
            'sub_area'  => ['nullable', 'string', 'max:255'],
            'max_items' => ['nullable', 'integer', 'min:1', 'max:50'],
            'selectors' => ['nullable', 'string'], // JSON crudo para HTML
        ];
    }

    public function storeSource(Request $request)
    {
        $data = $request->validate($this->sourceRules());

        ScrapingSource::create([
            'name'      => $data['name'],
            'module'    => $data['module'],
            'feed_type' => $data['feed_type'],
            'url'       => $data['url'],
            'type_id'   => $data['type_id'] ?? null,
            'sub_area'  => $data['sub_area'] ?? null,
            'max_items' => $data['max_items'] ?? 10,
            'selectors' => $this->parseSelectors($data['selectors'] ?? null),
            'is_active' => true,
        ]);

        return back()->with('status', 'Fuente de scraping creada correctamente.');
    }

    public function updateSource(Request $request, ScrapingSource $source)
    {
        $data = $request->validate($this->sourceRules());

        $source->update([
            'name'      => $data['name'],
            'module'    => $data['module'],
            'feed_type' => $data['feed_type'],
            'url'       => $data['url'],
            'type_id'   => $data['type_id'] ?? null,
            'sub_area'  => $data['sub_area'] ?? null,
            'max_items' => $data['max_items'] ?? 10,
            'selectors' => $this->parseSelectors($data['selectors'] ?? null),
        ]);

        return back()->with('status', 'Fuente actualizada correctamente.');
    }

    public function destroySource(ScrapingSource $source)
    {
        $source->delete();
        return back()->with('status', 'Fuente eliminada correctamente.');
    }

    public function toggleSource(ScrapingSource $source)
    {
        $source->update(['is_active' => !$source->is_active]);
        return back()->with('status', 'Estado de la fuente actualizado.');
    }

    private function parseSelectors(?string $raw): ?array
    {
        if (!$raw) {
            return null;
        }
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : null;
    }

    /* ===================== Disparo de scraping ===================== */

    public function scrapeNow(Request $request)
    {
        $data = $request->validate([
            'module'    => ['nullable', 'in:news,recommendations'],
            'source_id' => ['nullable', 'integer', 'exists:scraping_sources,id'],
        ]);

        if (!empty($data['source_id'])) {
            $source = ScrapingSource::find($data['source_id']);
            RunScrapingJob::dispatch($source->module, $source->id);
            return back()->with('status', "Scraping de la fuente «{$source->name}» encolado. Refresca en unos segundos para ver el resultado.");
        }

        $module = $data['module'] ?? 'news';
        RunScrapingJob::dispatch($module);

        return back()->with('status', "Scraping del módulo «{$module}» encolado. Refresca en unos segundos para ver el resultado.");
    }
}
