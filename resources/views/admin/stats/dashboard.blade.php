@extends('layouts.app')

@push('styles')
@include('admin._admin-styles')
<style>
    .hour-bars {
        display: grid;
        grid-template-columns: repeat(24, minmax(0, 1fr));
        gap: 4px;
        align-items: end;
        height: 140px;
        padding-top: 8px;
    }
    .hour-bar-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        height: 100%;
        justify-content: end;
    }
    .hour-bar-col {
        width: 100%;
        background: var(--eia-black);
        border-radius: 4px 4px 0 0;
        min-height: 3px;
        transition: background .2s ease;
    }
    .hour-bar-col:hover { background: var(--eia-gold); }
    .hour-bar-label {
        font-size: 9.5px;
        color: var(--eia-mute);
        font-weight: 600;
        letter-spacing: 0.04em;
    }
    .summary-tile {
        background: #FFFFFF;
        border: 1px solid var(--eia-border);
        border-radius: 12px;
        padding: 18px 20px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .summary-tile::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--eia-red);
    }
    .summary-tile.gold::before { background: var(--eia-gold); }
    .summary-tile.black::before { background: var(--eia-black); }
    .summary-tile.slate::before { background: var(--eia-slate); }
    .summary-tile-value {
        font-size: 26px;
        font-weight: 700;
        color: var(--eia-black);
        line-height: 1;
    }
    .summary-tile-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: var(--eia-mute);
        font-weight: 600;
        margin-top: 8px;
    }
    .agent-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 16px;
        border: 1px solid var(--eia-border);
        border-radius: 10px;
        background: #FFFFFF;
        transition: border-color .2s ease, background .2s ease;
    }
    .agent-row:hover { border-color: #94A3B8; background: #F8FAFC; }
    .module-tile {
        background: #FFFFFF;
        border: 1px solid var(--eia-border);
        border-radius: 12px;
        padding: 16px 18px;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .module-tile:hover {
        border-color: #94A3B8;
        box-shadow: 0 10px 22px -16px rgba(15, 20, 25, 0.25);
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
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Panel de Estadísticas</h1>
                    <p class="mt-1 text-sm text-slate-300 max-w-2xl">Dashboard y análisis de uso de la plataforma.</p>
                </div>
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

            <!-- KPIs principales -->
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="admin-kpi-label">Activos Hoy</div>
                            <div class="admin-kpi-value">{{ number_format($activeUsersToday) }}</div>
                        </div>
                    </div>
                </div>

                <div class="admin-kpi black">
                    <span class="accent"></span>
                    <div class="flex items-center gap-4">
                        <div class="admin-kpi-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="admin-kpi-label">Total Mensajes</div>
                            <div class="admin-kpi-value">{{ number_format($totalChats) }}</div>
                        </div>
                    </div>
                </div>

                <div class="admin-kpi slate">
                    <span class="accent"></span>
                    <div class="flex items-center gap-4">
                        <div class="admin-kpi-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="admin-kpi-label">Mensajes Hoy</div>
                            <div class="admin-kpi-value">{{ number_format($chatsToday) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen del último mes -->
            <div class="admin-panel mb-8 admin-fade admin-d3">
                <div class="admin-panel-head">
                    <div>
                        <div class="admin-panel-title">Resumen del último mes</div>
                        <div class="admin-panel-sub">Indicadores acumulados de los últimos 30 días.</div>
                    </div>
                </div>
                <div class="admin-panel-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="summary-tile">
                            <div class="summary-tile-value">{{ number_format($lastMonthStats['new_users']) }}</div>
                            <div class="summary-tile-label">Nuevos Usuarios</div>
                        </div>
                        <div class="summary-tile gold">
                            <div class="summary-tile-value">{{ number_format($lastMonthStats['total_messages']) }}</div>
                            <div class="summary-tile-label">Total Mensajes</div>
                        </div>
                        <div class="summary-tile black">
                            <div class="summary-tile-value">{{ number_format($lastMonthStats['active_configurations']) }}</div>
                            <div class="summary-tile-label">Configuraciones Activas</div>
                        </div>
                        <div class="summary-tile slate">
                            <div class="summary-tile-value">{{ number_format($lastMonthStats['avg_messages_per_user'] ?? 0, 1) }}</div>
                            <div class="summary-tile-label">Promedio Mensajes/Usuario</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Distribución de mensajes -->
                <div class="admin-panel admin-fade admin-d3">
                    <div class="admin-panel-head">
                        <div>
                            <div class="admin-panel-title">Distribución de mensajes</div>
                            <div class="admin-panel-sub">Mensajes por origen (usuario vs IA).</div>
                        </div>
                    </div>
                    <div class="admin-panel-body">
                        <div class="space-y-5">
                            @foreach($messageDistribution as $type)
                                @php
                                    $pct = $totalChats > 0 ? ($type->count / $totalChats) * 100 : 0;
                                    $isUser = $type->sender === 'user';
                                @endphp
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-semibold text-slate-700">
                                            {{ $isUser ? 'Usuario' : 'IA' }}
                                        </span>
                                        <span class="text-xs font-mono text-slate-500">{{ number_format($type->count) }} · {{ number_format($pct, 1) }}%</span>
                                    </div>
                                    <div class="admin-bar-track">
                                        <div class="admin-bar-fill {{ $isUser ? 'red' : 'gold' }}" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Actividad por hora -->
                <div class="admin-panel admin-fade admin-d4">
                    <div class="admin-panel-head">
                        <div>
                            <div class="admin-panel-title">Actividad por hora</div>
                            <div class="admin-panel-sub">Volumen de mensajes por hora del día.</div>
                        </div>
                    </div>
                    <div class="admin-panel-body">
                        @php $maxHourCount = $activityByHour->max('count') ?: 1; @endphp
                        <div class="hour-bars">
                            @foreach($activityByHour as $hourData)
                                @php
                                    $hPct = $hourData->count > 0 ? max(($hourData->count / $maxHourCount) * 100, 4) : 2;
                                @endphp
                                <div class="hour-bar-wrap" title="{{ $hourData->hour }}h · {{ number_format($hourData->count) }}">
                                    <div class="hour-bar-col" style="height: {{ $hPct }}%;"></div>
                                </div>
                            @endforeach
                        </div>
                        <div class="hour-bars" style="height: auto; padding-top: 6px;">
                            @foreach($activityByHour as $hourData)
                                <div class="hour-bar-label" style="text-align: center;">{{ $hourData->hour }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usuarios más activos -->
            <div class="admin-panel mb-8 admin-fade admin-d4">
                <div class="admin-panel-head">
                    <div>
                        <div class="admin-panel-title">Usuarios más activos</div>
                        <div class="admin-panel-sub">Top 10 usuarios con mayor volumen de mensajes.</div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Mensajes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mostActiveUsers->take(10) as $user)
                            <tr>
                                <td class="primary">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="admin-badge red">{{ number_format($user->chat_count) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Agentes más populares -->
            @if($popularAgentConfigs->count() > 0)
            <div class="admin-panel mb-8 admin-fade admin-d4">
                <div class="admin-panel-head">
                    <div>
                        <div class="admin-panel-title">Configuraciones de agentes populares</div>
                        <div class="admin-panel-sub">Agentes con mayor número de usos.</div>
                    </div>
                </div>
                <div class="admin-panel-body">
                    <div class="space-y-3">
                        @foreach($popularAgentConfigs as $agent)
                        <div class="agent-row">
                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-slate-900 truncate">{{ $agent->name }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">{{ strlen($agent->description) > 80 ? substr($agent->description, 0, 80) . '...' : $agent->description }}</div>
                            </div>
                            <span class="admin-badge gold">{{ number_format($agent->usage_count) }} usos</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Uso de módulos -->
            @if($moduleUsage->count() > 0)
            <div class="admin-panel mb-8 admin-fade admin-d4">
                <div class="admin-panel-head">
                    <div>
                        <div class="admin-panel-title">Uso de módulos / Apps</div>
                        <div class="admin-panel-sub">Top 6 módulos con mayor volumen de uso.</div>
                    </div>
                    <a href="{{ route('admin.stats.modules') }}" class="admin-btn-secondary">
                        Ver análisis completo
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <div class="admin-panel-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($moduleUsage->take(6) as $module)
                        <div class="module-tile">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-slate-900">{{ ucfirst($module->type) }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ number_format($module->unique_users) }} usuarios únicos</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xl font-bold text-slate-900 leading-none">{{ number_format($module->usage_count) }}</div>
                                    <div class="text-[10px] uppercase tracking-wider text-slate-500 font-semibold mt-1">usos</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </section>
</div>
@endsection
