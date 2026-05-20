@extends('layouts.app')

@push('styles')
@include('admin._admin-styles')
@endpush

@section('title', 'Administración de Empleados')

@section('content')
<div class="min-h-screen eia-bg" style="font-family: 'Figtree', system-ui, -apple-system, Segoe UI, sans-serif;">
    {{-- HERO --}}
    <section class="admin-hero px-4 sm:px-8 lg:px-12 py-10">
        <div class="max-w-7xl mx-auto flex items-start justify-between gap-6 flex-wrap">
            <div class="flex items-center gap-4">
                <a href="/" class="admin-back" aria-label="Volver al inicio">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                    </svg>
                </a>
                <div>
                    <span class="admin-eyebrow">Administración · Empleados</span>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Directorio Institucional</h1>
                    <p class="mt-1 text-sm text-slate-300 max-w-2xl">Gestión, búsqueda y mantenimiento del padrón de empleados de la organización.</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.employees.import') }}" class="admin-action">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5-5 5 5M12 5v12"/>
                    </svg>
                    <span>Importar</span>
                </a>
                <a href="{{ route('admin.employees.export', request()->query()) }}" class="admin-action">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 14l5 5 5-5M12 19V7"/>
                    </svg>
                    <span>Exportar</span>
                </a>
                <a href="{{ route('admin.employees.template') }}" class="admin-action">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Plantilla</span>
                </a>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-8 lg:px-12 py-10">
        {{-- KPIs --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
            <div class="admin-kpi red admin-fade admin-d1">
                <span class="accent"></span>
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <div class="admin-kpi-label">Total Empleados</div>
                        <div class="admin-kpi-value">{{ number_format($totalEmployees) }}</div>
                    </div>
                    <div class="admin-kpi-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0zm6 0a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="admin-kpi gold admin-fade admin-d2">
                <span class="accent"></span>
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <div class="admin-kpi-label">Con Acceso</div>
                        <div class="admin-kpi-value">{{ number_format($withAccess) }}</div>
                    </div>
                    <div class="admin-kpi-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="admin-kpi slate admin-fade admin-d3">
                <span class="accent"></span>
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <div class="admin-kpi-label">Sin Acceso</div>
                        <div class="admin-kpi-value">{{ number_format($withoutAccess) }}</div>
                    </div>
                    <div class="admin-kpi-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="admin-kpi black admin-fade admin-d4">
                <span class="accent"></span>
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <div class="admin-kpi-label">Departamentos</div>
                        <div class="admin-kpi-value">{{ $departments->count() }}</div>
                    </div>
                    <div class="admin-kpi-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="admin-panel mb-6 admin-fade">
            <div class="admin-panel-head">
                <div>
                    <div class="admin-panel-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filtros de búsqueda
                    </div>
                    <div class="admin-panel-sub">Combina criterios para localizar empleados específicos en el padrón.</div>
                </div>
            </div>
            <div class="admin-panel-body">
                <form method="GET" action="{{ route('admin.employees.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="search" class="admin-label">Búsqueda</label>
                            <input type="text" class="admin-input" id="search" name="search" value="{{ request('search') }}" placeholder="Nombre, email, ID...">
                        </div>
                        <div>
                            <label for="department" class="admin-label">Departamento</label>
                            <select class="admin-select" id="department" name="department">
                                <option value="">Todos los departamentos</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>
                                        {{ $dept }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="status" class="admin-label">Estado</label>
                            <select class="admin-select" id="status" name="status">
                                <option value="">Todos</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>En licencia</option>
                            </select>
                        </div>
                        <div>
                            <label for="access_filter" class="admin-label">Acceso</label>
                            <select class="admin-select" id="access_filter" name="access_filter">
                                <option value="">Todos</option>
                                <option value="with_access" {{ request('access_filter') === 'with_access' ? 'selected' : '' }}>Con acceso</option>
                                <option value="without_access" {{ request('access_filter') === 'without_access' ? 'selected' : '' }}>Sin acceso</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="admin-btn-primary w-full justify-center">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Filtrar
                            </button>
                        </div>
                    </div>

                    @if(request()->hasAny(['search', 'department', 'status', 'access_filter']))
                        <div class="mt-4 flex">
                            <a href="{{ route('admin.employees.index') }}" class="admin-btn-secondary">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Limpiar filtros
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="admin-panel admin-fade">
            <div class="admin-panel-head">
                <div>
                    <div class="admin-panel-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        Lista de empleados
                        <span class="admin-badge" style="margin-left:8px;">{{ $employees->total() }} resultados</span>
                    </div>
                    <div class="admin-panel-sub">Listado paginado con acciones individuales y selección múltiple.</div>
                </div>

                <div id="bulk-actions" class="hidden items-center gap-3">
                    <span id="selected-count" class="text-xs uppercase tracking-wider text-slate-500 font-semibold"></span>
                    <button type="button" id="bulk-delete-btn" class="admin-btn-danger">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                        </svg>
                        Eliminar seleccionados
                    </button>
                    <button type="button" id="clear-selection-btn" class="admin-btn-secondary">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancelar
                    </button>
                </div>
            </div>

            <div class="admin-panel-body" style="padding:0;">
                @if($employees->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width:42px;">
                                        <input type="checkbox" id="select-all" class="w-4 h-4 rounded border-slate-300" style="accent-color: #0F1419;">
                                    </th>
                                    <th>ID</th>
                                    <th>Empleado</th>
                                    <th>Contacto</th>
                                    <th>Posición</th>
                                    <th>Departamento</th>
                                    <th>Estado</th>
                                    <th>Acceso</th>
                                    <th style="text-align:right;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $employee)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="employee-checkbox w-4 h-4 rounded border-slate-300"
                                                   style="accent-color: #0F1419;"
                                                   value="{{ $employee->id }}"
                                                   data-name="{{ $employee->full_name }}">
                                        </td>
                                        <td>
                                            <span style="font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size:12px; color: var(--eia-black); background:#F1F5F9; padding:3px 8px; border-radius:6px; border:1px solid var(--eia-border);">
                                                {{ $employee->employee_id }}
                                            </span>
                                        </td>
                                        <td class="primary">
                                            <div class="flex items-center gap-3">
                                                <div style="width:36px; height:36px; border-radius:10px; background:#0F1419; color:#F8FAFC; display:inline-flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; letter-spacing:0.04em; border:1px solid #0F1419;">
                                                    {{ strtoupper(substr($employee->first_name, 0, 1)) }}{{ strtoupper(substr($employee->last_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div style="color: var(--eia-black); font-weight:600; font-size:13.5px;">{{ $employee->full_name }}</div>
                                                    @if($employee->phone)
                                                        <div style="color: var(--eia-mute); font-size:11.5px; margin-top:2px; display:inline-flex; align-items:center; gap:4px;">
                                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                            </svg>
                                                            {{ $employee->phone }}
                                                            @if($employee->extension)
                                                                <span style="color: var(--eia-mute);">Ext. {{ $employee->extension }}</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="color: var(--eia-black); font-size:13px;">{{ $employee->email }}</div>
                                            @if($employee->location)
                                                <div style="color: var(--eia-mute); font-size:11.5px; margin-top:2px; display:inline-flex; align-items:center; gap:4px;">
                                                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    {{ $employee->location }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="primary">{{ $employee->position }}</td>
                                        <td>
                                            <span class="admin-badge">{{ $employee->department }}</span>
                                        </td>
                                        <td>
                                            @switch($employee->status)
                                                @case('active')
                                                    <span class="admin-badge green">Activo</span>
                                                    @break
                                                @case('inactive')
                                                    <span class="admin-badge red">Inactivo</span>
                                                    @break
                                                @case('on_leave')
                                                    <span class="admin-badge gold">En licencia</span>
                                                    @break
                                                @default
                                                    <span class="admin-badge">{{ $employee->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($employee->hasSystemAccess())
                                                <span class="admin-badge green">Sí</span>
                                            @else
                                                <span class="admin-badge">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="flex items-center gap-2 justify-end">
                                                <a href="{{ route('admin.employees.show', $employee) }}"
                                                   title="Ver detalles"
                                                   style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid var(--eia-border); color: var(--eia-black); background:#FFFFFF; transition: all .2s ease;"
                                                   onmouseover="this.style.background='#0F1419';this.style.color='#FFFFFF';this.style.borderColor='#0F1419';"
                                                   onmouseout="this.style.background='#FFFFFF';this.style.color='#0F1419';this.style.borderColor='#E5E7EB';">
                                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                                <a href="mailto:{{ $employee->email }}"
                                                   title="Enviar email"
                                                   style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid #FDE68A; color:#92400E; background:#FFFBEB; transition: all .2s ease;"
                                                   onmouseover="this.style.background='#FEF3C7';"
                                                   onmouseout="this.style.background='#FFFBEB';">
                                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                </a>
                                                <button type="button"
                                                        class="delete-employee-btn"
                                                        title="Eliminar empleado"
                                                        data-employee-id="{{ $employee->id }}"
                                                        data-employee-name="{{ $employee->full_name }}"
                                                        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid #FECACA; color: var(--eia-red); background:#FFFFFF; transition: all .2s ease; cursor:pointer;"
                                                        onmouseover="this.style.background='#B91C1C';this.style.color='#FFFFFF';this.style.borderColor='#B91C1C';"
                                                        onmouseout="this.style.background='#FFFFFF';this.style.color='#B91C1C';this.style.borderColor='#FECACA';">
                                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4" style="border-top: 1px solid var(--eia-border);">
                        <div style="font-size:12px; color: var(--eia-mute);">
                            Mostrando {{ $employees->firstItem() }} a {{ $employees->lastItem() }} de {{ $employees->total() }} resultados
                        </div>
                        <div>
                            {{ $employees->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-14 px-6">
                        <div style="width:64px; height:64px; border-radius:14px; background:#F1F5F9; border:1px solid var(--eia-border); display:inline-flex; align-items:center; justify-content:center; color: var(--eia-slate);">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 11-8 0 4 4 0 018 0zm6 0a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 style="margin-top:14px; font-size:16px; font-weight:600; color: var(--eia-black);">No se encontraron empleados</h3>
                        <p style="margin-top:6px; font-size:13px; color: var(--eia-mute); max-width:480px; margin-left:auto; margin-right:auto;">
                            @if(request()->hasAny(['search', 'department', 'status', 'access_filter']))
                                Intenta ajustar los filtros de búsqueda o elimina algunos criterios.
                            @else
                                Comienza importando empleados desde un archivo CSV o Excel.
                            @endif
                        </p>
                        <div class="mt-5">
                            <a href="{{ route('admin.employees.import') }}" class="admin-btn-primary">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5-5 5 5M12 5v12"/>
                                </svg>
                                Importar empleados
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal eliminación individual --}}
<div id="delete-modal" tabindex="-1" class="hidden fixed inset-0 z-50 items-center justify-center" style="background: rgba(15, 20, 25, 0.55);">
    <div class="relative p-4 w-full max-w-md mx-auto" style="margin-top: 12vh;">
        <div style="background: var(--eia-surface); border-radius: 14px; border: 1px solid var(--eia-border); box-shadow: 0 30px 60px -20px rgba(15,20,25,0.4); overflow:hidden;">
            <div style="padding: 22px 24px; border-bottom: 1px solid var(--eia-border); display:flex; align-items:center; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:38px; height:38px; border-radius:10px; background:#FEF2F2; color: var(--eia-red); border:1px solid #FECACA; display:inline-flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M4.93 4.93l14.14 14.14M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="admin-panel-title">Confirmar eliminación</div>
                        <div class="admin-panel-sub">Esta acción no se puede deshacer.</div>
                    </div>
                </div>
                <button type="button" data-modal-hide="delete-modal" style="color: var(--eia-mute); background: transparent; border:none; cursor:pointer;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div style="padding: 22px 24px;">
                <p style="font-size:13.5px; color: var(--eia-slate);">¿Estás seguro de que quieres eliminar a <span id="delete-employee-name" style="font-weight:600; color: var(--eia-black);"></span>?</p>
            </div>
            <div style="padding: 16px 24px; border-top: 1px solid var(--eia-border); display:flex; justify-content:flex-end; gap:8px; background:#FAFAFB;">
                <button data-modal-hide="delete-modal" type="button" class="admin-btn-secondary">Cancelar</button>
                <button id="confirm-delete-btn" type="button" class="admin-btn-danger" style="background: var(--eia-red); color:#FFFFFF; border-color: var(--eia-red);">
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal eliminación múltiple --}}
<div id="bulk-delete-modal" tabindex="-1" class="hidden fixed inset-0 z-50 items-center justify-center" style="background: rgba(15, 20, 25, 0.55);">
    <div class="relative p-4 w-full max-w-md mx-auto" style="margin-top: 12vh;">
        <div style="background: var(--eia-surface); border-radius: 14px; border: 1px solid var(--eia-border); box-shadow: 0 30px 60px -20px rgba(15,20,25,0.4); overflow:hidden;">
            <div style="padding: 22px 24px; border-bottom: 1px solid var(--eia-border); display:flex; align-items:center; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:38px; height:38px; border-radius:10px; background:#FEF2F2; color: var(--eia-red); border:1px solid #FECACA; display:inline-flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M4.93 4.93l14.14 14.14M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="admin-panel-title">Eliminación múltiple</div>
                        <div class="admin-panel-sub">Operación masiva irreversible.</div>
                    </div>
                </div>
                <button type="button" data-modal-hide="bulk-delete-modal" style="color: var(--eia-mute); background: transparent; border:none; cursor:pointer;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div style="padding: 22px 24px;">
                <p style="font-size:13.5px; color: var(--eia-slate);">¿Estás seguro de que quieres eliminar <span id="bulk-delete-count" style="font-weight:600; color: var(--eia-black);"></span> empleado(s) seleccionado(s)?</p>
                <p style="font-size:12px; color: var(--eia-mute); margin-top:6px;">Esta acción no se puede deshacer.</p>
            </div>
            <div style="padding: 16px 24px; border-top: 1px solid var(--eia-border); display:flex; justify-content:flex-end; gap:8px; background:#FAFAFB;">
                <button data-modal-hide="bulk-delete-modal" type="button" class="admin-btn-secondary">Cancelar</button>
                <button id="confirm-bulk-delete-btn" type="button" class="admin-btn-danger" style="background: var(--eia-red); color:#FFFFFF; border-color: var(--eia-red);">
                    Sí, eliminar todos
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Asegurar flex en modales mostrados */
    #delete-modal:not(.hidden),
    #bulk-delete-modal:not(.hidden) { display: flex !important; }
    #bulk-actions:not(.hidden) { display: inline-flex !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const clearSelectionBtn = document.getElementById('clear-selection-btn');

    let currentEmployeeId = null;
    let selectedEmployees = [];

    function updateSelectionState() {
        const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
        const count = checkedBoxes.length;

        if (count > 0) {
            bulkActions.classList.remove('hidden');
            selectedCount.textContent = `${count} empleado(s) seleccionado(s)`;
            selectedEmployees = Array.from(checkedBoxes).map(cb => cb.value);
        } else {
            bulkActions.classList.add('hidden');
            selectedEmployees = [];
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.indeterminate = count > 0 && count < employeeCheckboxes.length;
            selectAllCheckbox.checked = count === employeeCheckboxes.length && employeeCheckboxes.length > 0;
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            employeeCheckboxes.forEach(checkbox => { checkbox.checked = isChecked; });
            updateSelectionState();
        });
    }

    employeeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionState);
    });

    if (clearSelectionBtn) {
        clearSelectionBtn.addEventListener('click', function() {
            employeeCheckboxes.forEach(checkbox => { checkbox.checked = false; });
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            updateSelectionState();
        });
    }

    document.querySelectorAll('.delete-employee-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            currentEmployeeId = this.dataset.employeeId;
            const employeeName = this.dataset.employeeName;
            document.getElementById('delete-employee-name').textContent = employeeName;
            const modal = document.getElementById('delete-modal');
            modal.classList.remove('hidden');
        });
    });

    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (!currentEmployeeId) return;

            this.innerHTML = '<span class="admin-spinner" style="width:14px; height:14px; border-width:2px;"></span> Eliminando...';
            this.disabled = true;

            fetch(`/admin/employees/${currentEmployeeId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message || 'Error al eliminar el empleado');
                    this.innerHTML = 'Sí, eliminar';
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar el empleado');
                this.innerHTML = 'Sí, eliminar';
                this.disabled = false;
            });
        });
    }

    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            if (selectedEmployees.length === 0) return;
            document.getElementById('bulk-delete-count').textContent = selectedEmployees.length;
            document.getElementById('bulk-delete-modal').classList.remove('hidden');
        });
    }

    const confirmBulkDeleteBtn = document.getElementById('confirm-bulk-delete-btn');
    if (confirmBulkDeleteBtn) {
        confirmBulkDeleteBtn.addEventListener('click', function() {
            if (selectedEmployees.length === 0) return;

            this.innerHTML = '<span class="admin-spinner" style="width:14px; height:14px; border-width:2px;"></span> Eliminando...';
            this.disabled = true;

            fetch('/admin/employees/bulk/delete', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ employee_ids: selectedEmployees })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message || 'Error al eliminar los empleados');
                    this.innerHTML = 'Sí, eliminar todos';
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar los empleados');
                this.innerHTML = 'Sí, eliminar todos';
                this.disabled = false;
            });
        });
    }

    document.querySelectorAll('[data-modal-hide]').forEach(btn => {
        btn.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-hide');
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.add('hidden');
        });
    });
});
</script>
@endsection
