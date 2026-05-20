@extends('layouts.app')

@section('content')

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
        --eia-red-deep: #7F1D1D;
        --eia-gold: #D97706;
        --eia-gold-soft: #FBBF24;
    }

    .eia-bg {
        background: var(--eia-bg);
    }

    /* Hero */
    .eia-hero {
        background:
            radial-gradient(1200px 300px at 90% -50%, rgba(217, 119, 6, 0.18), transparent 60%),
            radial-gradient(900px 280px at 10% 120%, rgba(185, 28, 28, 0.22), transparent 60%),
            linear-gradient(180deg, #0F1419 0%, #1A1F26 100%);
        color: #F8FAFC;
        border: 1px solid #1F2937;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
    }

    .eia-hero::after {
        content: '';
        position: absolute;
        left: 0; right: 0; bottom: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--eia-red) 0%, var(--eia-gold) 100%);
        opacity: 0.85;
    }

    .eia-eyebrow {
        font-size: 11px;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--eia-gold-soft);
        font-weight: 600;
    }

    /* KPI */
    .eia-kpi {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 12px;
        padding: 18px 20px;
        position: relative;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }
    .eia-kpi:hover {
        border-color: #CBD5E1;
        box-shadow: 0 6px 18px -10px rgba(15, 20, 25, 0.25);
        transform: translateY(-2px);
    }
    .eia-kpi .label {
        font-size: 11px;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--eia-mute);
        font-weight: 600;
    }
    .eia-kpi .value {
        font-size: 28px;
        font-weight: 700;
        color: var(--eia-black);
        line-height: 1;
        margin-top: 8px;
    }
    .eia-kpi .delta {
        font-size: 12px;
        color: var(--eia-slate);
        margin-top: 6px;
    }
    .eia-kpi .accent {
        position: absolute;
        left: 0; top: 14px; bottom: 14px;
        width: 3px;
        border-radius: 2px;
        background: var(--eia-red);
    }
    .eia-kpi.gold .accent { background: var(--eia-gold); }
    .eia-kpi.black .accent { background: var(--eia-black); }
    .eia-kpi.slate .accent { background: var(--eia-slate); }

    /* Panels */
    .eia-panel {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 14px;
    }
    .eia-panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 22px;
        border-bottom: 1px solid var(--eia-border);
    }
    .eia-panel-title {
        font-size: 11px;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--eia-black);
    }
    .eia-panel-sub {
        font-size: 12px;
        color: var(--eia-mute);
        margin-top: 2px;
    }

    /* App tiles */
    .eia-tile {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 12px;
        padding: 22px;
        position: relative;
        transition: all .25s cubic-bezier(.4,0,.2,1);
        height: 100%;
        display: flex;
        flex-direction: column;
        gap: 14px;
        overflow: hidden;
    }
    .eia-tile::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--eia-black);
        opacity: 0;
        transition: opacity .25s ease;
    }
    .eia-tile:hover {
        border-color: #94A3B8;
        box-shadow: 0 14px 30px -18px rgba(15, 20, 25, 0.35);
        transform: translateY(-3px);
    }
    .eia-tile:hover::before { opacity: 1; }
    .eia-tile.red:hover::before { background: var(--eia-red); }
    .eia-tile.gold:hover::before { background: var(--eia-gold); }
    .eia-tile.black:hover::before { background: var(--eia-black); }

    .eia-tile-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #F1F5F9;
        color: var(--eia-black);
        border: 1px solid var(--eia-border);
    }
    .eia-tile.red  .eia-tile-icon  { background: #FEF2F2; color: var(--eia-red); border-color: #FECACA; }
    .eia-tile.gold .eia-tile-icon  { background: #FFFBEB; color: var(--eia-gold); border-color: #FDE68A; }
    .eia-tile.black .eia-tile-icon { background: #0F1419; color: #F8FAFC; border-color: #0F1419; }

    .eia-tile-title {
        font-size: 15px;
        font-weight: 600;
        color: var(--eia-black);
        letter-spacing: -0.01em;
    }
    .eia-tile-desc {
        font-size: 12.5px;
        color: var(--eia-slate);
        line-height: 1.45;
    }
    .eia-tile-cta {
        font-size: 12px;
        font-weight: 600;
        color: var(--eia-black);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: auto;
    }
    .eia-tile-cta svg {
        transition: transform .25s ease;
    }
    .eia-tile:hover .eia-tile-cta svg {
        transform: translateX(3px);
    }

    .eia-tile.locked {
        background: #F8FAFC;
        border-style: dashed;
        cursor: not-allowed;
    }
    .eia-tile.locked:hover {
        transform: none;
        box-shadow: none;
        border-color: var(--eia-border);
    }
    .eia-tile.locked .eia-tile-icon {
        background: #E2E8F0;
        color: #94A3B8;
        border-color: #E2E8F0;
    }
    .eia-tile.locked .eia-tile-title { color: #64748B; }
    .eia-tile.locked .eia-tile-desc { color: #94A3B8; }

    .eia-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 3px 8px;
        border-radius: 999px;
        background: #F1F5F9;
        color: var(--eia-slate);
        border: 1px solid var(--eia-border);
    }
    .eia-badge.live { background: #ECFDF5; color: #047857; border-color: #A7F3D0; }
    .eia-badge.gold { background: #FFFBEB; color: #92400E; border-color: #FDE68A; }
    .eia-badge.red { background: #FEF2F2; color: #991B1B; border-color: #FECACA; }

    .eia-status-dot {
        width: 6px; height: 6px; border-radius: 50%;
        display: inline-block;
        background: #10B981;
    }

    /* Schedule rows */
    .eia-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        border-top: 1px solid var(--eia-border);
    }
    .eia-row:first-child { border-top: 0; }
    .eia-row .day {
        font-size: 13px;
        font-weight: 600;
        color: var(--eia-black);
    }
    .eia-row .sub {
        font-size: 11px;
        color: var(--eia-mute);
        letter-spacing: 0.04em;
    }
    .eia-row .time {
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-size: 12px;
        color: var(--eia-slate);
        background: #F8FAFC;
        border: 1px solid var(--eia-border);
        border-radius: 6px;
        padding: 4px 8px;
    }

    /* News */
    .eia-news-item {
        padding: 14px 18px;
        display: flex;
        gap: 12px;
        border-top: 1px solid var(--eia-border);
    }
    .eia-news-item:first-child { border-top: 0; }
    .eia-news-stripe {
        width: 3px;
        border-radius: 2px;
        background: var(--eia-red);
        flex-shrink: 0;
    }
    .eia-news-item.gold .eia-news-stripe { background: var(--eia-gold); }
    .eia-news-item.black .eia-news-stripe { background: var(--eia-black); }

    .eia-news-title {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--eia-black);
    }
    .eia-news-meta {
        font-size: 11px;
        color: var(--eia-mute);
        margin-top: 2px;
        letter-spacing: 0.02em;
    }

    /* Section header */
    .eia-section-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--eia-mute);
    }

    /* Subtle entrance */
    .eia-fade {
        animation: eiaFade .55s ease-out both;
    }
    .eia-d1 { animation-delay: .05s; }
    .eia-d2 { animation-delay: .12s; }
    .eia-d3 { animation-delay: .2s; }
    .eia-d4 { animation-delay: .28s; }

    @keyframes eiaFade {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="eia-bg min-h-screen px-4 sm:px-6 lg:px-10 pt-6 pb-12">

    {{-- HERO --}}
    <section class="eia-hero p-7 sm:p-9 mb-6 eia-fade">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
            <div>
                <span class="eia-eyebrow">Panel de Aplicaciones</span>
                <h1 class="mt-3 text-3xl sm:text-4xl font-semibold tracking-tight">
                    Bienvenido, {{ explode(' ', auth()->user()->name)[0] }}.
                </h1>
                <p class="mt-2 text-sm sm:text-base text-slate-300 max-w-2xl">
                    Plataforma corporativa de inteligencia: conocimiento, análisis y soporte unificado para la operación.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-slate-300">
                    <span class="eia-status-dot"></span> Sistemas operativos
                </span>
                <span class="hidden sm:inline text-slate-500">|</span>
                <span class="text-xs uppercase tracking-widest text-slate-400">
                    {{ now()->locale('es')->translatedFormat('l, d \\d\\e F · Y') }}
                </span>
            </div>
        </div>

        {{-- KPI strip embedded en hero --}}
        <div class="mt-7 grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-white/5 border border-white/10 rounded-xl p-4 backdrop-blur-sm">
                <p class="text-[10px] tracking-[0.2em] uppercase text-slate-400 font-semibold">Noticias</p>
                <p class="text-2xl font-semibold mt-1">24</p>
                <p class="text-[11px] text-slate-400 mt-1">Publicadas esta semana</p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-xl p-4 backdrop-blur-sm">
                <p class="text-[10px] tracking-[0.2em] uppercase text-slate-400 font-semibold">Recomendaciones</p>
                <p class="text-2xl font-semibold mt-1">17</p>
                <p class="text-[11px] text-slate-400 mt-1">Activas para tu perfil</p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-xl p-4 backdrop-blur-sm">
                <p class="text-[10px] tracking-[0.2em] uppercase text-slate-400 font-semibold">Consultas IA</p>
                <p class="text-2xl font-semibold mt-1">128</p>
                <p class="text-[11px] text-slate-400 mt-1">Mes actual</p>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-xl p-4 backdrop-blur-sm">
                <p class="text-[10px] tracking-[0.2em] uppercase text-slate-400 font-semibold">Próxima sesión</p>
                <p class="text-2xl font-semibold mt-1">
                    @php
                        $today = now();
                        $daysOfWeek = ['Monday', 'Wednesday', 'Friday'];
                        $nextSession = null;
                        foreach ($daysOfWeek as $day) {
                            $nextDate = $today->copy()->next($day);
                            if (!$nextSession || $nextDate->lt($nextSession)) {
                                $nextSession = $nextDate;
                            }
                        }
                    @endphp
                    {{ $nextSession ? $nextSession->format('d/m') : '—' }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1">Capacitación IA · 12:00</p>
            </div>
        </div>
    </section>

    {{-- MAIN GRID --}}
    <div class="grid grid-cols-1 xl:grid-cols-[1fr_360px] gap-6">

        {{-- LEFT: Aplicaciones --}}
        <section class="eia-panel eia-fade eia-d1">
            <div class="eia-panel-head">
                <div>
                    <p class="eia-panel-title">Centro de aplicaciones</p>
                    <p class="eia-panel-sub">Herramientas inteligentes habilitadas para tu rol</p>
                </div>
                <span class="eia-badge">9 módulos</span>
            </div>

            <div class="p-5 sm:p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                {{-- Buscador --}}
                <a href="{{ route('chat.index') }}" class="eia-tile black eia-fade eia-d1">
                    <div class="flex items-start justify-between">
                        <div class="eia-tile-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/>
                            </svg>
                        </div>
                        <span class="eia-badge live"><span class="eia-status-dot"></span>Activo</span>
                    </div>
                    <div>
                        <h3 class="eia-tile-title">Buscador Inteligente</h3>
                        <p class="eia-tile-desc">Consulta información corporativa con IA generativa.</p>
                    </div>
                    <span class="eia-tile-cta">Abrir módulo
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </a>

                {{-- Recomendaciones --}}
                <a href="{{ route('recommendations.index') }}" class="eia-tile gold eia-fade eia-d2">
                    <div class="flex items-start justify-between">
                        <div class="eia-tile-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.847a4.5 4.5 0 003.09 3.09L15.75 12l-2.847.813a4.5 4.5 0 00-3.09 3.091z"/>
                            </svg>
                        </div>
                        <span class="eia-badge gold">Personalizado</span>
                    </div>
                    <div>
                        <h3 class="eia-tile-title">Recomendaciones</h3>
                        <p class="eia-tile-desc">Sugerencias relevantes basadas en tu perfil y actividad.</p>
                    </div>
                    <span class="eia-tile-cta">Ver recomendaciones
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </a>

                {{-- Noticias --}}
                <a href="{{ route('news.index') }}" class="eia-tile red eia-fade eia-d3">
                    <div class="flex items-start justify-between">
                        <div class="eia-tile-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 10h6M7 14h10M7 18h7"/>
                            </svg>
                        </div>
                        <span class="eia-badge red">24 hoy</span>
                    </div>
                    <div>
                        <h3 class="eia-tile-title">Noticias</h3>
                        <p class="eia-tile-desc">Industria, mercado energético y novedades del sector.</p>
                    </div>
                    <span class="eia-tile-cta">Leer feed
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </a>

                {{-- Documentos --}}
                <a href="{{ route('document-bot.index') }}" class="eia-tile black eia-fade eia-d1">
                    <div class="flex items-start justify-between">
                        <div class="eia-tile-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9V5a2 2 0 00-2-2h-7l-5 5v11a2 2 0 002 2h6"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 3v4a2 2 0 002 2h4M16 17h6M19 14v6"/>
                            </svg>
                        </div>
                        <span class="eia-badge">RAG</span>
                    </div>
                    <div>
                        <h3 class="eia-tile-title">Documentos</h3>
                        <p class="eia-tile-desc">Búsqueda semántica sobre el repositorio corporativo.</p>
                    </div>
                    <span class="eia-tile-cta">Buscar documentos
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </a>

                {{-- Soporte Técnico --}}
                <a href="{{ route('tech-support.index') }}" class="eia-tile red eia-fade eia-d2">
                    <div class="flex items-start justify-between">
                        <div class="eia-tile-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h3m-3 12h3M5 12h14M7 6h.01M7 18h.01M5 6a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6z"/>
                            </svg>
                        </div>
                        <span class="eia-badge live"><span class="eia-status-dot"></span>24/7</span>
                    </div>
                    <div>
                        <h3 class="eia-tile-title">Soporte Técnico</h3>
                        <p class="eia-tile-desc">Diagnóstico asistido por IA y atención de incidencias.</p>
                    </div>
                    <span class="eia-tile-cta">Solicitar soporte
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </a>

                {{-- Perfil --}}
                <a href="{{ route('profile.index') }}" class="eia-tile eia-fade eia-d3">
                    <div class="flex items-start justify-between">
                        <div class="eia-tile-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 14a4 4 0 10-8 0M12 11a3 3 0 100-6 3 3 0 000 6zM4 21a8 8 0 0116 0"/>
                            </svg>
                        </div>
                        <span class="eia-badge">Cuenta</span>
                    </div>
                    <div>
                        <h3 class="eia-tile-title">Mi Perfil</h3>
                        <p class="eia-tile-desc">Preferencias personales y configuración de la cuenta.</p>
                    </div>
                    <span class="eia-tile-cta">Gestionar perfil
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </span>
                </a>

                {{-- Próximamente x3 --}}
                @for ($i = 0; $i < 3; $i++)
                <div class="eia-tile locked eia-fade eia-d{{ ($i % 3) + 2 }}">
                    <div class="flex items-start justify-between">
                        <div class="eia-tile-icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="eia-badge">Próximo</span>
                    </div>
                    <div>
                        <h3 class="eia-tile-title">En desarrollo</h3>
                        <p class="eia-tile-desc">Nuevo módulo corporativo será habilitado próximamente.</p>
                    </div>
                    <span class="eia-tile-cta" style="color:#94A3B8;">No disponible</span>
                </div>
                @endfor

            </div>
        </section>

        {{-- RIGHT: Sidebar --}}
        <aside class="space-y-6">

            {{-- Capacitaciones --}}
            <section class="eia-panel eia-fade eia-d2">
                <div class="eia-panel-head">
                    <div>
                        <p class="eia-panel-title">Capacitaciones IA</p>
                        <p class="eia-panel-sub">Agenda semanal · 12:00 a 13:30</p>
                    </div>
                    <span class="eia-badge gold">Semanal</span>
                </div>
                <div>
                    <div class="eia-row">
                        <div>
                            <p class="day">Lunes</p>
                            <p class="sub">Sesión de capacitación</p>
                        </div>
                        <span class="time">12:00 – 13:30</span>
                    </div>
                    <div class="eia-row">
                        <div>
                            <p class="day">Miércoles</p>
                            <p class="sub">Sesión de capacitación</p>
                        </div>
                        <span class="time">12:00 – 13:30</span>
                    </div>
                    <div class="eia-row">
                        <div>
                            <p class="day">Viernes</p>
                            <p class="sub">Sesión de capacitación</p>
                        </div>
                        <span class="time">12:00 – 13:30</span>
                    </div>
                </div>
                <div class="px-5 py-4 border-t border-slate-200 bg-slate-50 rounded-b-[14px]">
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] uppercase tracking-widest text-slate-500 font-semibold">Próxima sesión</p>
                        <p class="text-sm font-semibold text-slate-800">
                            {{ $nextSession ? $nextSession->locale('es')->translatedFormat('l d/m') : 'Por definir' }}
                        </p>
                    </div>
                </div>
            </section>

            {{-- Novedades --}}
            <section class="eia-panel eia-fade eia-d3">
                <div class="eia-panel-head">
                    <div>
                        <p class="eia-panel-title">Novedades</p>
                        <p class="eia-panel-sub">Actualizaciones recientes</p>
                    </div>
                </div>
                <div>
                    <div class="eia-news-item">
                        <div class="eia-news-stripe"></div>
                        <div class="flex-1">
                            <p class="eia-news-title">Sistema actualizado</p>
                            <p class="text-[12.5px] text-slate-600 mt-1">Nuevas funcionalidades de búsqueda semántica disponibles.</p>
                            <p class="eia-news-meta">Hace 2 horas</p>
                        </div>
                    </div>
                    <div class="eia-news-item gold">
                        <div class="eia-news-stripe"></div>
                        <div class="flex-1">
                            <p class="eia-news-title">Mejoras de rendimiento</p>
                            <p class="text-[12.5px] text-slate-600 mt-1">Optimización en tiempos de respuesta del módulo de chat.</p>
                            <p class="eia-news-meta">Ayer</p>
                        </div>
                    </div>
                    <div class="eia-news-item black">
                        <div class="eia-news-stripe"></div>
                        <div class="flex-1">
                            <p class="eia-news-title">Nuevo módulo RAG</p>
                            <p class="text-[12.5px] text-slate-600 mt-1">Consulta documental con razonamiento profundo habilitado.</p>
                            <p class="eia-news-meta">Esta semana</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Atajo / soporte --}}
            <section class="eia-panel eia-fade eia-d4" style="background: linear-gradient(180deg, #0F1419 0%, #1F2937 100%); color: #F8FAFC; border-color: #1F2937;">
                <div class="p-5">
                    <p class="text-[10px] tracking-[0.22em] uppercase font-semibold" style="color: var(--eia-gold-soft);">¿Necesitas ayuda?</p>
                    <h3 class="text-lg font-semibold mt-2">Asistente corporativo</h3>
                    <p class="text-sm text-slate-300 mt-1">Habla con el bot corporativo para resolver dudas inmediatas sobre procesos, empleados o documentación.</p>
                    <a href="{{ route('chat.index') }}" class="inline-flex items-center gap-2 mt-4 text-sm font-semibold" style="color: #FFFFFF; border-bottom: 1px solid var(--eia-gold); padding-bottom: 2px;">
                        Iniciar conversación
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </section>

        </aside>
    </div>
</div>

@endsection
