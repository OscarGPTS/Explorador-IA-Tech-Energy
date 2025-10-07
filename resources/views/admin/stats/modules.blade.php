@extends('layouts.app')

@push('styles')
<style>
/* Animaciones y transiciones suaves */
.stats-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateY(0);
}

.stats-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.gradient-text {
    background: linear-gradient(135deg, #DC2626 0%, #FBBF24 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.activity-bar {
    background: linear-gradient(135deg, #DC2626 0%, #FBBF24 100%);
    transition: all 0.3s ease;
}

.activity-bar:hover {
    transform: scaleY(1.1);
}

.nav-link-active {
    border-color: #DC2626;
    color: #DC2626;
}

.nav-link {
    transition: all 0.3s ease;
}

.nav-link:hover {
    color: #DC2626;
    border-color: #FBBF24;
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-yellow-50">
    <!-- Header mejorado con gradiente rojo-amarillo -->
    <div class="bg-gradient-to-r from-red-600 via-orange-500 to-yellow-500 text-white">
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="/" class="p-2 rounded-full bg-white/20 hover:bg-white/30 transition-all duration-300 transform hover:scale-110">
                        <svg width="24px" height="24px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                            <path fill="currentColor" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"/>
                            <path fill="currentColor" d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold">📊 Estadísticas de Módulos</h1>
                        <p class="text-orange-100 text-sm mt-1">Análisis detallado del uso de los diferentes módulos y aplicaciones del sistema</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.stats.export', ['type' => 'modules', 'format' => 'csv']) }}" 
                       class="flex items-center space-x-2 bg-white/20 hover:bg-white/30 backdrop-filter backdrop-blur-sm border border-white/30 font-medium rounded-full text-sm px-6 py-3 text-white transition-all duration-300 transform hover:scale-105">
                        <span>📥 CSV</span>
                    </a>
                    <a href="{{ route('admin.stats.export', ['type' => 'modules', 'format' => 'excel']) }}" 
                       class="flex items-center space-x-2 bg-white/20 hover:bg-white/30 backdrop-filter backdrop-blur-sm border border-white/30 font-medium rounded-full text-sm px-6 py-3 text-white transition-all duration-300 transform hover:scale-105">
                        <span>📊 Excel</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Navegación de estadísticas -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg mb-8 border border-white/20">
            <div class="border-b border-gray-100">
                <nav class="-mb-px flex space-x-8 px-6">
                    <a href="{{ route('admin.stats.dashboard') }}" 
                       class="nav-link border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-600">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('admin.stats.users') }}" 
                       class="nav-link border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-600">
                        👥 Usuarios
                    </a>
                    <a href="{{ route('admin.stats.chats') }}" 
                       class="nav-link border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-600">
                        💬 Chats
                    </a>
                    <a href="{{ route('admin.stats.modules') }}" 
                       class="nav-link-active border-b-2 py-4 px-1 text-sm font-medium">
                        📊 Módulos
                    </a>
                    <a href="{{ route('admin.stats.errors') }}" 
                       class="nav-link border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-600">
                        🚨 Errores
                    </a>
                </nav>
            </div>
        </div>

        <!-- Panel de Filtros -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 mb-8 border border-white/20">
            <h3 class="text-lg font-semibold gradient-text mb-4">🔍 Filtros de Exportación</h3>
            <form id="export-filters" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                    <input type="date" id="start_date" name="start_date" 
                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                    <input type="date" id="end_date" name="end_date" 
                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                </div>
                <div>
                    <label for="module_type" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Módulo</label>
                    <select id="module_type" name="module_type" 
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        <option value="">Todos los módulos</option>
                        <option value="chat">Chat</option>
                        <option value="news">Noticias</option>
                        <option value="recommendations">Recomendaciones</option>
                        <option value="employee_management">Gestión de Empleados</option>
                        <option value="analytics">Analytics</option>
                        <option value="admin_panel">Panel Admin</option>
                        <option value="profile">Perfil</option>
                        <option value="dashboard">Dashboard</option>
                    </select>
                </div>
            </form>
            <div class="mt-6 flex space-x-3">
                <button onclick="exportWithFilters('csv')" 
                        class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-full text-white bg-gradient-to-r from-red-500 to-orange-500 hover:from-red-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    📥 Exportar CSV Filtrado
                </button>
                <button onclick="exportWithFilters('excel')" 
                        class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-full text-white bg-gradient-to-r from-orange-500 to-yellow-500 hover:from-orange-600 hover:to-yellow-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    📊 Exportar Excel Filtrado
                </button>
            </div>
        </div>
        <!-- Estadísticas principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-red-100 to-red-200">
                        <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 truncate">Total Módulos</dt>
                            <dd class="text-lg font-bold gradient-text">{{ number_format($totalModules) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-orange-100 to-orange-200">
                        <svg class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 truncate">Logs Totales</dt>
                            <dd class="text-lg font-bold gradient-text">{{ number_format($totalLogs) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-yellow-100 to-yellow-200">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 truncate">Actividad Hoy</dt>
                            <dd class="text-lg font-bold gradient-text">{{ number_format($logsToday) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-red-400">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-red-100 to-red-200">
                        <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 truncate">Usuarios Activos Hoy</dt>
                            <dd class="text-lg font-bold gradient-text">{{ number_format($uniqueUsersToday) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Módulos más utilizados con tema rojo-amarillo -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg mb-8 border border-white/20">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-red-50 to-yellow-50 rounded-t-2xl">
                <h3 class="text-lg font-semibold gradient-text">🏆 Módulos Más Utilizados</h3>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-red-50 to-yellow-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Módulo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Uso Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Usuarios Únicos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tasa de Éxito</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Último Uso</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($moduleUsageStats as $module)
                        <tr class="hover:bg-red-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-lg bg-gradient-to-r from-red-500 to-orange-500 flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ strtoupper(substr($module->type, 0, 2)) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ ucfirst($module->type) }}</div>
                                        <div class="text-sm text-gray-500">Desde {{ \Carbon\Carbon::parse($module->first_used)->format('M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ number_format($module->total_usage) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($module->unique_users) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $module->success_rate >= 95 ? 'bg-green-100 text-green-800' : 
                                       ($module->success_rate >= 85 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ number_format($module->success_rate, 1) }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($module->last_used)->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Análisis de errores con tema rojo-amarillo -->
        @if($moduleErrors->count() > 0)
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg mb-8 border border-white/20">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-red-50 rounded-t-2xl">
                <h3 class="text-lg font-semibold gradient-text">⚠️ Análisis de Errores por Módulo</h3>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-orange-50 to-red-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Módulo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Total Requests</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Errores</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Tasa de Error</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($moduleErrors as $error)
                        <tr class="hover:bg-orange-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ ucfirst($error->type) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($error->total_requests) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($error->error_count) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $error->error_rate <= 5 ? 'bg-green-100 text-green-800' : 
                                       ($error->error_rate <= 15 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ number_format($error->error_rate, 2) }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>

<script>
function exportWithFilters(format) {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const moduleType = document.getElementById('module_type').value;
    
    let url = "{{ route('admin.stats.export') }}";
    const params = new URLSearchParams({
        type: 'modules',
        format: format
    });
    
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    if (moduleType) params.append('module_type', moduleType);
    
    const fullUrl = `${url}?${params.toString()}`;
    window.open(fullUrl, '_blank');
}

// Establecer fecha por defecto (últimos 30 días)
document.addEventListener('DOMContentLoaded', function() {
    const endDate = new Date();
    const startDate = new Date();
    startDate.setDate(startDate.getDate() - 30);
    
    document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
    document.getElementById('start_date').value = startDate.toISOString().split('T')[0];
});
</script>
@endsection