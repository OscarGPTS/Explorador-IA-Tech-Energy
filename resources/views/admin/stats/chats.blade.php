@extends('layouts.app')

@push('styles')
@include('admin._admin-styles')<style>
    .hour-bars {
        display: grid;
        grid-template-columns: repeat(24, minmax(0, 1fr));
        gap: 4px;
        align-items: end;
        height: 160px;
        padding-top: 8px;
    }
    .hour-bar-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        height: 100%;
        justify-content: end;
        position: relative;
    }
    .hour-bar-col {
        width: 100%;
        background: var(--eia-black);
        border-radius: 4px 4px 0 0;
        min-height: 4px;
        transition: background .2s ease;
        position: relative;
    }
    .hour-bar-col:hover { background: var(--eia-gold); }
    .hour-bar-count {
        position: absolute;
        top: -18px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 9.5px;
        color: var(--eia-slate);
        font-weight: 700;
        white-space: nowrap;
    }
    .hour-bar-label {
        font-size: 9.5px;
        color: var(--eia-mute);
        font-weight: 600;
        letter-spacing: 0.04em;
        text-align: center;
    }
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
    .avatar-circle.podium-1 { background: #FFFBEB; color: #92400E; border-color: #FDE68A; }
    .avatar-circle.podium-2 { background: #F1F5F9; color: var(--eia-slate); border-color: #CBD5E1; }
    .avatar-circle.podium-3 { background: #FEF2F2; color: var(--eia-red); border-color: #FECACA; }
    .dist-tile {
        background: #FFFFFF;
        border: 1px solid var(--eia-border);
        border-radius: 12px;
        padding: 22px;
        position: relative;
        overflow: hidden;
    }
    .dist-tile::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--eia-red);
    }
    .dist-tile.ai::before { background: var(--eia-gold); }
    .dist-tile-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--eia-black);
        line-height: 1;
        margin-top: 8px;
    }
    .dist-tile-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: var(--eia-mute);
        font-weight: 600;
        margin-top: 8px;
    }
    .dist-tile-eyebrow {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.18em;
        font-weight: 700;
        color: var(--eia-red);
    }
    .dist-tile.ai .dist-tile-eyebrow { color: var(--eia-gold); }
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
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Estadísticas de Chats</h1>
                    <p class="mt-1 text-sm text-slate-300 max-w-2xl">Análisis detallado de conversaciones y mensajes.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.stats.export', ['type' => 'chats', 'format' => 'csv']) }}" class="admin-action">
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8 admin-fade admin-d2">
                <div class="admin-kpi red">
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

                <div class="admin-kpi gold">
                    <span class="accent"></span>
                    <div class="flex items-center gap-4">
                        <div class="admin-kpi-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="admin-kpi-label">Conversaciones</div>
                            <div class="admin-kpi-value">{{ number_format($totalGroups) }}</div>
                        </div>
                    </div>
                </div>

                <div class="admin-kpi black">
                    <span class="accent"></span>
                    <div class="flex items-center gap-4">
                        <div class="admin-kpi-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="admin-kpi-label">Promedio / Conversación</div>
                            <div class="admin-kpi-value">{{ $avgMessagesPerGroup }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actividad por hora -->
            <div class="admin-panel mb-8 admin-fade admin-d3">
                <div class="admin-panel-head">
                    <div>
                        <div class="admin-panel-title">Actividad por hora del día</div>
                        <div class="admin-panel-sub">Volumen de mensajes por cada hora.</div>
                    </div>
                </div>
                <div class="admin-panel-body">
                    @php $maxCount = max($hourlyActivity) ?: 1; @endphp
                    <div class="hour-bars">
                        @for($hour = 0; $hour < 24; $hour++)
                            @php
                                $count = $hourlyActivity[$hour] ?? 0;
                                $height = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                            @endphp
                            <div class="hour-bar-wrap" title="{{ sprintf('%02d:00', $hour) }} · {{ number_format($count) }}">
                                <div class="hour-bar-col" style="height: {{ max($height, 3) }}%;">
                                    @if($count > 0)
                                        <span class="hour-bar-count">{{ $count }}</span>
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>
                    <div class="hour-bars" style="height: auto; padding-top: 8px;">
                        @for($hour = 0; $hour < 24; $hour++)
                            <div class="hour-bar-label">{{ sprintf('%02d', $hour) }}</div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Distribución de mensajes -->
            <div class="admin-panel mb-8 admin-fade admin-d3">
                <div class="admin-panel-head">
                    <div>
                        <div class="admin-panel-title">Distribución de mensajes · Últimos 30 días</div>
                        <div class="admin-panel-sub">Origen del mensaje: usuario vs IA.</div>
                    </div>
                </div>
                <div class="admin-panel-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach($messageTypeStats as $typeStats)
                            @php $isUser = $typeStats->sender === 'user'; @endphp
                            <div class="dist-tile {{ $isUser ? '' : 'ai' }}">
                                <div class="dist-tile-eyebrow">{{ $isUser ? 'Usuario' : 'Inteligencia Artificial' }}</div>
                                <div class="dist-tile-value">{{ number_format($typeStats->count) }}</div>
                                <div class="dist-tile-label">
                                    {{ $isUser ? 'Mensajes de Usuario' : 'Respuestas de IA' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Conversaciones más largas -->
            <div class="admin-panel mb-8 admin-fade admin-d4">
                <div class="admin-panel-head">
                    <div>
                        <div class="admin-panel-title">Conversaciones más largas</div>
                        <div class="admin-panel-sub">Top de conversaciones con mayor número de mensajes.</div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>ID Conversación</th>
                                <th>Mensajes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($longestConversations as $conversation)
                            <tr>
                                <td class="primary">
                                    <div class="flex items-center gap-3">
                                        <div class="avatar-circle">{{ strtoupper(substr($conversation->user->name ?? 'U', 0, 1)) }}</div>
                                        <span>{{ $conversation->user->name ?? 'Usuario Desconocido' }}</span>
                                    </div>
                                </td>
                                <td>{{ $conversation->user->email ?? '-' }}</td>
                                <td><span class="font-mono text-xs text-slate-700">{{ substr($conversation->chatgroup_id, 0, 8) }}…</span></td>
                                <td><span class="admin-badge red">{{ number_format($conversation->message_count) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top usuarios de la semana -->
            <div class="admin-panel admin-fade admin-d4">
                <div class="admin-panel-head">
                    <div>
                        <div class="admin-panel-title">Top usuarios de la semana</div>
                        <div class="admin-panel-sub">Usuarios con mayor actividad en los últimos 7 días.</div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Posición</th>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Mensajes Esta Semana</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($weeklyTopUsers as $index => $user)
                            <tr>
                                <td>
                                    @if($index === 0)
                                        <span class="admin-badge gold">1.º</span>
                                    @elseif($index === 1)
                                        <span class="admin-badge">2.º</span>
                                    @elseif($index === 2)
                                        <span class="admin-badge red">3.º</span>
                                    @else
                                        <span class="admin-badge">{{ $index + 1 }}.º</span>
                                    @endif
                                </td>
                                <td class="primary">
                                    <div class="flex items-center gap-3">
                                        <div class="avatar-circle {{ $index === 0 ? 'podium-1' : ($index === 1 ? 'podium-2' : ($index === 2 ? 'podium-3' : '')) }}">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td><span class="admin-badge gold">{{ number_format($user->weekly_messages) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection
