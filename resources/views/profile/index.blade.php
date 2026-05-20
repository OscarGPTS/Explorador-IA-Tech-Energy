@extends('layouts.app')

@push('styles')
<style>
    :root {
        --eia-black: #0F1419;
        --eia-graphite: #1F2937;
        --eia-slate: #475569;
        --eia-mute: #64748B;
        --eia-border: #E5E7EB;
        --eia-surface: #FFFFFF;
        --eia-bg: #F8FAFC;
        --eia-red: #B91C1C;
        --eia-gold: #D97706;
        --eia-gold-soft: #FBBF24;
    }

    .eia-bg { background: var(--eia-bg); }

    /* HERO */
    .pf-hero {
        background:
            radial-gradient(1000px 280px at 92% -40%, rgba(217, 119, 6, 0.18), transparent 60%),
            radial-gradient(800px 260px at 5% 130%, rgba(185, 28, 28, 0.22), transparent 60%),
            linear-gradient(180deg, #0F1419 0%, #1A1F26 100%);
        color: #F8FAFC;
        border-bottom: 1px solid var(--eia-graphite);
        position: relative;
    }
    .pf-hero::after {
        content: '';
        position: absolute;
        left: 0; right: 0; bottom: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--eia-red) 0%, var(--eia-gold) 100%);
        opacity: 0.85;
    }
    .pf-back {
        width: 38px; height: 38px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        background: rgba(255, 255, 255, 0.04);
        display: inline-flex; align-items: center; justify-content: center;
        color: #E2E8F0;
        transition: all .2s ease;
    }
    .pf-back:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: var(--eia-gold);
        color: #FFFFFF;
    }
    .eia-eyebrow {
        font-size: 11px;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--eia-gold-soft);
        font-weight: 600;
    }

    /* Tarjeta de identidad */
    .pf-identity {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 14px;
        padding: 24px;
        position: relative;
        overflow: hidden;
    }
    .pf-identity::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, var(--eia-red) 0%, var(--eia-gold) 100%);
    }
    .pf-avatar {
        width: 80px; height: 80px;
        border-radius: 50%;
        border: 2px solid var(--eia-black);
        box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.18);
        background: #F1F5F9;
        object-fit: cover;
    }
    .pf-meta {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--eia-mute);
    }
    .pf-meta svg { color: var(--eia-mute); }

    /* KPIs */
    .pf-kpi {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 12px;
        padding: 18px 20px;
        position: relative;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }
    .pf-kpi:hover {
        border-color: #94A3B8;
        box-shadow: 0 12px 26px -16px rgba(15, 20, 25, 0.3);
        transform: translateY(-2px);
    }
    .pf-kpi .accent {
        position: absolute;
        left: 0; top: 16px; bottom: 16px;
        width: 3px;
        border-radius: 2px;
        background: var(--eia-red);
    }
    .pf-kpi.gold .accent { background: var(--eia-gold); }
    .pf-kpi.black .accent { background: var(--eia-black); }
    .pf-kpi.slate .accent { background: var(--eia-slate); }
    .pf-kpi-label {
        font-size: 11px;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--eia-mute);
        font-weight: 600;
    }
    .pf-kpi-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--eia-black);
        line-height: 1;
        margin-top: 6px;
    }
    .pf-kpi-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: #F1F5F9;
        color: var(--eia-black);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--eia-border);
    }
    .pf-kpi.red .pf-kpi-icon { background: #FEF2F2; color: var(--eia-red); border-color: #FECACA; }
    .pf-kpi.gold .pf-kpi-icon { background: #FFFBEB; color: var(--eia-gold); border-color: #FDE68A; }
    .pf-kpi.black .pf-kpi-icon { background: #0F1419; color: #F8FAFC; border-color: #0F1419; }

    /* Panel */
    .pf-panel {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 14px;
    }
    .pf-panel-head {
        padding: 18px 22px;
        border-bottom: 1px solid var(--eia-border);
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 12px;
    }
    .pf-panel-title {
        font-size: 11px;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--eia-black);
    }
    .pf-panel-sub {
        font-size: 12px;
        color: var(--eia-mute);
        margin-top: 4px;
    }
    .pf-panel-body { padding: 18px 22px; }

    /* Bars */
    .pf-bar-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-top: 1px solid var(--eia-border);
    }
    .pf-bar-row:first-child { border-top: 0; }
    .pf-bar-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--eia-black);
        flex: 0 0 40%;
        text-transform: capitalize;
    }
    .pf-bar-track {
        flex: 1;
        height: 8px;
        background: #F1F5F9;
        border-radius: 999px;
        overflow: hidden;
        position: relative;
        border: 1px solid var(--eia-border);
    }
    .pf-bar-fill {
        height: 100%;
        background: var(--eia-black);
        border-radius: 999px;
        transition: width .35s ease;
    }
    .pf-bar-fill.red { background: var(--eia-red); }
    .pf-bar-fill.gold { background: var(--eia-gold); }
    .pf-bar-count {
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-size: 12px;
        font-weight: 600;
        color: var(--eia-slate);
        flex: 0 0 50px;
        text-align: right;
    }

    /* Activity rows */
    .pf-activity-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 0;
        border-top: 1px solid var(--eia-border);
        gap: 12px;
    }
    .pf-activity-row:first-child { border-top: 0; }
    .pf-activity-date {
        font-size: 12.5px;
        color: var(--eia-slate);
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    }

    /* Heatmap (hourly) */
    .pf-hour {
        text-align: center;
    }
    .pf-hour-label {
        font-size: 10px;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        color: var(--eia-mute);
        margin-bottom: 4px;
    }
    .pf-hour-cell {
        height: 56px;
        width: 100%;
        background: #F1F5F9;
        border: 1px solid var(--eia-border);
        border-radius: 6px;
        position: relative;
        overflow: hidden;
    }
    .pf-hour-fill {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        background: linear-gradient(180deg, var(--eia-gold) 0%, var(--eia-red) 100%);
        transition: height .3s ease;
    }
    .pf-hour-value {
        font-size: 10px;
        font-weight: 700;
        color: var(--eia-black);
        margin-top: 4px;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    }

    /* Performance metrics */
    .pf-perf-tile {
        background: linear-gradient(180deg, #FFFFFF 0%, #FAFAFB 100%);
        border: 1px solid var(--eia-border);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .pf-perf-tile::before {
        content: '';
        position: absolute;
        left: 0; right: 0; top: 0;
        height: 2px;
        background: var(--eia-red);
    }
    .pf-perf-tile.gold::before { background: var(--eia-gold); }
    .pf-perf-tile.black::before { background: var(--eia-black); }
    .pf-perf-value {
        font-size: 30px;
        font-weight: 700;
        color: var(--eia-black);
        letter-spacing: -0.02em;
    }
    .pf-perf-label {
        font-size: 11px;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--eia-mute);
        font-weight: 600;
        margin-top: 6px;
    }

    /* Fade-in */
    .eia-fade { animation: eiaFade .55s ease-out both; }
    .eia-d1 { animation-delay: .05s; }
    .eia-d2 { animation-delay: .12s; }
    .eia-d3 { animation-delay: .2s; }
    .eia-d4 { animation-delay: .28s; }
    @keyframes eiaFade {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="eia-bg min-h-screen">

    {{-- HERO --}}
    <section class="pf-hero px-4 sm:px-8 lg:px-12 py-10">
        <div class="max-w-7xl mx-auto flex items-start gap-4">
            <a href="/" class="pf-back" aria-label="Volver al inicio">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                </svg>
            </a>
            <div>
                <span class="eia-eyebrow">Cuenta corporativa</span>
                <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Mi perfil</h1>
                <p class="mt-1 text-sm text-slate-300 max-w-2xl">
                    Información personal y métricas de actividad en la plataforma corporativa.
                </p>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-8 lg:px-12 py-8">

        {{-- Identidad --}}
        <section class="pf-identity mb-6 eia-fade eia-d1">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                <img class="pf-avatar"
                     src="{{ $user->google_image ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=ffffff&background=0F1419' }}"
                     alt="{{ $user->name }}">
                <div class="flex-1 text-center sm:text-left">
                    <p class="eia-eyebrow" style="color: var(--eia-red);">Usuario activo</p>
                    <h2 class="mt-1 text-2xl font-semibold text-slate-900 tracking-tight">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>

                    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 mt-4">
                        <span class="pf-meta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Miembro desde {{ $stats['member_since']->locale('es')->translatedFormat('M Y') }}
                        </span>
                        @if($stats['last_activity'])
                        <span class="pf-meta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Última actividad {{ $stats['last_activity']->diffForHumans() }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- KPIs --}}
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="pf-kpi red eia-fade eia-d1">
                <span class="accent"></span>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="pf-kpi-label">Actividades</p>
                        <p class="pf-kpi-value">{{ number_format($stats['total_activities']) }}</p>
                        <p class="text-xs text-slate-500 mt-1">Total registrado</p>
                    </div>
                    <div class="pf-kpi-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8L10 18l-6-6"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="pf-kpi gold eia-fade eia-d2">
                <span class="accent"></span>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="pf-kpi-label">Sesiones</p>
                        <p class="pf-kpi-value">{{ number_format($stats['total_sessions']) }}</p>
                        <p class="text-xs text-slate-500 mt-1">Inicios de sesión</p>
                    </div>
                    <div class="pf-kpi-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H3m12 0l-4 4m4-4l-4-4M21 4v16"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="pf-kpi black eia-fade eia-d3">
                <span class="accent"></span>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="pf-kpi-label">Tasa de éxito</p>
                        <p class="pf-kpi-value">{{ $performanceStats['success_rate'] }}%</p>
                        <p class="text-xs text-slate-500 mt-1">Operaciones correctas</p>
                    </div>
                    <div class="pf-kpi-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="pf-kpi slate eia-fade eia-d4">
                <span class="accent"></span>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="pf-kpi-label">Errores</p>
                        <p class="pf-kpi-value">{{ number_format($performanceStats['total_errors']) }}</p>
                        <p class="text-xs text-slate-500 mt-1">Eventos atípicos</p>
                    </div>
                    <div class="pf-kpi-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 110 18 9 9 0 010-18z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        {{-- Grid principal --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Uso por Módulos --}}
            <section class="pf-panel eia-fade eia-d1">
                <div class="pf-panel-head">
                    <div>
                        <p class="pf-panel-title">Uso por módulos</p>
                        <p class="pf-panel-sub">Distribución de actividad por aplicación</p>
                    </div>
                </div>
                <div class="pf-panel-body">
                    @forelse($moduleStats as $module)
                        <div class="pf-bar-row">
                            <span class="pf-bar-label">{{ str_replace('_', ' ', $module->type) }}</span>
                            <div class="pf-bar-track">
                                <div class="pf-bar-fill" style="width: {{ $moduleStats->max('count') ? ($module->count / $moduleStats->max('count')) * 100 : 0 }}%"></div>
                            </div>
                            <span class="pf-bar-count">{{ number_format($module->count) }}</span>
                        </div>
                    @empty
                        <p class="text-center text-slate-500 py-8 text-sm">No hay datos de actividad disponibles.</p>
                    @endforelse
                </div>
            </section>

            {{-- Navegadores --}}
            <section class="pf-panel eia-fade eia-d2">
                <div class="pf-panel-head">
                    <div>
                        <p class="pf-panel-title">Navegadores</p>
                        <p class="pf-panel-sub">Clientes utilizados para acceder</p>
                    </div>
                </div>
                <div class="pf-panel-body">
                    @forelse($browserStats as $browser)
                        <div class="pf-bar-row">
                            <span class="pf-bar-label">{{ $browser['browser'] }}</span>
                            <div class="pf-bar-track">
                                <div class="pf-bar-fill gold" style="width: {{ $browserStats->max('count') ? ($browser['count'] / $browserStats->max('count')) * 100 : 0 }}%"></div>
                            </div>
                            <span class="pf-bar-count">{{ number_format($browser['count']) }}</span>
                        </div>
                    @empty
                        <p class="text-center text-slate-500 py-8 text-sm">No hay datos de navegador disponibles.</p>
                    @endforelse
                </div>
            </section>

            {{-- Actividad reciente --}}
            <section class="pf-panel eia-fade eia-d3">
                <div class="pf-panel-head">
                    <div>
                        <p class="pf-panel-title">Actividad reciente</p>
                        <p class="pf-panel-sub">Últimos 30 días</p>
                    </div>
                </div>
                <div class="pf-panel-body">
                    <div class="space-y-0 max-h-72 overflow-y-auto pr-2">
                        @forelse($activityByDay as $day)
                            <div class="pf-activity-row">
                                <span class="pf-activity-date">{{ \Carbon\Carbon::parse($day->date)->locale('es')->translatedFormat('d M Y') }}</span>
                                <div class="flex items-center gap-3 flex-1 max-w-[55%]">
                                    <div class="pf-bar-track">
                                        <div class="pf-bar-fill red" style="width: {{ $activityByDay->max('count') ? ($day->count / $activityByDay->max('count')) * 100 : 0 }}%"></div>
                                    </div>
                                    <span class="pf-bar-count">{{ $day->count }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-slate-500 py-8 text-sm">No hay actividad reciente.</p>
                        @endforelse
                    </div>
                </div>
            </section>

            {{-- Horarios de mayor actividad --}}
            <section class="pf-panel eia-fade eia-d4">
                <div class="pf-panel-head">
                    <div>
                        <p class="pf-panel-title">Horarios de mayor actividad</p>
                        <p class="pf-panel-sub">Intensidad por hora del día</p>
                    </div>
                </div>
                <div class="pf-panel-body">
                    <div class="grid grid-cols-6 gap-2">
                        @for($hour = 0; $hour < 24; $hour++)
                            @php
                                $activity = $hourlyActivity->where('hour', $hour)->first();
                                $count = $activity ? $activity->count : 0;
                                $maxActivity = $hourlyActivity->max('count') ?: 1;
                                $intensity = ($count / $maxActivity) * 100;
                            @endphp
                            <div class="pf-hour">
                                <div class="pf-hour-label">{{ sprintf('%02d', $hour) }}h</div>
                                <div class="pf-hour-cell" title="{{ $count }} actividades">
                                    <div class="pf-hour-fill" style="height: {{ $intensity }}%"></div>
                                </div>
                                <div class="pf-hour-value">{{ $count }}</div>
                            </div>
                        @endfor
                    </div>
                </div>
            </section>
        </div>

        {{-- Rendimiento --}}
        @if($performanceStats['avg_response_time'])
        <section class="pf-panel mt-6 eia-fade">
            <div class="pf-panel-head">
                <div>
                    <p class="pf-panel-title">Rendimiento</p>
                    <p class="pf-panel-sub">Métricas operacionales del usuario</p>
                </div>
            </div>
            <div class="pf-panel-body">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="pf-perf-tile">
                        <div class="pf-perf-value">{{ round($performanceStats['avg_response_time'], 2) }} <span class="text-base font-medium text-slate-500">ms</span></div>
                        <div class="pf-perf-label">Tiempo promedio</div>
                    </div>
                    <div class="pf-perf-tile gold">
                        <div class="pf-perf-value">{{ $performanceStats['success_rate'] }} <span class="text-base font-medium text-slate-500">%</span></div>
                        <div class="pf-perf-label">Tasa de éxito</div>
                    </div>
                    <div class="pf-perf-tile black">
                        <div class="pf-perf-value">{{ $performanceStats['total_errors'] }}</div>
                        <div class="pf-perf-label">Errores</div>
                    </div>
                </div>
            </div>
        </section>
        @endif

    </div>
</div>
@endsection
