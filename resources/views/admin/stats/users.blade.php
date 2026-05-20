@extends('layouts.app')

@push('styles')
@include('admin._admin-styles')
<style>
    .avatar-circle {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: #F1F5F9;
        color: var(--eia-black);
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--eia-border);
    }
    .month-tile {
        background: #FFFFFF;
        border: 1px solid var(--eia-border);
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .month-tile:hover {
        border-color: #94A3B8;
        box-shadow: 0 10px 22px -16px rgba(15, 20, 25, 0.25);
    }
    .month-tile-value {
        font-size: 22px;
        font-weight: 700;
        color: var(--eia-black);
        line-height: 1;
    }
    .month-tile-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: var(--eia-mute);
        font-weight: 600;
        margin-top: 6px;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen eia-bg">
    <!-- HERO -->
    <section class="admin-hero px-4 sm:px-8 lg:px-12 py-10">
        <div class="max-w-7xl mx-auto flex items-start justify-between gap-6 flex-wrap">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.stats.dashboard') }}" class="admin-back" aria-label="Volver al dashboard">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                    </svg>
                </a>
                <div>
                    <span class="admin-eyebrow">Administración · Estadísticas</span>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Estadísticas de Usuarios</h1>
                    <p class="mt-1 text-sm text-slate-300 max-w-2xl">Análisis detallado de actividad y comportamiento de usuarios.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.stats.export', ['type' => 'users', 'format' => 'csv']) }}" class="admin-action">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                    </svg>
                    Exportar CSV
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

            <!-- KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 admin-fade admin-d2">
                <div class="admin-kpi red">
                    <span class="accent"></span>
                    <div class="flex items-center gap-4">
                        <div class="admin-kpi-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-5.13a4 4 0 11-8 0 4 4 0 018 0zm6 0a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="admin-kpi-label">Total Usuarios</div>
                            <div class="admin-kpi-value">{{ number_format($totalUsers) }}</div>
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
                            <div class="admin-kpi-label">Verificados</div>
                            <div class="admin-kpi-value">{{ number_format($verifiedUsers) }}</div>
                        </div>
                    </div>
                </div>

                <div class="admin-kpi black">
                    <span class="accent"></span>
                    <div class="flex items-center gap-4">
                        <div class="admin-kpi-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="9"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M12 3a14.5 14.5 0 010 18M12 3a14.5 14.5 0 000 18"/>
                            </svg>
                        </div>
                        <div>
                            <div class="admin-kpi-label">Google OAuth</div>
                            <div class="admin-kpi-value">{{ number_format($googleUsers) }}</div>
                        </div>
                    </div>
                </div>

                <div class="admin-kpi slate">
                    <span class="accent"></span>
                    <div class="flex items-center gap-4">
                        <div class="admin-kpi-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="admin-kpi-label">Registro Normal</div>
                            <div class="admin-kpi-value">{{ number_format($regularUsers) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usuarios más activos detallados -->
            <div class="admin-panel mb-8 admin-fade admin-d3">
                <div class="admin-panel-head">
                    <div>
                        <div class="admin-panel-title">Usuarios más activos · Detallado</div>
                        <div class="admin-panel-sub">Mensajes acumulados, días de actividad y último registro.</div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Mensajes</th>
                                <th>Días Activos</th>
                                <th>Último Mensaje</th>
                                <th>Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeUsersDetailed as $user)
                            <tr>
                                <td class="primary">
                                    <div class="flex items-center gap-3">
                                        <div class="avatar-circle">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td><span class="admin-badge red">{{ number_format($user->total_messages) }}</span></td>
                                <td><span class="admin-badge gold">{{ number_format($user->active_days) }}</span></td>
                                <td>
                                    @if($user->last_message_at)
                                        {{ \Carbon\Carbon::parse($user->last_message_at)->diffForHumans() }}
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Actividad por día de la semana -->
            <div class="admin-panel mb-8 admin-fade admin-d3">
                <div class="admin-panel-head">
                    <div>
                        <div class="admin-panel-title">Actividad por día de la semana</div>
                        <div class="admin-panel-sub">Volumen relativo de mensajes por día.</div>
                    </div>
                </div>
                <div class="admin-panel-body">
                    @php $maxActivity = $activityByDayOfWeek->max('count') ?: 1; @endphp
                    <div class="space-y-4">
                        @foreach($activityByDayOfWeek as $dayActivity)
                        @php $percentage = $maxActivity > 0 ? ($dayActivity->count / $maxActivity) * 100 : 0; @endphp
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-semibold text-slate-700 w-24 shrink-0">{{ $dayActivity->day_name }}</span>
                            <div class="flex-1">
                                <div class="admin-bar-track" style="height: 10px;">
                                    <div class="admin-bar-fill {{ $loop->index % 2 === 0 ? 'red' : 'gold' }}" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            <span class="text-xs font-mono text-slate-500 w-20 text-right">{{ number_format($dayActivity->count) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Registros por mes -->
            <div class="admin-panel admin-fade admin-d4">
                <div class="admin-panel-head">
                    <div>
                        <div class="admin-panel-title">Registros por mes · Últimos 12 meses</div>
                        <div class="admin-panel-sub">Nuevos usuarios agrupados por mes.</div>
                    </div>
                </div>
                <div class="admin-panel-body">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                        @foreach($usersByMonth as $monthData)
                        <div class="month-tile">
                            <div class="month-tile-value">{{ number_format($monthData->count) }}</div>
                            <div class="month-tile-label">{{ \Carbon\Carbon::create($monthData->year, $monthData->month)->format('M Y') }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
