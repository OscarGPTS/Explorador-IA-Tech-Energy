@extends('layouts.app')

@push('styles')
@include('admin._admin-styles')
<style>
    .module-cell {
        display: inline-flex;
        align-items: center;
        gap: 12px;
    }
    .module-tag {
        width: 38px; height: 38px;
        border-radius: 10px;
        background: var(--eia-black);
        color: #F8FAFC;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 12px;
        letter-spacing: 0.05em;
        border: 1px solid var(--eia-black);
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen eia-bg">
    <!-- HERO -->
    <section class="admin-hero px-4 sm:px-8 lg:px-12 py-10">
        <div class="max-w-7xl mx-auto flex items-start justify-between gap-6 flex-wrap">
            <div class="flex items-center gap-4">
                <a href="/" class="admin-back" aria-label="Volver al inicio">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                    </svg>
                </a>
                <div>
                    <span class="admin-eyebrow">Administración · Estadísticas</span>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Estadísticas de Módulos</h1>
                    <p class="mt-1 text-sm text-slate-300 max-w-2xl">Análisis detallado del uso de los diferentes módulos y aplicaciones del sistema.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.stats.export', ['type' => 'modules', 'format' => 'csv']) }}" class="admin-action">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                    </svg>
                    CSV
                </a>
                <a href="{{ route('admin.stats.export', ['type' => 'modules', 'format' => 'excel']) }}" class="admin-action">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6h6v6M9 7h6M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Excel
                </a>
            </div>
        </div>
    </section>

    <!-- TABS -->
    <section class="px-4 sm:px-8 lg:px-12 pt-7">
        <div class="max-w-7xl mx-auto">
            <nav class="admin-tabs admin-fade admin-d1">
                <a href="{{ route('admin.stats.dashboard') }}" class="admin-tab {{ request()->routeIs('admin.stats.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.stats.users') }}" class="admin-tab {{ request()->routeIs('admin.stats.users') ? 'active' : '' }}">Usuarios</a>
                <a href="{{ route('admin.stats.chats') }}" class="admin-tab {{ request()->routeIs('admin.stats.chats') ? 'active' : '' }}">Chats</a>
                <a href="{{ route('admin.stats.modules') }}" class="admin-tab {{ request()->routeIs('admin.stats.modules') ? 'active' : '' }}">Módulos</a>
                <a href="{{ route('admin.stats.errors') }}" class="admin-tab {{ request()->routeIs('admin.stats.errors') ? 'active' : '' }}">Errores</a>
            </nav>
        </div>
    </section>

    <!-- CONTENT -->
    <section class="px-4 sm:px-8 lg:px-12 py-8">
        <div class="max-w-7xl mx-auto">

            <!-- Filtros de exportación -->
            <div class="admin-panel mb-8 admin-fade admin-d2">
                <div class="admin-panel-head">
                    <div>
                        <div class="admin-panel-title">Filtros de exportación</div>
                        <div class="admin-panel-sub">Acota el reporte por fechas y tipo de módulo.</div>
                    </div>
                </div>
                <div class="admin-panel-body">
                    <form id="export-filters" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="start_date" class="admin-label">Fecha Inicio</label>
                            <input type="date" id="start_date" name="start_date" class="admin-input">
                        </div>
                        <div>
                            <label for="end_date" class="admin-label">Fecha Fin</label>
                            <input type="date" id="end_date" name="end_date" class="admin-input">
                        </div>
                        <div>
                            <label for="module_type" class="admin-label">Tipo de Módulo</label>
                            <select id="module_type" name="module_type" class="admin-select">
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
                    <div class="mt-5 flex flex-wrap gap-3">
                        <button type="button" onclick="exportWithFilters('csv')" class="admin-btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                            </svg>
                            Exportar CSV filtrado
                        </button>
                        <button type="button" onclick="exportWithFilters('excel')" class="admin-btn-secondary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6h6v6M9 7h6M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Exportar Excel filtrado
                        </button>
                    </div>
                </div>
            </div>

            <!-- KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 admin-fade admin-d2">
                <div class="admin-kpi red">
                    <span class="accent"></span>
                    <div class="flex items-center gap-4">
                        <div class="admin-kpi-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="admin-kpi-label">Total Módulos</div>
                            <div class="admin-kpi-value">{{ number_format($totalModules) }}</div>
                        </div>
                    </div>
                </div>

                <div class="admin-kpi gold">
                    <span class="accent"></span>
                    <div class="flex items-center gap-4">
                        <div class="admin-kpi-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="admin-kpi-label">Logs Totales</div>
                            <div class="admin-kpi-value">{{ number_format($totalLogs) }}</div>
                        </div>
                    </div>
                </div>

                <div class="admin-kpi black">
                    <span class="accent"></span>
                    <div class="flex items-center gap-4">
                        <div class="admin-kpi-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="admin-kpi-label">Actividad Hoy</div>
                            <div class="admin-kpi-value">{{ number_format($logsToday) }}</div>
                        </div>
                    </div>
                </div>

                <div class="admin-kpi slate">
                    <span class="accent"></span>
                    <div class="flex items-center gap-4">
                        <div class="admin-kpi-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="admin-kpi-label">Usuarios Activos Hoy</div>
                            <div class="admin-kpi-value">{{ number_format($uniqueUsersToday) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Módulos más utilizados -->
            <div class="admin-panel mb-8 admin-fade admin-d3">
                <div class="admin-panel-head">
                    <div>
                        <div class="admin-panel-title">Módulos más utilizados</div>
                        <div class="admin-panel-sub">Ranking por uso total, usuarios únicos y tasa de éxito.</div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Módulo</th>
                                <th>Uso Total</th>
                                <th>Usuarios Únicos</th>
                                <th>Tasa de Éxito</th>
                                <th>Último Uso</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($moduleUsageStats as $module)
                            <tr>
                                <td class="primary">
                                    <div class="module-cell">
                                        <div class="module-tag">{{ strtoupper(substr($module->type, 0, 2)) }}</div>
                                        <div>
                                            <div class="text-sm font-semibold text-slate-900">{{ ucfirst($module->type) }}</div>
                                            <div class="text-xs text-slate-500 mt-0.5">Desde {{ \Carbon\Carbon::parse($module->first_used)->format('M Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="admin-badge red">{{ number_format($module->total_usage) }}</span></td>
                                <td>{{ number_format($module->unique_users) }}</td>
                                <td>
                                    <span class="admin-badge {{ $module->success_rate >= 95 ? 'green' : ($module->success_rate >= 85 ? 'gold' : 'red') }}">
                                        {{ number_format($module->success_rate, 1) }}%
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($module->last_used)->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Análisis de errores por módulo -->
            @if($moduleErrors->count() > 0)
            <div class="admin-panel admin-fade admin-d4">
                <div class="admin-panel-head">
                    <div>
                        <div class="admin-panel-title">Análisis de errores por módulo</div>
                        <div class="admin-panel-sub">Requests, errores absolutos y tasa de error por módulo.</div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Módulo</th>
                                <th>Total Requests</th>
                                <th>Errores</th>
                                <th>Tasa de Error</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($moduleErrors as $error)
                            <tr>
                                <td class="primary">{{ ucfirst($error->type) }}</td>
                                <td>{{ number_format($error->total_requests) }}</td>
                                <td>{{ number_format($error->error_count) }}</td>
                                <td>
                                    <span class="admin-badge {{ $error->error_rate <= 5 ? 'green' : ($error->error_rate <= 15 ? 'gold' : 'red') }}">
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
    </section>
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
