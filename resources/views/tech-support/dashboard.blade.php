@extends('layouts.app')

@section('title', 'Dashboard · Soporte Técnico')

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
    .tsd-hero {
        background:
            radial-gradient(1000px 280px at 92% -40%, rgba(217, 119, 6, 0.18), transparent 60%),
            radial-gradient(800px 260px at 5% 130%, rgba(185, 28, 28, 0.22), transparent 60%),
            linear-gradient(180deg, #0F1419 0%, #1A1F26 100%);
        color: #F8FAFC;
        border-bottom: 1px solid var(--eia-graphite);
        position: relative;
    }
    .tsd-hero::after {
        content: '';
        position: absolute;
        left: 0; right: 0; bottom: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--eia-red) 0%, var(--eia-gold) 100%);
        opacity: 0.85;
    }
    .tsd-back {
        width: 38px; height: 38px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        background: rgba(255, 255, 255, 0.04);
        display: inline-flex; align-items: center; justify-content: center;
        color: #E2E8F0;
        transition: all .2s ease;
    }
    .tsd-back:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: var(--eia-gold);
        color: #FFFFFF;
    }
    .tsd-eyebrow {
        font-size: 11px;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--eia-gold-soft);
        font-weight: 600;
    }
    .tsd-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border: 1px solid rgba(255, 255, 255, 0.22);
        background: rgba(255, 255, 255, 0.06);
        color: #FFFFFF;
        border-radius: 10px;
        font-size: 12.5px;
        font-weight: 600;
        transition: all .2s ease;
        text-decoration: none;
    }
    .tsd-action:hover {
        background: rgba(217, 119, 6, 0.15);
        border-color: var(--eia-gold);
    }

    /* KPI */
    .tsd-kpi {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 12px;
        padding: 18px 20px;
        position: relative;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        overflow: hidden;
    }
    .tsd-kpi:hover {
        border-color: #94A3B8;
        box-shadow: 0 12px 26px -16px rgba(15, 20, 25, 0.3);
        transform: translateY(-2px);
    }
    .tsd-kpi .accent {
        position: absolute;
        left: 0; top: 16px; bottom: 16px;
        width: 3px;
        border-radius: 2px;
        background: var(--eia-red);
    }
    .tsd-kpi.gold .accent { background: var(--eia-gold); }
    .tsd-kpi.black .accent { background: var(--eia-black); }
    .tsd-kpi.slate .accent { background: var(--eia-slate); }
    .tsd-kpi-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: #F1F5F9;
        color: var(--eia-black);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--eia-border);
    }
    .tsd-kpi.red .tsd-kpi-icon { background: #FEF2F2; color: var(--eia-red); border-color: #FECACA; }
    .tsd-kpi.gold .tsd-kpi-icon { background: #FFFBEB; color: var(--eia-gold); border-color: #FDE68A; }
    .tsd-kpi.black .tsd-kpi-icon { background: #0F1419; color: #F8FAFC; border-color: #0F1419; }
    .tsd-kpi-label {
        font-size: 11px;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--eia-mute);
        font-weight: 600;
    }
    .tsd-kpi-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--eia-black);
        line-height: 1;
        margin-top: 6px;
        letter-spacing: -0.01em;
    }
    .tsd-kpi-value .unit {
        font-size: 14px;
        font-weight: 500;
        color: var(--eia-mute);
        margin-left: 2px;
    }
    .tsd-kpi-delta {
        font-size: 11px;
        font-weight: 600;
        margin-top: 8px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        letter-spacing: 0.02em;
    }
    .tsd-kpi-delta.up { color: #047857; }
    .tsd-kpi-delta.down { color: var(--eia-red); }

    /* Panel */
    .tsd-panel {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 14px;
    }
    .tsd-panel-head {
        padding: 18px 22px;
        border-bottom: 1px solid var(--eia-border);
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .tsd-panel-title {
        font-size: 11px;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--eia-black);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .tsd-panel-sub {
        font-size: 12px;
        color: var(--eia-mute);
        margin-top: 4px;
    }
    .tsd-panel-body { padding: 20px 22px; }

    /* Popular problems list */
    .tsd-problem-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        border: 1px solid var(--eia-border);
        border-radius: 10px;
        background: #FFFFFF;
        margin-bottom: 8px;
        transition: all .2s ease;
        position: relative;
        overflow: hidden;
    }
    .tsd-problem-row::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--eia-red);
        opacity: 0;
        transition: opacity .2s ease;
    }
    .tsd-problem-row:hover {
        border-color: #94A3B8;
        background: #FAFAFB;
    }
    .tsd-problem-row:hover::before { opacity: 1; }
    .tsd-problem-rank {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px; height: 28px;
        border-radius: 8px;
        background: var(--eia-black);
        color: var(--eia-gold-soft);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.04em;
        flex-shrink: 0;
        margin-right: 12px;
    }
    .tsd-problem-rank.gold { background: var(--eia-gold); color: #FFFFFF; }
    .tsd-problem-rank.red { background: var(--eia-red); color: #FFFFFF; }
    .tsd-problem-count {
        font-size: 18px;
        font-weight: 700;
        color: var(--eia-black);
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        line-height: 1;
    }
    .tsd-problem-count-sub {
        font-size: 10px;
        color: var(--eia-mute);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-top: 2px;
    }

    /* Table */
    .tsd-table {
        width: 100%;
        border-collapse: collapse;
    }
    .tsd-table thead th {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: var(--eia-mute);
        text-align: left;
        padding: 12px 18px;
        background: #FAFAFB;
        border-bottom: 1px solid var(--eia-border);
    }
    .tsd-table tbody td {
        padding: 14px 18px;
        font-size: 13.5px;
        color: var(--eia-slate);
        border-bottom: 1px solid var(--eia-border);
        vertical-align: middle;
    }
    .tsd-table tbody tr:last-child td { border-bottom: 0; }
    .tsd-table tbody tr {
        transition: background .15s ease;
    }
    .tsd-table tbody tr:hover { background: #F8FAFC; }
    .tsd-cat-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: #F1F5F9;
        color: var(--eia-black);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--eia-border);
        margin-right: 10px;
        vertical-align: middle;
    }

    /* Bar */
    .tsd-bar-track {
        height: 6px;
        width: 70px;
        background: #F1F5F9;
        border-radius: 999px;
        overflow: hidden;
        border: 1px solid var(--eia-border);
        display: inline-block;
        vertical-align: middle;
    }
    .tsd-bar-fill {
        height: 100%;
        background: var(--eia-black);
        border-radius: 999px;
        transition: width .35s ease;
    }
    .tsd-bar-fill.gold { background: var(--eia-gold); }
    .tsd-bar-fill.red { background: var(--eia-red); }

    /* Badge */
    .tsd-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 4px 9px;
        border-radius: 999px;
        background: #F1F5F9;
        color: var(--eia-slate);
        border: 1px solid var(--eia-border);
    }
    .tsd-badge.up { background: #ECFDF5; color: #047857; border-color: #A7F3D0; }
    .tsd-badge.down { background: #FEF2F2; color: var(--eia-red); border-color: #FECACA; }

    /* Acciones rápidas */
    .tsd-quick {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
        padding: 18px 20px;
        border: 1px solid var(--eia-border);
        border-radius: 12px;
        background: #FFFFFF;
        text-align: left;
        cursor: pointer;
        transition: all .2s ease;
        position: relative;
        overflow: hidden;
        width: 100%;
    }
    .tsd-quick::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--eia-black);
        opacity: 0;
        transition: opacity .2s ease;
    }
    .tsd-quick:hover {
        background: #F8FAFC;
        border-color: #94A3B8;
        transform: translateY(-2px);
    }
    .tsd-quick:hover::before { opacity: 1; }
    .tsd-quick.red:hover::before { background: var(--eia-red); }
    .tsd-quick.gold:hover::before { background: var(--eia-gold); }
    .tsd-quick-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: #F1F5F9;
        color: var(--eia-black);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--eia-border);
        margin-bottom: 10px;
    }
    .tsd-quick.red .tsd-quick-icon { background: #FEF2F2; color: var(--eia-red); border-color: #FECACA; }
    .tsd-quick.gold .tsd-quick-icon { background: #FFFBEB; color: var(--eia-gold); border-color: #FDE68A; }
    .tsd-quick-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--eia-black);
        letter-spacing: -0.01em;
    }
    .tsd-quick-sub {
        font-size: 12px;
        color: var(--eia-mute);
    }

    /* Empty state */
    .tsd-empty {
        text-align: center;
        padding: 40px 20px;
        color: var(--eia-mute);
    }
    .tsd-empty-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        background: #F1F5F9;
        color: var(--eia-mute);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        border: 1px solid var(--eia-border);
    }

    /* Fade-in */
    .tsd-fade { animation: tsdFade .55s ease-out both; }
    .tsd-d1 { animation-delay: .05s; }
    .tsd-d2 { animation-delay: .12s; }
    .tsd-d3 { animation-delay: .2s; }
    .tsd-d4 { animation-delay: .28s; }
    @keyframes tsdFade {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="eia-bg min-h-screen">

    {{-- HERO --}}
    <section class="tsd-hero px-4 sm:px-8 lg:px-12 py-10">
        <div class="max-w-7xl mx-auto flex items-start justify-between gap-6 flex-wrap">
            <div class="flex items-center gap-4">
                <a href="{{ route('tech-support.index') }}" class="tsd-back" aria-label="Volver al soporte">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                    </svg>
                </a>
                <div>
                    <span class="tsd-eyebrow">Mesa de servicio · Análisis</span>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Dashboard de soporte técnico</h1>
                    <p class="mt-1 text-sm text-slate-300 max-w-2xl">
                        Análisis y estadísticas de las conversaciones gestionadas por EVIA.
                    </p>
                </div>
            </div>

            <a href="{{ route('tech-support.index') }}" class="tsd-action">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Volver al chat
            </a>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-8 lg:px-12 py-8">

        {{-- KPIs --}}
        <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
            <div class="tsd-kpi red tsd-fade tsd-d1">
                <span class="accent"></span>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="tsd-kpi-label">Conversaciones</p>
                        <p class="tsd-kpi-value">{{ number_format($stats['total_conversations'] ?? 0) }}</p>
                        <p class="tsd-kpi-delta up">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            +12% este mes
                        </p>
                    </div>
                    <div class="tsd-kpi-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="tsd-kpi gold tsd-fade tsd-d2">
                <span class="accent"></span>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="tsd-kpi-label">Tasa resolución</p>
                        <p class="tsd-kpi-value">{{ number_format($stats['effectiveness_rate'] ?? 75, 1) }}<span class="unit">%</span></p>
                        <p class="tsd-kpi-delta up">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            +5% vs mes anterior
                        </p>
                    </div>
                    <div class="tsd-kpi-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="tsd-kpi slate tsd-fade tsd-d3">
                <span class="accent"></span>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="tsd-kpi-label">Escalaciones</p>
                        <p class="tsd-kpi-value">{{ number_format($stats['escalation_rate'] ?? 25, 1) }}<span class="unit">%</span></p>
                        <p class="tsd-kpi-delta down">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="transform: rotate(180deg);"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            -3% vs mes anterior
                        </p>
                    </div>
                    <div class="tsd-kpi-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="tsd-kpi black tsd-fade tsd-d4">
                <span class="accent"></span>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="tsd-kpi-label">Tiempo promedio</p>
                        <p class="tsd-kpi-value">1.8<span class="unit">min</span></p>
                        <p class="tsd-kpi-delta up">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="transform: rotate(180deg);"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                            -0.3min vs anterior
                        </p>
                    </div>
                    <div class="tsd-kpi-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        {{-- Charts: línea + dona --}}
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="tsd-panel tsd-fade tsd-d1">
                <div class="tsd-panel-head">
                    <div>
                        <p class="tsd-panel-title">Conversaciones por día</p>
                        <p class="tsd-panel-sub">Última semana</p>
                    </div>
                </div>
                <div class="tsd-panel-body">
                    <div style="height: 260px;">
                        <canvas id="dailyConversationsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="tsd-panel tsd-fade tsd-d2">
                <div class="tsd-panel-head">
                    <div>
                        <p class="tsd-panel-title">Distribución por categoría</p>
                        <p class="tsd-panel-sub">Proporción de problemas atendidos</p>
                    </div>
                </div>
                <div class="tsd-panel-body">
                    <div style="height: 260px;">
                        <canvas id="categoryDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </section>

        {{-- Charts: barras horarias + problemas comunes --}}
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="tsd-panel tsd-fade tsd-d1">
                <div class="tsd-panel-head">
                    <div>
                        <p class="tsd-panel-title">Distribución horaria</p>
                        <p class="tsd-panel-sub">Conversaciones por franja horaria</p>
                    </div>
                </div>
                <div class="tsd-panel-body">
                    <div style="height: 260px;">
                        <canvas id="hourlyDistributionChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="tsd-panel tsd-fade tsd-d2">
                <div class="tsd-panel-head">
                    <div>
                        <p class="tsd-panel-title">Problemas más comunes</p>
                        <p class="tsd-panel-sub">Top de incidencias atendidas</p>
                    </div>
                </div>
                <div class="tsd-panel-body">
                    @if(isset($stats['popular_problems']) && count($stats['popular_problems']) > 0)
                        @foreach($stats['popular_problems'] as $index => $problem)
                            @php
                                $rankClass = $index === 0 ? 'gold' : ($index === 1 ? 'red' : '');
                            @endphp
                            <div class="tsd-problem-row">
                                <div class="flex items-center min-w-0">
                                    <span class="tsd-problem-rank {{ $rankClass }}">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-900 truncate">{{ $problem->problem_type ?? 'Sin tipo' }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5 capitalize">{{ ucfirst($problem->problem_category ?? 'other') }}</p>
                                    </div>
                                </div>
                                <div class="text-right ml-3 flex-shrink-0">
                                    <p class="tsd-problem-count">{{ $problem->count ?? 0 }}</p>
                                    <p class="tsd-problem-count-sub">Casos</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="tsd-empty">
                            <div class="tsd-empty-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6m4 6V5m4 14v-9"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">No hay datos suficientes</p>
                            <p class="text-xs text-slate-500 mt-1">Los problemas comunes aparecerán cuando haya más conversaciones</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- Tabla detallada por categoría --}}
        <section class="tsd-panel tsd-fade tsd-d1 mb-8">
            <div class="tsd-panel-head">
                <div>
                    <p class="tsd-panel-title">Estadísticas por categoría</p>
                    <p class="tsd-panel-sub">Desglose detallado del rendimiento por tipo de problema</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="tsd-table">
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th>Total</th>
                            <th>Resueltos</th>
                            <th>Escalados</th>
                            <th>Tasa éxito</th>
                            <th>Tendencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($stats['category_stats']) && count($stats['category_stats']) > 0)
                            @foreach($stats['category_stats'] as $category)
                                @php
                                    $total = $category->total ?? 0;
                                    $resolved = $category->resolved ?? 0;
                                    $escalated = $category->escalated ?? 0;
                                    $successRate = $total > 0 ? ($resolved / $total) * 100 : 0;
                                    $catKey = $category->problem_category ?? 'other';
                                    $catIcons = [
                                        'computer' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                                        'internet' => 'M5 12.55a11 11 0 0114 0M1.42 9a16 16 0 0121.16 0M8.53 16.11a6 6 0 016.95 0M12 20h.01',
                                        'email' => 'M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                                        'printer' => 'M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z',
                                        'software' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
                                        'access' => 'M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z',
                                        'ai_resolve' => 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.847a4.5 4.5 0 003.09 3.09L15.75 12l-2.847.813a4.5 4.5 0 00-3.09 3.091z',
                                    ];
                                    $iconPath = $catIcons[$catKey] ?? 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';

                                    if ($successRate >= 80) { $barColor = 'gold'; }
                                    elseif ($successRate >= 50) { $barColor = ''; }
                                    else { $barColor = 'red'; }
                                @endphp
                                <tr>
                                    <td>
                                        <div class="flex items-center">
                                            <span class="tsd-cat-icon">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}"/>
                                                </svg>
                                            </span>
                                            <span class="font-semibold text-slate-900 capitalize">{{ ucfirst($catKey) }}</span>
                                        </div>
                                    </td>
                                    <td class="font-mono text-slate-900 font-semibold">{{ $total }}</td>
                                    <td class="font-mono text-emerald-700 font-semibold">{{ $resolved }}</td>
                                    <td class="font-mono" style="color: var(--eia-red); font-weight: 600;">{{ $escalated }}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span class="tsd-bar-track">
                                                <span class="tsd-bar-fill {{ $barColor }}" style="width: {{ $successRate }}%; display:block;"></span>
                                            </span>
                                            <span class="text-xs font-semibold text-slate-900 font-mono">{{ number_format($successRate, 1) }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="tsd-badge up">
                                            <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                            +5%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">
                                    <div class="tsd-empty">
                                        <div class="tsd-empty-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6m4 6V5m4 14v-9"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-slate-700">Sin datos disponibles</p>
                                        <p class="text-xs text-slate-500 mt-1">Aún no hay categorías con suficiente actividad</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Acciones rápidas --}}
        <section class="tsd-panel tsd-fade tsd-d2">
            <div class="tsd-panel-head">
                <div>
                    <p class="tsd-panel-title">Acciones rápidas</p>
                    <p class="tsd-panel-sub">Herramientas administrativas del dashboard</p>
                </div>
            </div>
            <div class="tsd-panel-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button class="tsd-quick">
                        <span class="tsd-quick-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                        <span class="tsd-quick-title">Exportar datos</span>
                        <span class="tsd-quick-sub">Descargar reporte completo en CSV</span>
                    </button>

                    <button class="tsd-quick gold">
                        <span class="tsd-quick-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6m4 6V5m4 14v-9"/>
                            </svg>
                        </span>
                        <span class="tsd-quick-title">Generar reporte</span>
                        <span class="tsd-quick-sub">Crear reporte personalizado</span>
                    </button>

                    <button class="tsd-quick red">
                        <span class="tsd-quick-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </span>
                        <span class="tsd-quick-title">Configuración</span>
                        <span class="tsd-quick-sub">Ajustar parámetros del sistema</span>
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dailyData = @json($stats['daily_stats'] ?? []);
    const categoryData = @json($stats['category_stats'] ?? []);
    const hourlyData = @json($stats['hourly_distribution'] ?? []);

    // Defaults institucionales
    Chart.defaults.font.family = "'Figtree', system-ui, -apple-system, sans-serif";
    Chart.defaults.color = '#475569';
    Chart.defaults.borderColor = '#E5E7EB';

    // ===== Conversaciones por día (línea) =====
    const dailyLabels = dailyData.length > 0
        ? dailyData.map(item => new Date(item.date).toLocaleDateString('es-MX', { day: '2-digit', month: 'short' }))
        : ['Sin datos'];
    const dailyCounts = dailyData.length > 0 ? dailyData.map(item => item.count) : [0];

    new Chart(document.getElementById('dailyConversationsChart'), {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Conversaciones',
                data: dailyCounts,
                borderColor: '#B91C1C',
                backgroundColor: 'rgba(185, 28, 28, 0.08)',
                pointBackgroundColor: '#0F1419',
                pointBorderColor: '#D97706',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 2,
                tension: 0.35,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F1419',
                    titleColor: '#FBBF24',
                    bodyColor: '#FFFFFF',
                    borderColor: '#1F2937',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#F1F5F9', borderDash: [4, 4] },
                    ticks: { font: { size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });

    // ===== Distribución por categoría (dona) =====
    const categoryLabels = categoryData.length > 0
        ? categoryData.map(item => (item.problem_category || 'Sin categoría').replace(/^./, c => c.toUpperCase()))
        : ['Sin datos'];
    const categoryCounts = categoryData.length > 0 ? categoryData.map(item => item.total || 0) : [1];

    new Chart(document.getElementById('categoryDistributionChart'), {
        type: 'doughnut',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryCounts,
                backgroundColor: [
                    '#0F1419', // negro corporativo
                    '#B91C1C', // rojo institucional
                    '#D97706', // oro
                    '#475569', // slate
                    '#7F1D1D', // rojo profundo
                    '#92400E', // ámbar profundo
                    '#94A3B8'  // gris claro
                ],
                borderColor: '#FFFFFF',
                borderWidth: 2,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '62%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 11, weight: '600' },
                        boxWidth: 10,
                        boxHeight: 10,
                        padding: 12,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: '#0F1419',
                    titleColor: '#FBBF24',
                    bodyColor: '#FFFFFF',
                    borderColor: '#1F2937',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8
                }
            }
        }
    });

    // ===== Distribución horaria (barras) =====
    const hourlyLabels = Array.from({length: 24}, (_, i) => `${String(i).padStart(2, '0')}h`);
    const hourlyCounts = Array.from({length: 24}, (_, i) => {
        const hourData = hourlyData.find(item => item.hour === i);
        return hourData ? hourData.count : 0;
    });

    new Chart(document.getElementById('hourlyDistributionChart'), {
        type: 'bar',
        data: {
            labels: hourlyLabels,
            datasets: [{
                label: 'Conversaciones',
                data: hourlyCounts,
                backgroundColor: 'rgba(217, 119, 6, 0.85)',
                hoverBackgroundColor: '#B91C1C',
                borderColor: '#0F1419',
                borderWidth: 1,
                borderRadius: 4,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F1419',
                    titleColor: '#FBBF24',
                    bodyColor: '#FFFFFF',
                    borderColor: '#1F2937',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#F1F5F9', borderDash: [4, 4] },
                    ticks: { font: { size: 11 } }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { size: 9, family: 'ui-monospace, SFMono-Regular, Menlo, monospace' },
                        maxRotation: 0,
                        autoSkip: true,
                        autoSkipPadding: 4
                    }
                }
            }
        }
    });
});
</script>
@endpush
