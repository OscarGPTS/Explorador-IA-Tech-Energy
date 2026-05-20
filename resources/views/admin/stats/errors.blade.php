@extends('layouts.app')

@push('styles')
@include('admin._admin-styles')
<style>
    .error-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 20, 25, 0.55);
        z-index: 50;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .error-modal-backdrop.is-open { display: flex; }
    .error-modal-card {
        background: #FFFFFF;
        border: 1px solid var(--eia-border);
        border-radius: 14px;
        width: 100%;
        max-width: 920px;
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 30px 60px -20px rgba(15, 20, 25, 0.4);
    }
    .error-modal-head {
        padding: 18px 22px;
        border-bottom: 1px solid var(--eia-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .error-modal-body {
        padding: 20px 22px;
        overflow-y: auto;
        flex: 1;
    }
    .error-modal-foot {
        padding: 14px 22px;
        border-top: 1px solid var(--eia-border);
        background: #FAFAFB;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    .error-modal-close {
        width: 32px; height: 32px;
        border-radius: 8px;
        border: 1px solid var(--eia-border);
        background: #FFFFFF;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--eia-slate);
        cursor: pointer;
        transition: all .2s ease;
    }
    .error-modal-close:hover { color: var(--eia-black); border-color: var(--eia-black); }
    .detail-card {
        background: #FAFAFB;
        border: 1px solid var(--eia-border);
        border-radius: 10px;
        padding: 14px 16px;
    }
    .detail-card.danger {
        background: #FEF2F2;
        border-color: #FECACA;
    }
    .detail-card h4 {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--eia-mute);
        margin-bottom: 10px;
    }
    .detail-card.danger h4 { color: #92400E; }
    .detail-list {
        font-size: 13px;
        color: var(--eia-slate);
        display: grid;
        gap: 6px;
    }
    .detail-list strong { color: var(--eia-black); font-weight: 600; }
    .code-block {
        background: #0F1419;
        color: #E2E8F0;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 12px;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        overflow-x: auto;
        max-height: 280px;
        overflow-y: auto;
    }
    .empty-state {
        padding: 56px 20px;
        text-align: center;
        color: var(--eia-mute);
    }
    .empty-icon {
        width: 56px; height: 56px;
        border-radius: 50%;
        background: #ECFDF5;
        color: #047857;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        border: 1px solid #A7F3D0;
    }
    .pagination-wrap {
        padding: 16px 20px;
        border-top: 1px solid var(--eia-border);
        background: #FAFAFB;
    }
    .pagination-wrap a,
    .pagination-wrap span {
        font-size: 13px;
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
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Monitoreo de Errores</h1>
                    <p class="mt-1 text-sm text-slate-300 max-w-2xl">Análisis detallado de errores y fallos del sistema con información completa de request y response.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.stats.export', ['type' => 'modules', 'format' => 'csv', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="admin-action">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                    </svg>
                    CSV
                </a>
                <a href="{{ route('admin.stats.export', ['type' => 'modules', 'format' => 'excel', 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="admin-action">
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

            <!-- Filtros -->
            <div class="admin-panel mb-8 admin-fade admin-d2">
                <div class="admin-panel-head">
                    <div>
                        <div class="admin-panel-title">Filtros</div>
                        <div class="admin-panel-sub">Filtra errores por fecha, módulo o código de estado.</div>
                    </div>
                </div>
                <div class="admin-panel-body">
                    <form method="GET" action="{{ route('admin.stats.errors') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="start_date" class="admin-label">Fecha Inicio</label>
                            <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="admin-input">
                        </div>
                        <div>
                            <label for="end_date" class="admin-label">Fecha Fin</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="admin-input">
                        </div>
                        <div>
                            <label for="module_type" class="admin-label">Tipo de Módulo</label>
                            <select id="module_type" name="module_type" class="admin-select">
                                <option value="">Todos los módulos</option>
                                @foreach($availableModules as $module)
                                    <option value="{{ $module }}" {{ $moduleType == $module ? 'selected' : '' }}>
                                        {{ ucfirst($module) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="status_code" class="admin-label">Código de Estado</label>
                            <select id="status_code" name="status_code" class="admin-select">
                                <option value="">Todos los códigos</option>
                                @foreach($availableStatusCodes as $code)
                                    <option value="{{ $code }}" {{ $statusCode == $code ? 'selected' : '' }}>
                                        {{ $code }} - {{ $code >= 500 ? 'Error Servidor' : ($code >= 400 ? 'Error Cliente' : 'OK') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2 lg:col-span-4 flex flex-wrap gap-3 pt-2">
                            <button type="submit" class="admin-btn-primary">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="7"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/>
                                </svg>
                                Filtrar errores
                            </button>
                            <a href="{{ route('admin.stats.errors') }}" class="admin-btn-secondary">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2"/>
                                </svg>
                                Limpiar filtros
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 admin-fade admin-d2">
                <div class="admin-kpi red">
                    <span class="accent"></span>
                    <div class="flex items-center gap-4">
                        <div class="admin-kpi-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.268 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="admin-kpi-label">Total Errores</div>
                            <div class="admin-kpi-value">{{ number_format($errorStats['total_errors']) }}</div>
                        </div>
                    </div>
                </div>

                <div class="admin-kpi gold">
                    <span class="accent"></span>
                    <div class="flex items-center gap-4">
                        <div class="admin-kpi-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="admin-kpi-label">Errores Hoy</div>
                            <div class="admin-kpi-value">{{ number_format($errorStats['errors_today']) }}</div>
                        </div>
                    </div>
                </div>

                <div class="admin-kpi black">
                    <span class="accent"></span>
                    <div class="flex items-center gap-4">
                        <div class="admin-kpi-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="admin-kpi-label">Error Más Común</div>
                            <div class="admin-kpi-value">{{ $errorStats['most_common_error']->status_code ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <div class="admin-kpi slate">
                    <span class="accent"></span>
                    <div class="flex items-center gap-4">
                        <div class="admin-kpi-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="admin-kpi-label">Módulos Afectados</div>
                            <div class="admin-kpi-value">{{ count($errorStats['errors_by_module']) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de errores -->
            <div class="admin-panel admin-fade admin-d3">
                <div class="admin-panel-head">
                    <div>
                        <div class="admin-panel-title">Lista de errores</div>
                        <div class="admin-panel-sub">{{ $errors->total() }} registros encontrados.</div>
                    </div>
                </div>

                @if($errors->count() > 0)
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Fecha / Hora</th>
                                <th>Módulo</th>
                                <th>Usuario</th>
                                <th>Error</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($errors as $error)
                            <tr>
                                <td>
                                    <div class="text-sm text-slate-900">{{ $error->created_at->format('Y-m-d H:i:s') }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $error->created_at->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <span class="admin-badge red">{{ ucfirst($error->type) }}</span>
                                </td>
                                <td>
                                    @if($error->user)
                                        <div class="text-sm text-slate-900">{{ $error->user->name }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">{{ $error->user->email }}</div>
                                    @else
                                        <span class="text-slate-400 text-sm">Usuario eliminado</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="max-w-xs">
                                        <div class="text-sm text-slate-900">{{ Str::limit($error->message, 100) }}</div>
                                        @if($error->error_details)
                                            <div class="text-xs mt-1" style="color: var(--eia-red);">
                                                {{ json_decode($error->error_details, true)['exception_class'] ?? 'Error desconocido' }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="admin-badge {{ $error->status_code >= 500 ? 'red' : ($error->status_code >= 400 ? 'gold' : 'green') }}">
                                        {{ $error->status_code }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" onclick='showErrorDetails(@json($error))' class="admin-btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="11" cy="11" r="7"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/>
                                        </svg>
                                        Ver detalles
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="pagination-wrap">
                    {{ $errors->appends(request()->query())->links() }}
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-sm font-semibold text-slate-900">No se encontraron errores</div>
                    <div class="text-xs text-slate-500 mt-1">No hay errores en el rango de fechas seleccionado.</div>
                </div>
                @endif
            </div>

        </div>
    </section>
</div>

<!-- Modal de detalles del error -->
<div id="errorModal" class="error-modal-backdrop" role="dialog" aria-modal="true">
    <div class="error-modal-card">
        <div class="error-modal-head">
            <div>
                <div class="admin-panel-title" style="color: var(--eia-red);">Detalles del error</div>
                <div class="admin-panel-sub">Información completa del request, response y stack trace.</div>
            </div>
            <button type="button" onclick="closeErrorModal()" class="error-modal-close" aria-label="Cerrar">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="error-modal-body">
            <div id="errorDetails" class="space-y-5"></div>
        </div>
        <div class="error-modal-foot">
            <button type="button" onclick="closeErrorModal()" class="admin-btn-primary">Cerrar</button>
        </div>
    </div>
</div>

<script>
function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function showErrorDetails(error) {
    const modal = document.getElementById('errorModal');
    const detailsDiv = document.getElementById('errorDetails');

    let errorDetailsObj = null;
    try {
        errorDetailsObj = typeof error.error_details === 'string' ? JSON.parse(error.error_details) : error.error_details;
    } catch (e) {
        errorDetailsObj = null;
    }

    let requestData = null;
    try {
        requestData = typeof error.request_data === 'string' ? JSON.parse(error.request_data) : error.request_data;
    } catch (e) {
        requestData = null;
    }

    let responseData = null;
    try {
        responseData = typeof error.response_data === 'string' ? JSON.parse(error.response_data) : error.response_data;
    } catch (e) {
        responseData = null;
    }

    detailsDiv.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="detail-card">
                <h4>Información general</h4>
                <div class="detail-list">
                    <div><strong>Fecha:</strong> ${escapeHtml(error.created_at)}</div>
                    <div><strong>Módulo:</strong> ${escapeHtml(error.type)}</div>
                    <div><strong>Usuario:</strong> ${error.user ? escapeHtml(error.user.name + ' (' + error.user.email + ')') : 'Usuario eliminado'}</div>
                    <div><strong>Método:</strong> ${escapeHtml(error.method || 'N/A')}</div>
                    <div><strong>URL:</strong> ${escapeHtml(error.url || 'N/A')}</div>
                    <div><strong>IP:</strong> ${escapeHtml(error.ip_address || 'N/A')}</div>
                    <div><strong>Tiempo de respuesta:</strong> ${escapeHtml(error.response_time || 'N/A')}ms</div>
                </div>
            </div>

            <div class="detail-card danger">
                <h4>Detalles del error</h4>
                <div class="detail-list">
                    <div><strong>Código:</strong> ${escapeHtml(error.status_code)}</div>
                    <div><strong>Mensaje:</strong> ${escapeHtml(error.message)}</div>
                    ${errorDetailsObj ? `
                        <div><strong>Excepción:</strong> ${escapeHtml(errorDetailsObj.exception_class || 'N/A')}</div>
                        <div><strong>Archivo:</strong> ${escapeHtml(errorDetailsObj.file || 'N/A')}</div>
                        <div><strong>Línea:</strong> ${escapeHtml(errorDetailsObj.line || 'N/A')}</div>
                    ` : ''}
                </div>
            </div>
        </div>

        ${requestData ? `
            <div>
                <h4 style="font-size: 11px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--eia-mute); margin-bottom: 8px;">Datos del request</h4>
                <div class="code-block"><pre>${escapeHtml(JSON.stringify(requestData, null, 2))}</pre></div>
            </div>
        ` : ''}

        ${responseData ? `
            <div>
                <h4 style="font-size: 11px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--eia-mute); margin-bottom: 8px;">Datos del response</h4>
                <div class="code-block"><pre>${escapeHtml(JSON.stringify(responseData, null, 2))}</pre></div>
            </div>
        ` : ''}

        ${error.stack_trace ? `
            <div>
                <h4 style="font-size: 11px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--eia-mute); margin-bottom: 8px;">Stack trace</h4>
                <div class="code-block"><pre>${escapeHtml(error.stack_trace)}</pre></div>
            </div>
        ` : ''}
    `;

    modal.classList.add('is-open');
}

function closeErrorModal() {
    document.getElementById('errorModal').classList.remove('is-open');
}

// Cerrar modal al hacer clic fuera
document.getElementById('errorModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeErrorModal();
    }
});

// Cerrar modal con la tecla Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeErrorModal();
    }
});
</script>
@endsection
