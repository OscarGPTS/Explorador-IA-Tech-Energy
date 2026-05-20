@extends('layouts.app')

@push('styles')
@include('admin._admin-styles')
@endpush

@section('title', 'Detalles del Empleado')

@section('content')
<div class="min-h-screen eia-bg" style="font-family: 'Figtree', system-ui, -apple-system, Segoe UI, sans-serif;">
    {{-- HERO --}}
    <section class="admin-hero px-4 sm:px-8 lg:px-12 py-10">
        <div class="max-w-7xl mx-auto flex items-start justify-between gap-6 flex-wrap">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.employees.index') }}" class="admin-back" aria-label="Volver al listado">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                    </svg>
                </a>
                <div>
                    <span class="admin-eyebrow">Administración · Empleado</span>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">{{ $employee->full_name }}</h1>
                    <p class="mt-1 text-sm text-slate-300 max-w-2xl">{{ $employee->position }} · {{ $employee->department }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="mailto:{{ $employee->email }}" class="admin-action">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Enviar email</span>
                </a>
                @if($employee->phone)
                    <a href="tel:{{ $employee->phone }}" class="admin-action">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>Llamar</span>
                    </a>
                @endif
                <a href="{{ route('admin.employees.export', ['search' => $employee->email]) }}" class="admin-action">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 14l5 5 5-5M12 19V7"/>
                    </svg>
                    <span>Exportar</span>
                </a>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-8 lg:px-12 py-10">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            {{-- Columna principal --}}
            <div class="xl:col-span-2 space-y-6">
                {{-- Información personal --}}
                <div class="admin-panel admin-fade admin-d1">
                    <div class="admin-panel-head">
                        <div>
                            <div class="admin-panel-title">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Información personal
                            </div>
                            <div class="admin-panel-sub">Datos de identificación y contacto.</div>
                        </div>
                    </div>
                    <div class="admin-panel-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <span class="admin-label">ID de empleado</span>
                                <div style="font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size:13px; color: var(--eia-black); background:#F1F5F9; padding:9px 12px; border-radius:8px; border:1px solid var(--eia-border); display:inline-block;">
                                    {{ $employee->employee_id }}
                                </div>
                            </div>
                            <div>
                                <span class="admin-label">Nombre completo</span>
                                <div style="font-size:14px; color: var(--eia-black); font-weight:600;">{{ $employee->full_name }}</div>
                            </div>
                            <div>
                                <span class="admin-label">Email</span>
                                <a href="mailto:{{ $employee->email }}" style="font-size:13.5px; color: var(--eia-black); display:inline-flex; align-items:center; gap:6px; text-decoration:none; border-bottom:1px solid var(--eia-gold); padding-bottom:1px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $employee->email }}
                                </a>
                            </div>
                            <div>
                                <span class="admin-label">Teléfono</span>
                                @if($employee->phone)
                                    <a href="tel:{{ $employee->phone }}" style="font-size:13.5px; color: var(--eia-black); display:inline-flex; align-items:center; gap:6px; text-decoration:none;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        {{ $employee->phone }}
                                        @if($employee->extension)
                                            <span style="color: var(--eia-mute); font-size:12px;">· Ext. {{ $employee->extension }}</span>
                                        @endif
                                    </a>
                                @else
                                    <span style="font-size:13px; color: var(--eia-mute);">No especificado</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Información laboral --}}
                <div class="admin-panel admin-fade admin-d2">
                    <div class="admin-panel-head">
                        <div>
                            <div class="admin-panel-title">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 8h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Información laboral
                            </div>
                            <div class="admin-panel-sub">Posición, asignación y vínculos jerárquicos.</div>
                        </div>
                    </div>
                    <div class="admin-panel-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <span class="admin-label">Posición</span>
                                <div style="font-size:14px; color: var(--eia-black); font-weight:600;">{{ $employee->position }}</div>
                            </div>
                            <div>
                                <span class="admin-label">Departamento</span>
                                <div><span class="admin-badge">{{ $employee->department }}</span></div>
                            </div>
                            <div>
                                <span class="admin-label">Ubicación</span>
                                @if($employee->location)
                                    <div style="font-size:13.5px; color: var(--eia-black); display:inline-flex; align-items:center; gap:6px;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $employee->location }}
                                    </div>
                                @else
                                    <span style="font-size:13px; color: var(--eia-mute);">No especificada</span>
                                @endif
                            </div>
                            <div>
                                <span class="admin-label">Supervisor</span>
                                @if($employee->manager_email)
                                    <a href="mailto:{{ $employee->manager_email }}" style="font-size:13.5px; color: var(--eia-black); display:inline-flex; align-items:center; gap:6px; text-decoration:none;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $employee->manager_email }}
                                    </a>
                                @else
                                    <span style="font-size:13px; color: var(--eia-mute);">No asignado</span>
                                @endif
                            </div>
                            <div>
                                <span class="admin-label">Fecha de contratación</span>
                                @if($employee->hire_date)
                                    <div style="font-size:13.5px; color: var(--eia-black); display:inline-flex; align-items:center; gap:6px;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $employee->hire_date->format('d/m/Y') }}
                                    </div>
                                    <span style="font-size:11.5px; color: var(--eia-mute); margin-top:4px; display:inline-block;">{{ $employee->hire_date->diffForHumans() }}</span>
                                @else
                                    <span style="font-size:13px; color: var(--eia-mute);">No especificada</span>
                                @endif
                            </div>
                            <div>
                                <span class="admin-label">Estado</span>
                                <div>
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
                                </div>
                            </div>
                        </div>

                        @if($employee->notes)
                            <div style="margin-top: 22px;">
                                <span class="admin-label">Notas</span>
                                <div style="background:#FAFAFB; border:1px solid var(--eia-border); border-left:3px solid var(--eia-gold); border-radius:10px; padding:14px 16px; font-size:13.5px; color: var(--eia-slate); line-height:1.6;">
                                    {{ $employee->notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Información de registro --}}
                <div class="admin-panel admin-fade admin-d3">
                    <div class="admin-panel-head">
                        <div>
                            <div class="admin-panel-title">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Información de registro
                            </div>
                            <div class="admin-panel-sub">Trazabilidad de origen y sincronización.</div>
                        </div>
                    </div>
                    <div class="admin-panel-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <span class="admin-label">Fecha de importación</span>
                                @if($employee->data_imported_at)
                                    <div style="font-size:13.5px; color: var(--eia-black);">{{ $employee->data_imported_at->format('d/m/Y H:i') }}</div>
                                    <span style="font-size:11.5px; color: var(--eia-mute); margin-top:2px; display:inline-block;">{{ $employee->data_imported_at->diffForHumans() }}</span>
                                @else
                                    <span style="font-size:13px; color: var(--eia-mute);">No especificada</span>
                                @endif
                            </div>
                            <div>
                                <span class="admin-label">Fuente de importación</span>
                                <div>
                                    @switch($employee->import_source)
                                        @case('manual_seed')
                                            <span class="admin-badge">Datos de prueba</span>
                                            @break
                                        @case('csv_upload')
                                            <span class="admin-badge green">Importación CSV</span>
                                            @break
                                        @case('excel_upload')
                                            <span class="admin-badge green">Importación Excel</span>
                                            @break
                                        @default
                                            <span class="admin-badge">{{ $employee->import_source ?? 'Desconocida' }}</span>
                                    @endswitch
                                </div>
                            </div>
                            @if($employee->last_sync_at)
                                <div>
                                    <span class="admin-label">Última sincronización</span>
                                    <div style="font-size:13.5px; color: var(--eia-black);">{{ $employee->last_sync_at->format('d/m/Y H:i') }}</div>
                                    <span style="font-size:11.5px; color: var(--eia-mute); margin-top:2px; display:inline-block;">{{ $employee->last_sync_at->diffForHumans() }}</span>
                                </div>
                            @endif
                            <div>
                                <span class="admin-label">Fecha de creación</span>
                                <div style="font-size:13.5px; color: var(--eia-black);">{{ $employee->created_at->format('d/m/Y H:i') }}</div>
                                <span style="font-size:11.5px; color: var(--eia-mute); margin-top:2px; display:inline-block;">{{ $employee->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Columna lateral --}}
            <div class="space-y-6">
                {{-- Tarjeta de identidad --}}
                <div class="admin-panel admin-fade admin-d1">
                    <div class="admin-panel-body" style="text-align:center;">
                        <div style="width:88px; height:88px; border-radius:16px; background: linear-gradient(180deg, #0F1419 0%, #1F2937 100%); color:#F8FAFC; display:inline-flex; align-items:center; justify-content:center; font-size:26px; font-weight:700; letter-spacing:0.04em; margin:0 auto; border:1px solid #0F1419; position:relative; overflow:hidden;">
                            <span style="position:absolute; left:0; right:0; bottom:0; height:3px; background: linear-gradient(90deg, var(--eia-red) 0%, var(--eia-gold) 100%);"></span>
                            {{ strtoupper(substr($employee->first_name, 0, 1)) }}{{ strtoupper(substr($employee->last_name, 0, 1)) }}
                        </div>
                        <h3 style="margin-top:14px; font-size:17px; font-weight:700; color: var(--eia-black);">{{ $employee->full_name }}</h3>
                        <p style="margin-top:2px; font-size:13px; color: var(--eia-slate);">{{ $employee->position }}</p>
                        <p style="margin-top:2px; font-size:11.5px; color: var(--eia-mute); letter-spacing:0.04em;">{{ $employee->department }}</p>
                    </div>
                </div>

                {{-- Acceso al sistema --}}
                <div class="admin-panel admin-fade admin-d2">
                    <div class="admin-panel-head">
                        <div>
                            <div class="admin-panel-title">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                                Acceso al sistema
                            </div>
                        </div>
                    </div>
                    <div class="admin-panel-body" style="text-align:center;">
                        @if($employee->hasSystemAccess())
                            <div style="width:48px; height:48px; border-radius:12px; background:#ECFDF5; color:#047857; border:1px solid #A7F3D0; display:inline-flex; align-items:center; justify-content:center; margin:0 auto;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p style="margin-top:10px; font-size:13.5px; font-weight:600; color: var(--eia-black);">Acceso habilitado</p>
                            <p style="margin-top:2px; font-size:12px; color: var(--eia-mute);">El empleado tiene acceso al sistema vía Google Auth.</p>
                            @if($employee->user)
                                <div style="margin-top:14px; padding:12px 14px; background:#FAFAFB; border:1px solid var(--eia-border); border-radius:10px; text-align:left;">
                                    <span class="admin-label">Usuario vinculado</span>
                                    <div style="font-size:13px; color: var(--eia-black); font-weight:600;">{{ $employee->user->name }}</div>
                                    <div style="font-size:12px; color: var(--eia-mute);">{{ $employee->user->email }}</div>
                                </div>
                            @endif
                        @else
                            <div style="width:48px; height:48px; border-radius:12px; background:#F1F5F9; color: var(--eia-slate); border:1px solid var(--eia-border); display:inline-flex; align-items:center; justify-content:center; margin:0 auto;">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <p style="margin-top:10px; font-size:13.5px; font-weight:600; color: var(--eia-black);">Sin acceso</p>
                            <p style="margin-top:2px; font-size:12px; color: var(--eia-mute);">El empleado aún no ha accedido al sistema.</p>
                        @endif
                    </div>
                </div>

                {{-- Acciones rápidas --}}
                <div class="admin-panel admin-fade admin-d3">
                    <div class="admin-panel-head">
                        <div>
                            <div class="admin-panel-title">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Acciones rápidas
                            </div>
                        </div>
                    </div>
                    <div class="admin-panel-body">
                        <div class="space-y-2">
                            <a href="mailto:{{ $employee->email }}" class="admin-btn-primary w-full justify-center">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Enviar email
                            </a>
                            @if($employee->phone)
                                <a href="tel:{{ $employee->phone }}" class="admin-btn-secondary w-full justify-center">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    Llamar
                                </a>
                            @endif
                            <a href="{{ route('admin.employees.export', ['search' => $employee->email]) }}" class="admin-btn-secondary w-full justify-center">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 14l5 5 5-5M12 19V7"/>
                                </svg>
                                Exportar datos
                            </a>
                            <a href="{{ route('admin.employees.index') }}" class="admin-btn-secondary w-full justify-center">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Volver a la lista
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .space-y-2 > * + * { margin-top: 8px; }
    .space-y-6 > * + * { margin-top: 24px; }
</style>
@endsection
