<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NewsScrapingService;
use App\Models\News;
use App\Models\NewsType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class NewsApiController extends Controller
{
    private NewsScrapingService $scrapingService;

    public function __construct(NewsScrapingService $scrapingService)
    {
        $this->scrapingService = $scrapingService;
    }

    /**
     * Obtener todas las noticias con paginación y filtros
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'page' => 'integer|min:1',
            'per_page' => 'integer|min:1|max:100',
            'news_type_id' => 'integer|exists:news_type,id',
            'source' => 'string|in:eluniversal,elfinanciero,milenio',
            'is_scraped' => 'boolean',
            'date_from' => 'date',
            'date_to' => 'date|after_or_equal:date_from',
            'search' => 'string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $query = News::with('newsType');

            // Aplicar filtros
            if ($request->has('news_type_id')) {
                $query->where('news_type_id', $request->news_type_id);
            }

            if ($request->has('source')) {
                $query->where('source', $request->source);
            }

            if ($request->has('is_scraped')) {
                $query->where('is_scraped', $request->boolean('is_scraped'));
            }

            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            if ($request->has('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('content', 'LIKE', "%{$searchTerm}%");
                });
            }

            // Ordenar por fecha de creación descendente
            $query->orderBy('created_at', 'desc');

            // Paginar
            $perPage = $request->input('per_page', 15);
            $news = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $news->items(),
                'pagination' => [
                    'current_page' => $news->currentPage(),
                    'per_page' => $news->perPage(),
                    'total' => $news->total(),
                    'last_page' => $news->lastPage(),
                    'from' => $news->firstItem(),
                    'to' => $news->lastItem()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving news',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener una noticia específica
     */
    public function show(int $id): JsonResponse
    {
        try {
            $news = News::with('newsType')->find($id);

            if (!$news) {
                return response()->json([
                    'success' => false,
                    'message' => 'News not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $news
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving news',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ejecutar scraping de noticias manualmente
     */
    public function scrapeNews(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'source' => 'string|in:eluniversal,elfinanciero,milenio',
            'clean_old' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Limpiar noticias antiguas si se solicita
            if ($request->boolean('clean_old')) {
                $deleted = $this->scrapingService->cleanOldNews();
                $cleanMessage = $deleted > 0 ? "Deleted {$deleted} old news articles. " : '';
            } else {
                $cleanMessage = '';
            }

            // Ejecutar scraping
            $startTime = microtime(true);
            $results = $this->scrapingService->scrapeAllSources();
            $duration = round(microtime(true) - $startTime, 2);

            return response()->json([
                'success' => true,
                'message' => $cleanMessage . 'News scraping completed successfully',
                'data' => [
                    'total_processed' => $results['total'],
                    'successfully_saved' => $results['success'],
                    'errors' => $results['errors'],
                    'success_rate' => $results['total'] > 0 ? round(($results['success'] / $results['total']) * 100, 2) : 0,
                    'duration_seconds' => $duration,
                    'details' => $results['details']
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during news scraping',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de scraping
     */
    public function scrapingStats(): JsonResponse
    {
        try {
            $stats = $this->scrapingService->getScrapingStats();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving scraping statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener tipos de noticias disponibles
     */
    public function newsTypes(): JsonResponse
    {
        try {
            $newsTypes = NewsType::orderBy('name')->get();

            return response()->json([
                'success' => true,
                'data' => $newsTypes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving news types',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener noticias recientes por tipo
     */
    public function recentByType(int $newsTypeId, Request $request): JsonResponse
    {
        $validator = Validator::make(['news_type_id' => $newsTypeId] + $request->all(), [
            'news_type_id' => 'required|integer|exists:news_type,id',
            'limit' => 'integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $limit = $request->input('limit', 10);
            
            $news = News::with('newsType')
                ->where('news_type_id', $newsTypeId)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $news
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving recent news',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar noticias
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:3|max:255',
            'news_type_id' => 'integer|exists:news_type,id',
            'source' => 'string|in:eluniversal,elfinanciero,milenio',
            'limit' => 'integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $query = News::with('newsType');
            $searchTerm = $request->input('q');

            // Búsqueda en título y contenido
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('content', 'LIKE', "%{$searchTerm}%");
            });

            // Filtros adicionales
            if ($request->has('news_type_id')) {
                $query->where('news_type_id', $request->news_type_id);
            }

            if ($request->has('source')) {
                $query->where('source', $request->source);
            }

            $limit = $request->input('limit', 20);
            $news = $query->orderBy('created_at', 'desc')
                          ->limit($limit)
                          ->get();

            return response()->json([
                'success' => true,
                'data' => $news,
                'search_term' => $searchTerm,
                'results_count' => $news->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching news',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Health check para el sistema de scraping
     */
    public function healthCheck(): JsonResponse
    {
        try {
            $stats = $this->scrapingService->getScrapingStats();
            $lastScraping = $stats['last_scraping'];
            
            // Verificar si el último scraping fue hace más de 24 horas
            $isHealthy = true;
            $warnings = [];

            if (!$lastScraping) {
                $isHealthy = false;
                $warnings[] = 'No scraping has been performed yet';
            } elseif ($lastScraping->diffInHours(Carbon::now()) > 24) {
                $isHealthy = false;
                $warnings[] = 'Last scraping was more than 24 hours ago';
            }

            // Verificar si hay noticias recientes
            $recentNews = News::where('created_at', '>=', Carbon::now()->subDay())->count();
            if ($recentNews === 0) {
                $warnings[] = 'No news scraped in the last 24 hours';
            }

            return response()->json([
                'success' => true,
                'healthy' => $isHealthy,
                'warnings' => $warnings,
                'data' => [
                    'last_scraping' => $lastScraping?->format('Y-m-d H:i:s'),
                    'total_news' => $stats['total_scraped_news'],
                    'recent_news_24h' => $recentNews,
                    'timestamp' => Carbon::now()->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'healthy' => false,
                'message' => 'Health check failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}