<?php

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
use App\Http\Controllers\Admin\TechSupportManagementController;

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

    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::post('/news', [NewsController::class, 'updatePreferences'])->name('news.updatePreferences');

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

    // Panel de Estadísticas Administrativas
    Route::prefix('admin/stats')->name('admin.stats.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\AdminStatsController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [App\Http\Controllers\AdminStatsController::class, 'users'])->name('users');
        Route::get('/chats', [App\Http\Controllers\AdminStatsController::class, 'chats'])->name('chats');
        Route::get('/modules', [App\Http\Controllers\AdminStatsController::class, 'modules'])->name('modules');
        Route::get('/errors', [App\Http\Controllers\AdminStatsController::class, 'errors'])->name('errors');
        Route::get('/export', [App\Http\Controllers\AdminStatsController::class, 'export'])->name('export');
    });

    // Administración de Empleados
    Route::prefix('admin/employees')->name('admin.employees.')->middleware(['auth'])->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\EmployeeAdminController::class, 'index'])->name('index');
        Route::get('/import', [App\Http\Controllers\Admin\EmployeeAdminController::class, 'import'])->name('import');
        Route::post('/import', [App\Http\Controllers\Admin\EmployeeAdminController::class, 'processImport'])->name('process-import');
        Route::get('/template', [App\Http\Controllers\Admin\EmployeeAdminController::class, 'downloadTemplate'])->name('template');
        Route::get('/export', [App\Http\Controllers\Admin\EmployeeAdminController::class, 'export'])->name('export');
        // Rutas específicas ANTES de las rutas con parámetros
        Route::delete('/bulk/delete', [App\Http\Controllers\Admin\EmployeeAdminController::class, 'bulkDelete'])->name('bulk-delete');
        // Rutas con parámetros AL FINAL
        Route::delete('/{employee}', [App\Http\Controllers\Admin\EmployeeAdminController::class, 'destroy'])->name('destroy');
        Route::get('/{employee}', [App\Http\Controllers\Admin\EmployeeAdminController::class, 'show'])->name('show');
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
