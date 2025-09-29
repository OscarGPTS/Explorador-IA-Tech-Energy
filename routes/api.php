<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecommendationApiController;
use App\Http\Controllers\Api\NewsApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas API para Recomendaciones
Route::prefix('recommendations')->middleware(['auth:sanctum', 'recommendation.rate.limit'])->group(function () {
    // Generación de recomendaciones
    Route::post('/generate/all', [RecommendationApiController::class, 'generateForAllUsers']);
    Route::post('/generate/user/{userId}', [RecommendationApiController::class, 'generateForUser']);
    Route::post('/generate/role/{roleName}', [RecommendationApiController::class, 'generateForRole']);
    Route::post('/generate/news/{newsId}', [RecommendationApiController::class, 'generateFromNews']);
    
    // Consulta de recomendaciones
    Route::get('/user/{userId}', [RecommendationApiController::class, 'getUserRecommendations']);
    Route::get('/recent', [RecommendationApiController::class, 'getRecentRecommendations']);
    Route::get('/by-type/{typeId}', [RecommendationApiController::class, 'getByType']);
    
    // Estadísticas y administración
    Route::get('/stats', [RecommendationApiController::class, 'getStats']);
    Route::get('/health', [RecommendationApiController::class, 'healthCheck']);
});

// Rutas API para Noticias y Web Scraping
Route::prefix('news')->group(function () {
    // Rutas públicas (solo lectura)
    Route::get('/', [NewsApiController::class, 'index']);
    Route::get('/{id}', [NewsApiController::class, 'show']);
    Route::get('/search', [NewsApiController::class, 'search']);
    Route::get('/types', [NewsApiController::class, 'newsTypes']);
    Route::get('/type/{newsTypeId}/recent', [NewsApiController::class, 'recentByType']);
    
    // Rutas protegidas (administración)
    Route::middleware(['auth:sanctum', 'recommendation.rate.limit'])->group(function () {
        Route::post('/scrape', [NewsApiController::class, 'scrapeNews']);
        Route::get('/scraping/stats', [NewsApiController::class, 'scrapingStats']);
        Route::get('/scraping/health', [NewsApiController::class, 'healthCheck']);
    });
});

// Ruta para verificar estado general del sistema
Route::get('/system/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is running',
        'timestamp' => now()->toISOString(),
        'services' => [
            'recommendations' => 'active',
            'news_scraping' => 'active',
            'ai_service' => 'active'
        ]
    ]);
});