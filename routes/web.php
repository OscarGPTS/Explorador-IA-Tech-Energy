<?php

use App\Http\Controllers\Admin\EmployeeAdminController;
use App\Http\Controllers\Admin\RecommendationAdminController;
use App\Http\Controllers\Admin\NewsAdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RecommendationsController;
use App\Http\Controllers\AgentConfigurationController;
use App\Http\Controllers\CorporateInfoController;
use App\Http\Controllers\TechSupportController;
use App\Http\Controllers\DocumentBotController;
use App\Http\Controllers\Admin\TechSupportManagementController;
use App\Http\Controllers\AdminStatsController;

Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
Route::post('/logout/google', [GoogleController::class, 'logout'])->name('google.logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/recommendations', [RecommendationsController::class, 'index'])->name('recommendations.index');
    Route::post('/recommendations', [RecommendationsController::class, 'updatePreferences'])->name('recommendations.updatePreferences');

    // Administración de Recomendaciones (CRUD) — limitado por permiso
    Route::prefix('admin/recommendations')->name('admin.recommendations.')
        ->middleware('permission:manage-recommendations')->group(function () {
            Route::post('/', [RecommendationAdminController::class, 'store'])->name('store');
            Route::put('/{recommendation}', [RecommendationAdminController::class, 'update'])->name('update');
            Route::delete('/{recommendation}', [RecommendationAdminController::class, 'destroy'])->name('destroy');
        });

    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::post('/news', [NewsController::class, 'updatePreferences'])->name('news.updatePreferences');

    // Administración de Noticias + fuentes de scraping — limitado por permiso
    Route::prefix('admin/news')->name('admin.news.')
        ->middleware('permission:manage-news')->group(function () {
            Route::get('/', [NewsAdminController::class, 'index'])->name('index');

            // Noticias (carga manual)
            Route::post('/items', [NewsAdminController::class, 'storeNews'])->name('items.store');
            Route::put('/items/{news}', [NewsAdminController::class, 'updateNews'])->name('items.update');
            Route::delete('/items/{news}', [NewsAdminController::class, 'destroyNews'])->name('items.destroy');

            // Fuentes de scraping
            Route::post('/sources', [NewsAdminController::class, 'storeSource'])->name('sources.store');
            Route::put('/sources/{source}', [NewsAdminController::class, 'updateSource'])->name('sources.update');
            Route::delete('/sources/{source}', [NewsAdminController::class, 'destroySource'])->name('sources.destroy');
            Route::post('/sources/{source}/toggle', [NewsAdminController::class, 'toggleSource'])->name('sources.toggle');

            // Disparo manual del scraping (bajo demanda)
            Route::post('/scrape', [NewsAdminController::class, 'scrapeNow'])
                ->middleware('throttle:12,1')->name('scrape');
        });

    Route::get('/chat', function () {
        return view('chat.index');
    })->name('chat.index');

    // Vista para configuración de agentes IA
    Route::get('/agent-config', function () {
        return view('agent-config');
    })->name('agent.config.view');

    // Rutas para configuración de agentes IA
    Route::prefix('agent-config')->name('agent.')->group(function () {
        Route::get('/roles', [AgentConfigurationController::class, 'getAvailableRoles'])->name('roles');
        Route::get('/settings', [AgentConfigurationController::class, 'getUserSettings'])->name('settings');
        Route::post('/settings', [AgentConfigurationController::class, 'createUserSetting'])->name('settings.create');
        Route::put('/settings/{id}', [AgentConfigurationController::class, 'updateUserSetting'])->name('settings.update');
        Route::delete('/settings/{id}', [AgentConfigurationController::class, 'deleteUserSetting'])->name('settings.delete');
        Route::get('/default', [AgentConfigurationController::class, 'getDefaultConfiguration'])->name('default');
        Route::get('/chat/{chatGroupId}', [AgentConfigurationController::class, 'getChatConfiguration'])->name('chat.config');
        Route::post('/chat/{chatGroupId}', [AgentConfigurationController::class, 'applyChatConfiguration'])->name('chat.apply');
    });

    // Rutas para chatbot corporativo flotante
    Route::prefix('corporate-chat')->name('corporate.')->group(function () {
        Route::post('/message', [CorporateInfoController::class, 'chatBot'])->name('chat');
        Route::get('/employees/search', [CorporateInfoController::class, 'searchEmployees'])->name('employees.search');
        Route::get('/employees/tags', [CorporateInfoController::class, 'getEmployeeTags'])->name('employees.tags');
        Route::get('/documents/tags', [CorporateInfoController::class, 'getDocumentTags'])->name('documents.tags');
        Route::get('/locations/search', [CorporateInfoController::class, 'searchLocations'])->name('locations.search');
        Route::get('/documents/search', [CorporateInfoController::class, 'searchDocuments'])->name('documents.search');
    });

    // Rutas para módulo de soporte técnico
    Route::prefix('tech-support')->name('tech-support.')->group(function () {
        Route::get('/', [TechSupportController::class, 'index'])->name('index');
        Route::get('/dashboard', [TechSupportController::class, 'dashboard'])->name('dashboard');
        Route::post('/interact', [TechSupportController::class, 'handleInteraction'])->name('interact');
    });

    // Rutas para buscador de documentos corporativos
    Route::prefix('document-bot')->name('document-bot.')->group(function () {
        // Vista principal
        Route::get('/', [DocumentBotController::class, 'index'])->name('index');
        
        // Health check general (combina ambos bots)
        Route::get('/health', [DocumentBotController::class, 'healthCheck'])->name('health');
        
        // === Bot Simple (Ollama - Local) ===
        Route::prefix('simple')->name('simple.')->group(function () {
            Route::get('/health', [DocumentBotController::class, 'simpleHealthCheck'])->name('health');
            Route::post('/query', [DocumentBotController::class, 'query'])->name('query');
            Route::post('/analyze-document', [DocumentBotController::class, 'analyzeDocument'])->name('analyze-document');
            Route::get('/documents', [DocumentBotController::class, 'listDocuments'])->name('documents');
            Route::get('/recent-documents', [DocumentBotController::class, 'recentDocuments'])->name('recent-documents');
        });
        
        // === Bot Avanzado (OpenAI - Cloud) ===
        Route::prefix('advanced')->name('advanced.')->group(function () {
            Route::get('/health', [DocumentBotController::class, 'advancedHealthCheck'])->name('health');
            Route::post('/quick-query', [DocumentBotController::class, 'quickQuery'])->name('quick-query');
            Route::post('/deep-reasoning', [DocumentBotController::class, 'deepReasoning'])->name('deep-reasoning');
            Route::post('/semantic-search', [DocumentBotController::class, 'semanticSearch'])->name('semantic-search');
            Route::get('/stats', [DocumentBotController::class, 'stats'])->name('stats');
            Route::get('/documents', [DocumentBotController::class, 'advancedListDocuments'])->name('documents');
            Route::get('/recent-documents', [DocumentBotController::class, 'advancedRecentDocuments'])->name('recent-documents');
            Route::post('/reindex', [DocumentBotController::class, 'reindex'])->name('reindex')->middleware('role:admin');
        });
        
        // Rutas de compatibilidad (legacy - apuntan al bot simple)
        Route::post('/query', [DocumentBotController::class, 'query'])->name('query');
        Route::post('/analyze-document', [DocumentBotController::class, 'analyzeDocument'])->name('analyze-document');
        Route::get('/documents', [DocumentBotController::class, 'listDocuments'])->name('documents');
        Route::get('/recent-documents', [DocumentBotController::class, 'recentDocuments'])->name('recent-documents');
        Route::post('/quick-query', [DocumentBotController::class, 'quickQuery'])->name('quick-query');
        Route::post('/deep-reasoning', [DocumentBotController::class, 'deepReasoning'])->name('deep-reasoning');
        Route::post('/semantic-search', [DocumentBotController::class, 'semanticSearch'])->name('semantic-search');
        Route::get('/stats', [DocumentBotController::class, 'stats'])->name('stats');
        Route::post('/reindex', [DocumentBotController::class, 'reindex'])->name('reindex')->middleware('role:admin');
    });

    // Panel de Estadísticas Administrativas
    Route::prefix('admin/stats')->name('admin.stats.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [AdminStatsController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminStatsController::class, 'users'])->name('users');
        Route::get('/chats', [AdminStatsController::class, 'chats'])->name('chats');
        Route::get('/modules', [AdminStatsController::class, 'modules'])->name('modules');
        Route::get('/errors', [AdminStatsController::class, 'errors'])->name('errors');
        Route::get('/export', [AdminStatsController::class, 'export'])->name('export');
    });

    // Administración de Empleados
    Route::prefix('admin/employees')->name('admin.employees.')->middleware(['auth'])->group(function () {
        Route::get('/', [EmployeeAdminController::class, 'index'])->name('index');
        Route::get('/import', [EmployeeAdminController::class, 'import'])->name('import');
        Route::post('/import', [EmployeeAdminController::class, 'processImport'])->name('process-import');
        Route::get('/template', [EmployeeAdminController::class, 'downloadTemplate'])->name('template');
        Route::get('/export', [EmployeeAdminController::class, 'export'])->name('export');
        // Rutas específicas ANTES de las rutas con parámetros
        Route::delete('/bulk/delete', [EmployeeAdminController::class, 'bulkDelete'])->name('bulk-delete');
        // Rutas con parámetros AL FINAL
        Route::delete('/{employee}', [EmployeeAdminController::class, 'destroy'])->name('destroy');
        Route::get('/{employee}', [EmployeeAdminController::class, 'show'])->name('show');
    });

    // Rutas para gestión de soporte técnico
    Route::prefix('admin/tech-support')->name('admin.tech-support.')->middleware(['auth'])->group(function () {
        Route::get('/', [TechSupportManagementController::class, 'index'])->name('index');
        
        // Categorías
        Route::post('/categories', [TechSupportManagementController::class, 'storeCategory'])->name('categories.store');
        Route::put('/categories/{category}', [TechSupportManagementController::class, 'updateCategory'])->name('categories.update');
        Route::delete('/categories/{category}', [TechSupportManagementController::class, 'destroyCategory'])->name('categories.destroy');
        
        // Problemas
        Route::post('/problems', [TechSupportManagementController::class, 'storeProblem'])->name('problems.store');
        Route::put('/problems/{problem}', [TechSupportManagementController::class, 'updateProblem'])->name('problems.update');
        Route::delete('/problems/{problem}', [TechSupportManagementController::class, 'destroyProblem'])->name('problems.destroy');
        
        // Toggle active
        Route::post('/toggle-active', [TechSupportManagementController::class, 'toggleActive'])->name('toggle-active');
    });
});

require __DIR__.'/auth.php';
