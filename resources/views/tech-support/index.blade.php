@extends('layouts.app')

@section('title', 'Soporte Técnico')

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
    .ts-hero {
        background:
            radial-gradient(1000px 280px at 92% -40%, rgba(217, 119, 6, 0.18), transparent 60%),
            radial-gradient(800px 260px at 5% 130%, rgba(185, 28, 28, 0.22), transparent 60%),
            linear-gradient(180deg, #0F1419 0%, #1A1F26 100%);
        color: #F8FAFC;
        border-bottom: 1px solid var(--eia-graphite);
        position: relative;
    }
    .ts-hero::after {
        content: '';
        position: absolute;
        left: 0; right: 0; bottom: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--eia-red) 0%, var(--eia-gold) 100%);
        opacity: 0.85;
    }
    .ts-back {
        width: 38px; height: 38px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        background: rgba(255, 255, 255, 0.04);
        display: inline-flex; align-items: center; justify-content: center;
        color: #E2E8F0;
        transition: all .2s ease;
    }
    .ts-back:hover {
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
    .ts-action {
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
        letter-spacing: 0.02em;
        transition: all .2s ease;
    }
    .ts-action:hover {
        background: rgba(217, 119, 6, 0.15);
        border-color: var(--eia-gold);
    }

    /* KPI */
    .ts-kpi {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 12px;
        padding: 18px 20px;
        position: relative;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        overflow: hidden;
    }
    .ts-kpi:hover {
        border-color: #94A3B8;
        box-shadow: 0 12px 28px -16px rgba(15, 20, 25, 0.3);
        transform: translateY(-2px);
    }
    .ts-kpi .accent {
        position: absolute;
        left: 0; top: 16px; bottom: 16px;
        width: 3px;
        border-radius: 2px;
        background: var(--eia-red);
    }
    .ts-kpi.gold .accent { background: var(--eia-gold); }
    .ts-kpi.black .accent { background: var(--eia-black); }
    .ts-kpi-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: #FEF2F2;
        color: var(--eia-red);
        border: 1px solid #FECACA;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .ts-kpi.gold .ts-kpi-icon { background: #FFFBEB; color: var(--eia-gold); border-color: #FDE68A; }
    .ts-kpi.black .ts-kpi-icon { background: #0F1419; color: #F8FAFC; border-color: #0F1419; }
    .ts-kpi-label {
        font-size: 11px;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--eia-mute);
        font-weight: 600;
    }
    .ts-kpi-value {
        font-size: 26px;
        font-weight: 700;
        color: var(--eia-black);
        line-height: 1;
        margin-top: 4px;
    }

    /* Panel */
    .ts-panel {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 14px;
        overflow: hidden;
    }
    .ts-panel-head {
        padding: 18px 22px;
        border-bottom: 1px solid var(--eia-border);
        background: #FAFAFB;
    }
    .ts-panel-head.dark {
        background: linear-gradient(180deg, #0F1419 0%, #1A1F26 100%);
        color: #F8FAFC;
        border-bottom: 1px solid var(--eia-graphite);
    }
    .ts-panel-head.dark .ts-panel-title { color: #FFFFFF; }
    .ts-panel-head.dark .ts-panel-sub { color: #94A3B8; }

    .ts-panel-title {
        font-size: 11px;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--eia-black);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .ts-panel-sub {
        font-size: 12.5px;
        color: var(--eia-mute);
        margin-top: 4px;
    }
    .ts-panel-body { padding: 20px 22px; }

    /* Inputs */
    .ts-search-wrap {
        position: relative;
    }
    .ts-search-wrap svg.search-icon {
        position: absolute;
        left: 12px; top: 50%;
        transform: translateY(-50%);
        color: var(--eia-mute);
    }
    .ts-input {
        width: 100%;
        padding: 11px 14px 11px 38px;
        border: 1px solid var(--eia-border);
        border-radius: 10px;
        background: #FFFFFF;
        color: var(--eia-black);
        font-size: 13.5px;
        outline: none;
        transition: all .2s ease;
    }
    .ts-input:focus {
        border-color: var(--eia-black);
        box-shadow: 0 0 0 3px rgba(15, 20, 25, 0.08);
    }
    .ts-input::placeholder { color: #94A3B8; }
    .ts-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--eia-mute);
        margin-bottom: 8px;
    }

    /* === Override de clases que el JS añade dinámicamente === */
    /* Tags de empleados (JS usa bg-blue-*) */
    .employee-tag-btn {
        background: #F1F5F9 !important;
        color: var(--eia-slate) !important;
        border: 1px solid var(--eia-border) !important;
        font-weight: 600;
        font-size: 12px;
        padding: 6px 12px !important;
        border-radius: 999px !important;
        transition: all .15s ease;
    }
    .employee-tag-btn:hover {
        background: #FFFFFF !important;
        border-color: var(--eia-black) !important;
        color: var(--eia-black) !important;
    }
    .employee-tag-btn.bg-blue-300,
    .employee-tag-btn.bg-blue-400 {
        background: var(--eia-black) !important;
        color: #FFFFFF !important;
        border-color: var(--eia-black) !important;
    }
    .employee-tag-btn.text-blue-700,
    .employee-tag-btn.text-blue-800,
    .employee-tag-btn.text-blue-900 { color: inherit !important; }

    /* Botones categoría documentos (JS usa bg-green-*) */
    .document-category-btn {
        background: #F8FAFC !important;
        border: 1px solid var(--eia-border) !important;
        border-radius: 10px !important;
        padding: 12px 14px !important;
        transition: all .2s ease;
        color: var(--eia-black);
        position: relative;
        overflow: hidden;
    }
    .document-category-btn::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--eia-gold);
        opacity: 0;
        transition: opacity .2s ease;
    }
    .document-category-btn:hover {
        background: #FFFFFF !important;
        border-color: #94A3B8 !important;
    }
    .document-category-btn:hover::before { opacity: 1; }
    .document-category-btn.bg-green-200,
    .document-category-btn.border-green-400 {
        background: #FFFBEB !important;
        border-color: var(--eia-gold) !important;
    }
    .document-category-btn.bg-green-200::before { opacity: 1; }
    .document-category-btn i { color: var(--eia-slate) !important; }
    .document-category-btn.bg-green-200 i,
    .document-category-btn:hover i { color: var(--eia-gold) !important; }
    .document-category-btn span { color: inherit !important; font-weight: 600; font-size: 13px; }

    /* Chat container */
    .ts-chat {
        height: 480px;
        overflow-y: auto;
        padding: 24px;
        background: linear-gradient(180deg, #F8FAFC 0%, #FFFFFF 100%);
        border-bottom: 1px solid var(--eia-border);
    }
    .ts-chat::-webkit-scrollbar { width: 6px; }
    .ts-chat::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }

    /* Chat input area */
    .ts-chat-actions {
        padding: 16px 22px;
        background: #FFFFFF;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    /* Botones de chat */
    .ts-btn-primary {
        background: var(--eia-black);
        color: #FFFFFF;
        font-size: 12.5px;
        font-weight: 600;
        padding: 9px 16px;
        border-radius: 8px;
        border: 1px solid var(--eia-black);
        transition: all .2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .ts-btn-primary:hover {
        background: #1F2937;
        border-color: var(--eia-gold);
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15);
    }
    .ts-btn-ghost {
        background: #F8FAFC;
        color: var(--eia-slate);
        font-size: 12.5px;
        font-weight: 600;
        padding: 9px 16px;
        border-radius: 8px;
        border: 1px solid var(--eia-border);
        transition: all .2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .ts-btn-ghost:hover {
        background: #FFFFFF;
        color: var(--eia-black);
        border-color: var(--eia-black);
    }
    .ts-btn-danger {
        background: #FFFFFF;
        color: var(--eia-red);
        font-size: 12.5px;
        font-weight: 600;
        padding: 9px 16px;
        border-radius: 8px;
        border: 1px solid #FECACA;
        transition: all .2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .ts-btn-danger:hover {
        background: var(--eia-red);
        color: #FFFFFF;
        border-color: var(--eia-red);
    }
    .ts-btn-success {
        background: #FFFFFF;
        color: #047857;
        font-size: 12.5px;
        font-weight: 600;
        padding: 9px 16px;
        border-radius: 8px;
        border: 1px solid #A7F3D0;
        transition: all .2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .ts-btn-success:hover {
        background: #047857;
        color: #FFFFFF;
        border-color: #047857;
    }

    /* Sidebar acciones rápidas */
    .ts-quick-action {
        display: block;
        width: 100%;
        text-align: left;
        padding: 14px 16px;
        background: #FFFFFF;
        border: 1px solid var(--eia-border);
        border-radius: 10px;
        transition: all .2s ease;
        position: relative;
        overflow: hidden;
    }
    .ts-quick-action::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--eia-black);
        opacity: 0;
        transition: opacity .2s ease;
    }
    .ts-quick-action:hover {
        background: #F8FAFC;
        border-color: #94A3B8;
        transform: translateX(2px);
    }
    .ts-quick-action:hover::before { opacity: 1; }
    .ts-quick-action.red:hover::before { background: var(--eia-red); }
    .ts-quick-action.gold:hover::before { background: var(--eia-gold); }
    .ts-quick-action .ts-qa-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: #F1F5F9;
        color: var(--eia-slate);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        vertical-align: middle;
        border: 1px solid var(--eia-border);
    }
    .ts-quick-action.red .ts-qa-icon { background: #FEF2F2; color: var(--eia-red); border-color: #FECACA; }
    .ts-quick-action.gold .ts-qa-icon { background: #FFFBEB; color: var(--eia-gold); border-color: #FDE68A; }

    /* Lista de problemas comunes */
    .ts-pop-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-top: 1px solid var(--eia-border);
        font-size: 13px;
    }
    .ts-pop-row:first-child { border-top: 0; }
    .ts-pop-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 20px;
        padding: 0 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        background: #FEF2F2;
        color: var(--eia-red);
        border: 1px solid #FECACA;
    }

    /* Horarios */
    .ts-hour-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-top: 1px solid var(--eia-border);
        font-size: 13px;
    }
    .ts-hour-row:first-child { border-top: 0; }
    .ts-hour-row .label { color: var(--eia-slate); }
    .ts-hour-row .value { font-weight: 600; color: var(--eia-black); font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: 12px; }
    .ts-hour-row .value.alert { color: var(--eia-red); }

    /* Solution card override (renderizado por JS, mantiene clases bg-blue-100 etc) */
    .ts-chat .bg-blue-100 {
        background: #F1F5F9 !important;
        color: var(--eia-black) !important;
        border: 1px solid var(--eia-border) !important;
    }
    .ts-chat .bg-blue-100 i { color: var(--eia-black) !important; }
    .ts-chat .text-blue-600 { color: var(--eia-black) !important; }
    .ts-chat .text-blue-800 { color: var(--eia-black) !important; }
    .ts-chat .bg-blue-500 {
        background: var(--eia-black) !important;
        border: 1px solid var(--eia-black) !important;
    }
    .ts-chat .border-blue-300,
    .ts-chat .border-blue-200 { border-color: var(--eia-border) !important; }

    /* Spinner */
    .eia-spinner {
        width: 22px; height: 22px;
        border: 2.5px solid #E2E8F0;
        border-top-color: var(--eia-black);
        border-right-color: var(--eia-gold);
        border-radius: 50%;
        animation: spin .8s linear infinite;
        display: inline-block;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

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
    <section class="ts-hero px-4 sm:px-8 lg:px-12 py-10">
        <div class="max-w-7xl mx-auto flex items-start justify-between gap-6 flex-wrap">
            <div class="flex items-center gap-4">
                <a href="/" class="ts-back" aria-label="Volver al inicio">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                    </svg>
                </a>
                <div>
                    <span class="eia-eyebrow">Mesa de servicio · IT</span>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Soporte técnico</h1>
                    <p class="mt-1 text-sm text-slate-300 max-w-2xl">
                        Diagnóstico asistido por IA y acceso al directorio corporativo.
                    </p>
                </div>
            </div>

            <a href="{{ route('tech-support.dashboard') }}" class="ts-action">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                </svg>
                Dashboard de soporte
            </a>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-8 lg:px-12 py-8">

        {{-- KPIs --}}
        <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
            <div class="ts-kpi eia-fade eia-d1">
                <span class="accent"></span>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="ts-kpi-label">Conversaciones</p>
                        <p class="ts-kpi-value">{{ $stats['total_conversations'] ?? 0 }}</p>
                        <p class="text-xs text-slate-500 mt-1">Total acumulado</p>
                    </div>
                    <div class="ts-kpi-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="ts-kpi gold eia-fade eia-d2">
                <span class="accent"></span>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="ts-kpi-label">Resueltos hoy</p>
                        <p class="ts-kpi-value">{{ $stats['resolved_today'] ?? 0 }}</p>
                        <p class="text-xs text-slate-500 mt-1">Tickets cerrados</p>
                    </div>
                    <div class="ts-kpi-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="ts-kpi eia-fade eia-d3">
                <span class="accent"></span>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="ts-kpi-label">Escalados hoy</p>
                        <p class="ts-kpi-value">{{ $stats['escalated_today'] ?? 0 }}</p>
                        <p class="text-xs text-slate-500 mt-1">Derivados a IT</p>
                    </div>
                    <div class="ts-kpi-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="ts-kpi black eia-fade eia-d4">
                <span class="accent"></span>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="ts-kpi-label">Tiempo respuesta</p>
                        <p class="ts-kpi-value">&lt; 2 min</p>
                        <p class="text-xs text-slate-500 mt-1">Promedio</p>
                    </div>
                    <div class="ts-kpi-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        {{-- Sección "Directorio de empleados + Documentos corporativos" deshabilitada por solicitud.
             El asistente EVIA toma protagonismo justo debajo de los KPIs. --}}

        {{-- Chat + Sidebar --}}
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Chat principal --}}
            <div class="lg:col-span-2">
                <div class="ts-panel eia-fade eia-d1">
                    <div class="ts-panel-head dark" style="display: flex; align-items: center; gap: 14px;">
                        <img src="{{ asset('storage/img/persona_logo.png') }}" alt="EVIA"
                             style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; background: linear-gradient(135deg, #0F1419 0%, #1F2937 100%); border: 2px solid var(--eia-gold); box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.25); flex-shrink: 0;">
                        <div>
                            <p class="ts-panel-title" style="color: var(--eia-gold-soft); letter-spacing: 0.18em;">
                                EVIA · Asistente de Soporte
                            </p>
                            <p class="ts-panel-sub" style="color: #94A3B8;">Cuéntame qué pasa, te guío paso a paso para resolverlo.</p>
                        </div>
                    </div>

                    <div id="tech-support-chat" class="ts-chat">
                        <div class="flex items-start mb-4">
                            <img src="{{ asset('storage/img/persona_logo.png') }}" alt="EVIA"
                                 class="mr-3 rounded-full flex-shrink-0"
                                 style="width: 40px; height: 40px; object-fit: cover; background: linear-gradient(135deg, #0F1419 0%, #1F2937 100%); border: 1.5px solid var(--eia-gold); box-shadow: 0 0 0 2px rgba(217, 119, 6, 0.15);">
                            <div class="bg-white rounded-lg p-4 shadow-sm max-w-md" style="border: 1px solid var(--eia-border);">
                                <p class="text-[10px] uppercase tracking-[0.18em] font-bold mb-2" style="color: var(--eia-red);">EVIA · Asistente</p>
                                <p class="text-slate-800">
                                    ¡Hola! Soy EVIA, tu asistente de soporte. Estoy aquí para ayudarte a resolver problemas con tu equipo, internet, correo, impresora o cualquier aplicación corporativa.
                                </p>
                                <p class="text-slate-800 mt-2">
                                    <strong>¿Con qué puedo ayudarte hoy?</strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="ts-chat-actions">
                        <button id="main-menu" class="ts-btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75L12 3l9 6.75M4.5 9.75v9A1.5 1.5 0 006 20.25h12a1.5 1.5 0 001.5-1.5v-9"/>
                            </svg>
                            Menú principal
                        </button>
                        <button id="restart-chat" class="ts-btn-ghost">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v6h6M20 20v-6h-6M4 10a8 8 0 0114-5.5M20 14a8 8 0 01-14 5.5"/>
                            </svg>
                            Nuevo problema
                        </button>
                        <button id="escalate-to-it" class="ts-btn-danger" style="display: none;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Contactar IT
                        </button>
                        <button id="mark-resolved" class="ts-btn-success" style="display: none;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Problema resuelto
                        </button>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Accesos rápidos --}}
                <div class="ts-panel eia-fade eia-d2">
                    <div class="ts-panel-head">
                        <p class="ts-panel-title">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Accesos rápidos
                        </p>
                    </div>
                    <div class="ts-panel-body space-y-2">
                        <button class="ts-quick-action" onclick="quickAction('restart_computer')">
                            <div class="flex items-center">
                                <span class="ts-qa-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.36 6.64a9 9 0 11-12.73 0M12 2v10"/>
                                    </svg>
                                </span>
                                <span class="inline-block align-middle">
                                    <span class="font-semibold text-slate-900 text-sm">Reiniciar computadora</span>
                                    <p class="text-xs text-slate-500 mt-0.5">Guía paso a paso para reiniciar</p>
                                </span>
                            </div>
                        </button>
                        <button class="ts-quick-action gold" onclick="quickAction('check_internet')">
                            <div class="flex items-center">
                                <span class="ts-qa-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12.55a11 11 0 0114 0M1.42 9a16 16 0 0121.16 0M8.53 16.11a6 6 0 016.95 0M12 20h.01"/>
                                    </svg>
                                </span>
                                <span class="inline-block align-middle">
                                    <span class="font-semibold text-slate-900 text-sm">Verificar internet</span>
                                    <p class="text-xs text-slate-500 mt-0.5">Diagnosticar problemas de conexión</p>
                                </span>
                            </div>
                        </button>
                        <button class="ts-quick-action red" onclick="quickAction('contact_it')">
                            <div class="flex items-center">
                                <span class="ts-qa-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 010 1.414l-1.586 1.586a16 16 0 006.586 6.586l1.586-1.586a1 1 0 011.414 0l2.414 2.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-1C9.715 21 3 14.285 3 6V5z"/>
                                    </svg>
                                </span>
                                <span class="inline-block align-middle">
                                    <span class="font-semibold text-slate-900 text-sm">Contactar IT</span>
                                    <p class="text-xs text-slate-500 mt-0.5">Hablar directamente con soporte</p>
                                </span>
                            </div>
                        </button>
                    </div>
                </div>

                {{-- Problemas comunes --}}
                <div class="ts-panel eia-fade eia-d3">
                    <div class="ts-panel-head">
                        <p class="ts-panel-title">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18M3 6h18M3 18h18"/>
                            </svg>
                            Problemas comunes
                        </p>
                    </div>
                    <div class="ts-panel-body">
                        @if(isset($stats['categories_popular']) && count($stats['categories_popular']) > 0)
                            @foreach($stats['categories_popular'] as $category)
                                <div class="ts-pop-row">
                                    <span class="capitalize text-slate-700">{{ ucfirst($category->problem_category) }}</span>
                                    <span class="ts-pop-count">{{ $category->count }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-slate-500 text-sm py-2">No hay datos disponibles aún.</p>
                        @endif
                    </div>
                </div>

                {{-- Equipo de IT --}}
                <div class="ts-panel eia-fade eia-d4">
                    <div class="ts-panel-head">
                        <p class="ts-panel-title">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 14a4 4 0 10-8 0M12 11a3 3 0 100-6 3 3 0 000 6zM4 21a8 8 0 0116 0"/>
                            </svg>
                            Equipo de IT
                        </p>
                    </div>
                    <div class="ts-panel-body" style="padding: 0;">
                        {{-- Oscar --}}
                        <div style="padding: 14px 22px; border-bottom: 1px solid var(--eia-border);">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #0F1419 0%, #1F2937 100%); color: var(--eia-gold-soft); display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; letter-spacing: 0.04em; flex-shrink: 0;">OC</div>
                                <div style="min-width: 0; flex: 1;">
                                    <p style="font-size: 13px; font-weight: 600; color: var(--eia-black); line-height: 1.2;">Oscar Chávez Rosales</p>
                                    <p style="font-size: 10.5px; color: var(--eia-mute); margin-top: 1px;">Soporte técnico</p>
                                </div>
                            </div>
                            <div style="margin-top: 10px; display: flex; flex-direction: column; gap: 4px;">
                                <a href="mailto:ochavez@gptservices.com" style="display: inline-flex; align-items: center; gap: 6px; font-size: 11.5px; color: var(--eia-red); font-weight: 500; text-decoration: none; transition: color .15s;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    ochavez@gptservices.com
                                </a>
                                <a href="tel:5610071384" style="display: inline-flex; align-items: center; gap: 6px; font-size: 11.5px; color: var(--eia-gold); font-weight: 600; text-decoration: none; font-family: ui-monospace, SFMono-Regular, Menlo, monospace;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 010 1.414l-1.586 1.586a16 16 0 006.586 6.586l1.586-1.586a1 1 0 011.414 0l2.414 2.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-1C9.715 21 3 14.285 3 6V5z"/></svg>
                                    56 1007 1384
                                </a>
                            </div>
                        </div>

                        {{-- Alan --}}
                        <div style="padding: 14px 22px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #0F1419 0%, #1F2937 100%); color: var(--eia-gold-soft); display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; letter-spacing: 0.04em; flex-shrink: 0;">AH</div>
                                <div style="min-width: 0; flex: 1;">
                                    <p style="font-size: 13px; font-weight: 600; color: var(--eia-black); line-height: 1.2;">Alan E. Hernández Mendoza</p>
                                    <p style="font-size: 10.5px; color: var(--eia-mute); margin-top: 1px;">Soporte técnico</p>
                                </div>
                            </div>
                            <div style="margin-top: 10px; display: flex; flex-direction: column; gap: 4px;">
                                <a href="mailto:ahernandezm@gptservices.com" style="display: inline-flex; align-items: center; gap: 6px; font-size: 11.5px; color: var(--eia-red); font-weight: 500; text-decoration: none;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    ahernandezm@gptservices.com
                                </a>
                                <a href="tel:5517989865" style="display: inline-flex; align-items: center; gap: 6px; font-size: 11.5px; color: var(--eia-gold); font-weight: 600; text-decoration: none; font-family: ui-monospace, SFMono-Regular, Menlo, monospace;">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 010 1.414l-1.586 1.586a16 16 0 006.586 6.586l1.586-1.586a1 1 0 011.414 0l2.414 2.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-1C9.715 21 3 14.285 3 6V5z"/></svg>
                                    55 1798 9865
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Horarios --}}
                <div class="ts-panel eia-fade eia-d4">
                    <div class="ts-panel-head">
                        <p class="ts-panel-title">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Horarios de soporte
                        </p>
                    </div>
                    <div class="ts-panel-body">
                        <div class="ts-hour-row">
                            <span class="label">Lunes – Viernes</span>
                            <span class="value">07:30 – 15:00</span>
                        </div>
                        <div class="ts-hour-row">
                            <span class="label">Emergencias</span>
                            <span class="value alert">24 / 7</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@push('scripts')
<script>
let currentSessionId = null;
let currentStep = 'categories';
let currentCategory = null;

document.addEventListener('DOMContentLoaded', function() {
    currentSessionId = generateSessionId();
    loadCategories();
    document.getElementById('main-menu').addEventListener('click', showMainMenu);
    document.getElementById('restart-chat').addEventListener('click', restartChat);
    document.getElementById('escalate-to-it').addEventListener('click', escalateToIT);
    document.getElementById('mark-resolved').addEventListener('click', markAsResolved);
});

function generateSessionId() {
    return 'tech_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
}

function getCurrentCategory() {
    return currentCategory;
}

function loadCategories() {
    fetch('{{ route("tech-support.interact", [], false) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ type: 'start', session_id: currentSessionId })
    })
    .then(r => r.json())
    .then(data => setTimeout(() => displayCategories(data.categories), 300))
    .catch(err => { console.error(err); showError('Error al cargar las categorías'); });
}

function displayCategories(categories) {
    const chatContainer = document.getElementById('tech-support-chat');
    const existingOptions = document.getElementById('current-options');
    if (existingOptions) existingOptions.remove();

    const optionsDiv = document.createElement('div');
    optionsDiv.className = 'grid grid-cols-1 md:grid-cols-2 gap-3 mt-4 mb-4';
    optionsDiv.id = 'current-options';

    categories.forEach(category => {
        const button = document.createElement('button');
        button.className = 'p-4 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-400 rounded-lg text-left transition duration-200';
        button.style.cssText = 'position:relative;overflow:hidden;';
        button.innerHTML = `
            <div class="flex items-center">
                <span class="text-2xl mr-3">${category.icon}</span>
                <div>
                    <div class="font-semibold text-slate-900">${category.title}</div>
                    <div class="text-sm text-slate-600 mt-0.5">${category.description}</div>
                </div>
            </div>
        `;
        button.onclick = () => selectCategory(category.id);
        optionsDiv.appendChild(button);
    });

    chatContainer.appendChild(optionsDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function selectCategory(categoryId) {
    currentCategory = categoryId;
    addMessageToChat('user', `Seleccioné: ${categoryId}`);

    fetch('{{ route("tech-support.interact", [], false) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ type: 'category_selected', category: categoryId, session_id: currentSessionId })
    })
    .then(r => r.json())
    .then(data => {
        addMessageToChat('bot', `Perfecto, veo que tienes problemas con ${categoryId}. ¿Cuál de estos describe mejor tu situación?`);
        setTimeout(() => displayProblems(data.problems), 500);
        currentStep = 'problems';
    })
    .catch(err => { console.error(err); showError('Error al procesar la categoría'); });
}

function displayProblems(problems) {
    const chatContainer = document.getElementById('tech-support-chat');
    const optionsDiv = document.createElement('div');
    optionsDiv.className = 'grid grid-cols-1 gap-3 mt-4 mb-4';
    optionsDiv.id = 'current-options';

    problems.forEach(problem => {
        const button = document.createElement('button');
        button.className = 'p-4 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-400 rounded-lg text-left transition duration-200';
        button.innerHTML = `
            <div class="font-semibold text-slate-900">${problem.title}</div>
            <div class="text-sm text-slate-600 mt-1">${problem.description}</div>
        `;
        button.onclick = () => selectProblem(problem.id);
        optionsDiv.appendChild(button);
    });

    chatContainer.appendChild(optionsDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function selectProblem(problemId) {
    const existingOptions = document.getElementById('current-options');
    if (existingOptions) existingOptions.remove();

    addMessageToChat('user', `Mi problema específico es: ${problemId}`);

    fetch('{{ route("tech-support.interact", [], false) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ type: 'problem_selected', problem_id: problemId, category: getCurrentCategory(), session_id: currentSessionId })
    })
    .then(r => r.json())
    .then(data => {
        addMessageToChat('bot', data.solution.title);
        setTimeout(() => {
            displaySolution(data.solution);
            showActionButtons();
        }, 500);
        currentStep = 'solution';
    })
    .catch(err => { console.error(err); showError('Error al obtener la solución'); });
}

function displaySolution(solution) {
    const chatContainer = document.getElementById('tech-support-chat');
    const solutionDiv = document.createElement('div');
    const priorityColor = solution.priority === 'high' ? 'red' : solution.priority === 'medium' ? 'yellow' : 'green';
    solutionDiv.className = 'flex items-start mb-4';
    solutionDiv.innerHTML = `
        <img src="{{ asset('storage/img/persona_logo.png') }}" alt="EVIA"
             class="mr-3 rounded-full flex-shrink-0"
             style="width: 40px; height: 40px; object-fit: cover; background: linear-gradient(135deg, #0F1419 0%, #1F2937 100%); border: 1.5px solid var(--eia-gold); box-shadow: 0 0 0 2px rgba(217, 119, 6, 0.15);">
        <div class="bg-white rounded-lg p-6 max-w-full shadow-sm" style="border: 1px solid var(--eia-border);">
            <h4 class="text-base font-semibold text-slate-900 mb-3 flex items-center" style="letter-spacing:-0.01em;">
                Solución paso a paso
                <span class="ml-auto text-xs font-medium px-3 py-1 rounded-full" style="background:#FFFBEB;color:#92400E;border:1px solid #FDE68A;">
                    ${solution.estimated_time}
                </span>
            </h4>
            <div class="solution-content text-slate-700 text-sm leading-relaxed">
                ${solution.content}
            </div>
            <div class="mt-4 flex justify-start">
                <span class="bg-${priorityColor}-100 text-${priorityColor}-800 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider" style="letter-spacing:0.1em;">
                    Prioridad ${solution.priority === 'high' ? 'Alta' : solution.priority === 'medium' ? 'Media' : 'Baja'}
                </span>
            </div>
        </div>
    `;
    chatContainer.appendChild(solutionDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function showActionButtons() {
    document.getElementById('escalate-to-it').style.display = 'inline-flex';
    document.getElementById('mark-resolved').style.display = 'inline-flex';
}

function addMessageToChat(sender, message) {
    const chatContainer = document.getElementById('tech-support-chat');
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex items-start mb-4 ${sender === 'user' ? 'justify-end' : ''}`;

    if (sender === 'bot') {
        messageDiv.innerHTML = `
            <img src="{{ asset('storage/img/persona_logo.png') }}" alt="EVIA"
                 class="mr-3 rounded-full flex-shrink-0"
                 style="width: 36px; height: 36px; object-fit: cover; background: linear-gradient(135deg, #0F1419 0%, #1F2937 100%); border: 1.5px solid var(--eia-gold); box-shadow: 0 0 0 2px rgba(217, 119, 6, 0.15);">
            <div class="bg-white rounded-lg p-4 shadow-sm max-w-md" style="border: 1px solid var(--eia-border);">
                <p class="text-[10px] uppercase tracking-[0.18em] font-bold mb-2" style="color: var(--eia-red);">EVIA</p>
                <p class="text-slate-800 whitespace-pre-line">${message}</p>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="bg-blue-500 rounded-lg p-4 shadow-sm max-w-md">
                <p class="text-white">${message}</p>
            </div>
        `;
    }

    chatContainer.appendChild(messageDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function showMainMenu() {
    const existingOptions = document.getElementById('current-options');
    if (existingOptions) existingOptions.remove();

    addMessageToChat('bot', 'Menú principal — ¿Qué necesitas hacer?\n\nElige una de estas opciones para una experiencia más rápida:');
    setTimeout(() => displayMainMenuOptions(), 300);

    document.getElementById('escalate-to-it').style.display = 'none';
    document.getElementById('mark-resolved').style.display = 'none';
    currentStep = 'main_menu';
}

function displayMainMenuOptions() {
    const chatContainer = document.getElementById('tech-support-chat');
    const optionsDiv = document.createElement('div');
    optionsDiv.className = 'grid grid-cols-1 md:grid-cols-2 gap-3 mt-4 mb-4';
    optionsDiv.id = 'current-options';

    const menuOptions = [
        { id: 'solve_problem', title: 'Resolver un problema', description: 'Describe tu problema, EVIA responde paso a paso', icon: '🔧',
          action: () => { addMessageToChat('user', 'Quiero describir mi problema'); setTimeout(() => showAiResolveInput(), 400); } },
        { id: 'quick_actions', title: 'Acciones rápidas', description: 'Soluciones inmediatas a problemas comunes', icon: '⚡',
          action: () => { addMessageToChat('user', 'Mostrar acciones rápidas'); setTimeout(() => showQuickActionsMenu(), 500); } },
        { id: 'contact_support', title: 'Contactar soporte', description: 'Hablar directamente con el equipo de IT', icon: '📞',
          action: () => { addMessageToChat('user', 'Quiero contactar con soporte'); setTimeout(() => quickAction('contact_it'), 500); } },
        { id: 'system_status', title: 'Estado del sistema', description: 'Verificar servicios y conexiones', icon: '📊',
          action: () => { addMessageToChat('user', 'Verificar estado del sistema'); setTimeout(() => showSystemStatus(), 500); } }
    ];

    menuOptions.forEach(option => {
        const button = document.createElement('button');
        button.className = 'p-4 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-400 rounded-lg text-left transition duration-200';
        button.innerHTML = `
            <div class="flex items-center">
                <span class="text-2xl mr-3">${option.icon}</span>
                <div>
                    <div class="font-semibold text-slate-900">${option.title}</div>
                    <div class="text-sm text-slate-600 mt-0.5">${option.description}</div>
                </div>
            </div>
        `;
        button.onclick = option.action;
        optionsDiv.appendChild(button);
    });

    chatContainer.appendChild(optionsDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

// === Resolver problema con IA (EVIA libre) ===
function showAiResolveInput() {
    const existingOptions = document.getElementById('current-options');
    if (existingOptions) existingOptions.remove();

    const existingForm = document.getElementById('ai-resolve-form');
    if (existingForm) existingForm.remove();

    addMessageToChat('bot', 'Cuéntame qué pasa con tu equipo o herramienta. Sé concreto: qué intentaste, qué error ves y qué necesitas hacer. Te respondo paso a paso.');

    setTimeout(() => {
        const chatContainer = document.getElementById('tech-support-chat');
        const formDiv = document.createElement('div');
        formDiv.id = 'ai-resolve-form';
        formDiv.className = 'mt-4 mb-4';
        formDiv.innerHTML = `
            <div style="background: #FFFFFF; border: 1px solid var(--eia-border); border-radius: 12px; padding: 14px;">
                <label style="display:block; font-size:10px; font-weight:700; letter-spacing:0.16em; text-transform:uppercase; color: var(--eia-mute); margin-bottom:8px;">Describe tu problema</label>
                <textarea id="ai-resolve-input" rows="4" maxlength="800"
                    placeholder="Ejemplo: Mi Outlook no abre, salió un error de perfil y al reiniciar sigue igual."
                    style="width:100%; padding:11px 14px; border:1px solid var(--eia-border); border-radius:10px; background:#FFFFFF; color:var(--eia-black); font-size:13.5px; outline:none; resize:vertical; font-family:inherit; transition: all .2s ease;"></textarea>
                <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; margin-top:10px; flex-wrap:wrap;">
                    <span id="ai-resolve-counter" style="font-size:11px; color: var(--eia-mute); font-family: ui-monospace, SFMono-Regular, Menlo, monospace;">0 / 800</span>
                    <div style="display:flex; gap:8px;">
                        <button type="button" id="ai-resolve-cancel" class="ts-btn-ghost">Cancelar</button>
                        <button type="button" id="ai-resolve-send" class="ts-btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 5l7 7-7 7"/></svg>
                            Enviar a EVIA
                        </button>
                    </div>
                </div>
            </div>
        `;
        chatContainer.appendChild(formDiv);
        chatContainer.scrollTop = chatContainer.scrollHeight;

        const input = document.getElementById('ai-resolve-input');
        const counter = document.getElementById('ai-resolve-counter');
        const cancelBtn = document.getElementById('ai-resolve-cancel');
        const sendBtn = document.getElementById('ai-resolve-send');

        input.focus();
        input.addEventListener('input', () => {
            counter.textContent = `${input.value.length} / 800`;
        });
        input.addEventListener('focus', () => {
            input.style.borderColor = 'var(--eia-black)';
            input.style.boxShadow = '0 0 0 3px rgba(15, 20, 25, 0.08)';
        });
        input.addEventListener('blur', () => {
            input.style.borderColor = 'var(--eia-border)';
            input.style.boxShadow = 'none';
        });
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                sendBtn.click();
            }
        });
        cancelBtn.addEventListener('click', () => {
            formDiv.remove();
            showMainMenu();
        });
        sendBtn.addEventListener('click', () => submitAiResolve());
    }, 350);
}

function submitAiResolve() {
    const input = document.getElementById('ai-resolve-input');
    const sendBtn = document.getElementById('ai-resolve-send');
    const cancelBtn = document.getElementById('ai-resolve-cancel');
    if (!input) return;

    const problem = input.value.trim();
    if (problem.length < 5) {
        input.focus();
        input.style.borderColor = 'var(--eia-red)';
        input.style.boxShadow = '0 0 0 3px rgba(185, 28, 28, 0.15)';
        return;
    }

    // Echo del usuario y limpieza del formulario
    addMessageToChat('user', problem);
    const formDiv = document.getElementById('ai-resolve-form');
    if (formDiv) formDiv.remove();

    // Loader como mensaje del bot
    const chatContainer = document.getElementById('tech-support-chat');
    const loadingDiv = document.createElement('div');
    loadingDiv.id = 'ai-resolve-loading';
    loadingDiv.className = 'flex items-start mb-4';
    loadingDiv.innerHTML = `
        <img src="{{ asset('storage/img/persona_logo.png') }}" alt="EVIA"
             class="mr-3 rounded-full flex-shrink-0"
             style="width: 36px; height: 36px; object-fit: cover; background: linear-gradient(135deg, #0F1419 0%, #1F2937 100%); border: 1.5px solid var(--eia-gold); box-shadow: 0 0 0 2px rgba(217, 119, 6, 0.15);">
        <div class="bg-white rounded-lg p-4 shadow-sm" style="border: 1px solid var(--eia-border);">
            <p class="text-[10px] uppercase tracking-[0.18em] font-bold mb-2" style="color: var(--eia-red);">EVIA</p>
            <div style="display:flex; align-items:center; gap:10px;">
                <span class="eia-spinner"></span>
                <span class="text-sm text-slate-700">Analizando tu problema…</span>
            </div>
        </div>
    `;
    chatContainer.appendChild(loadingDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;

    fetch('{{ route("tech-support.interact", [], false) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            type: 'ai_resolve',
            session_id: currentSessionId,
            problem: problem
        })
    })
    .then(r => r.json())
    .then(data => {
        const loader = document.getElementById('ai-resolve-loading');
        if (loader) loader.remove();

        if (data.success && data.answer) {
            addMessageToChat('bot', data.answer);
            showActionButtons();
            // Permitir nueva consulta
            setTimeout(() => offerFollowUp(), 600);
        } else {
            addMessageToChat('bot', data.error || 'No pude procesar tu solicitud. ¿Puedes reformularla?');
            setTimeout(() => offerFollowUp(), 600);
        }
    })
    .catch(err => {
        console.error(err);
        const loader = document.getElementById('ai-resolve-loading');
        if (loader) loader.remove();
        addMessageToChat('bot', 'Error de conexión al consultar el servicio. Intenta de nuevo en un momento.');
    });
}

function offerFollowUp() {
    const existingOptions = document.getElementById('current-options');
    if (existingOptions) existingOptions.remove();

    const chatContainer = document.getElementById('tech-support-chat');
    const optionsDiv = document.createElement('div');
    optionsDiv.className = 'grid grid-cols-1 md:grid-cols-2 gap-3 mt-4 mb-4';
    optionsDiv.id = 'current-options';

    const options = [
        { title: 'Tengo otra duda', description: 'Describir un problema nuevo', icon: '💬', action: () => { showAiResolveInput(); } },
        { title: 'Menú principal', description: 'Volver a las opciones', icon: '🏠', action: () => { showMainMenu(); } },
    ];

    options.forEach(option => {
        const button = document.createElement('button');
        button.className = 'p-4 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-400 rounded-lg text-left transition duration-200';
        button.innerHTML = `
            <div class="flex items-center">
                <span class="text-2xl mr-3">${option.icon}</span>
                <div>
                    <div class="font-semibold text-slate-900">${option.title}</div>
                    <div class="text-sm text-slate-600 mt-0.5">${option.description}</div>
                </div>
            </div>
        `;
        button.onclick = option.action;
        optionsDiv.appendChild(button);
    });

    chatContainer.appendChild(optionsDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function showQuickActionsMenu() {
    addMessageToChat('bot', 'Acciones rápidas disponibles:\n\nElige la acción que necesitas realizar.');

    const chatContainer = document.getElementById('tech-support-chat');
    const optionsDiv = document.createElement('div');
    optionsDiv.className = 'grid grid-cols-1 gap-3 mt-4 mb-4';
    optionsDiv.id = 'current-options';

    const quickActions = [
        { id: 'restart_computer', title: '🔄 Reiniciar Computadora', description: 'Guía paso a paso para reiniciar' },
        { id: 'check_internet', title: '🌐 Verificar Internet', description: 'Diagnosticar problemas de conexión' },
        { id: 'contact_it', title: '📞 Contactar IT', description: 'Información de contacto directa' }
    ];

    quickActions.forEach(action => {
        const button = document.createElement('button');
        button.className = 'p-3 bg-white hover:bg-slate-50 border border-slate-200 hover:border-slate-400 rounded-lg text-left transition duration-200';
        button.innerHTML = `
            <div class="flex items-center">
                <span class="text-xl mr-3">${action.title.split(' ')[0]}</span>
                <div>
                    <div class="font-semibold text-slate-900">${action.title.substring(2)}</div>
                    <div class="text-sm text-slate-600 mt-0.5">${action.description}</div>
                </div>
            </div>
        `;
        button.onclick = () => { addMessageToChat('user', `Seleccioné: ${action.title}`); quickAction(action.id); };
        optionsDiv.appendChild(button);
    });

    chatContainer.appendChild(optionsDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function showSystemStatus() {
    addMessageToChat('bot', `Estado del sistema:

Verificaciones básicas
• Conexión a Internet: Activa
• Servidor de correo: Funcionando
• Red interna: Conectada
• Impresoras de red: Verificando

Última actualización: ${new Date().toLocaleTimeString()}

Recomendaciones
• Para problemas específicos, usa "Resolver un Problema"
• Para asistencia inmediata, contacta a Oscar (56 1007 1384) o Alan (55 1798 9865)

¿Necesitas verificar algo más específico?`);
    showActionButtons();
}

function restartChat() {
    currentSessionId = generateSessionId();
    currentStep = 'categories';
    currentCategory = null;

    document.getElementById('tech-support-chat').innerHTML = `
        <div class="flex items-start mb-4">
            <img src="{{ asset('storage/img/persona_logo.png') }}" alt="EVIA"
                 class="mr-3 rounded-full flex-shrink-0"
                 style="width: 40px; height: 40px; object-fit: cover; background: linear-gradient(135deg, #0F1419 0%, #1F2937 100%); border: 1.5px solid var(--eia-gold); box-shadow: 0 0 0 2px rgba(217, 119, 6, 0.15);">
            <div class="bg-white rounded-lg p-4 shadow-sm max-w-md" style="border: 1px solid var(--eia-border);">
                <p class="text-[10px] uppercase tracking-[0.18em] font-bold mb-2" style="color: var(--eia-red);">EVIA · Asistente</p>
                <p class="text-slate-800">
                    ¡Hola de nuevo! Soy EVIA, tu asistente de soporte. Estoy aquí para ayudarte con cualquier problema técnico.
                </p>
                <p class="text-slate-800 mt-2">
                    <strong>¿Con qué puedo ayudarte hoy?</strong>
                </p>
            </div>
        </div>
    `;

    document.getElementById('escalate-to-it').style.display = 'none';
    document.getElementById('mark-resolved').style.display = 'none';

    setTimeout(() => loadCategories(), 500);
}

function escalateToIT() {
    fetch('{{ route("tech-support.interact", [], false) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ type: 'escalate', session_id: currentSessionId, reason: 'Usuario solicitó escalamiento manual' })
    })
    .then(r => r.json())
    .then(data => {
        addMessageToChat('bot', data.message);
        setTimeout(() => restartChat(), 3000);
    })
    .catch(err => { console.error(err); showError('Error al procesar la solicitud'); });
}

function markAsResolved() {
    fetch('{{ route("tech-support.interact", [], false) }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ type: 'mark_resolved', session_id: currentSessionId })
    })
    .then(r => r.json())
    .then(data => {
        addMessageToChat('bot', 'Excelente, me alegra saber que pudimos resolver tu problema.\n\nSi tienes otro problema, estaré aquí para ayudarte.');
        setTimeout(() => restartChat(), 3000);
    })
    .catch(err => { console.error(err); showError('Error al marcar como resuelto'); });
}

function quickAction(action) {
    const existingOptions = document.getElementById('current-options');
    if (existingOptions) existingOptions.remove();

    switch(action) {
        case 'restart_computer':
            addMessageToChat('bot', `Cómo reiniciar tu computadora paso a paso:

1. Guarda tu trabajo
   • Guarda todos los documentos abiertos (Ctrl + S)
   • Cierra todos los programas

2. Reiniciar
   • Click en el botón de Windows (esquina inferior izquierda)
   • Click en el ícono de encendido
   • Selecciona "Reiniciar"
   • Espera a que la computadora se reinicie completamente

3. Después del reinicio
   • Ingresa tu contraseña si te la pide
   • Espera a que cargue el escritorio
   • Tu computadora debería funcionar mejor

Tiempo estimado: 3 – 5 minutos`);
            showActionButtons();
            break;

        case 'check_internet':
            addMessageToChat('bot', `Verificar problemas de internet:

1. Revisar conexión
   • Mira la esquina inferior derecha de tu pantalla
   • ¿Ves el símbolo del WiFi?
   • Si tiene una X roja, no estás conectado

2. Reconectar WiFi
   • Click en el símbolo de WiFi
   • Busca el nombre de tu red
   • Click en "Conectar"
   • Ingresa la contraseña si te la pide

3. Probar navegación
   • Abre tu navegador
   • Ve a google.com
   • Si carga, tu internet está funcionando

Si nada funciona, el problema puede estar en el proveedor de internet.`);
            showActionButtons();
            break;

        case 'contact_it':
            addMessageToChat('bot', `Contactar al equipo de IT:

Oscar Chávez Rosales
Email: ochavez@gptservices.com
Teléfono: 56 1007 1384

Alan E. Hernández Mendoza
Email: ahernandezm@gptservices.com
Teléfono: 55 1798 9865

Horarios
• Lunes – Viernes: 07:30 – 15:00
• Emergencias: 24/7

Antes de llamar ten lista esta información:
• Nombre completo
• Número de empleado
• Descripción del problema
• Qué estabas haciendo cuando ocurrió

El equipo de IT estará encantado de ayudarte.`);
            break;
    }
}

function showError(message) {
    addMessageToChat('bot', `${message}. Por favor intenta nuevamente o contacta a IT.`);
}

// === Búsqueda de empleados y documentos (panel deshabilitado en esta vista) ===
document.addEventListener('DOMContentLoaded', function() {
    const employeeSearch = document.getElementById('employee-search');
    const documentSearch = document.getElementById('document-search');

    // Si los paneles no están en el DOM, no inicializamos nada más.
    if (!employeeSearch && !documentSearch) {
        return;
    }

    if (typeof loadEmployeeTags === 'function' && document.getElementById('employee-tags')) {
        loadEmployeeTags();
    }
    if (typeof loadDocumentTags === 'function' && document.getElementById('document-tags-container')) {
        loadDocumentTags();
    }

    if (employeeSearch) {
        let employeeSearchTimeout;
        employeeSearch.addEventListener('input', function() {
            clearTimeout(employeeSearchTimeout);
            employeeSearchTimeout = setTimeout(() => {
                if (this.value.length >= 2) searchEmployees(this.value);
                else showEmployeeDefaultMessage();
            }, 300);
        });
    }

    if (documentSearch) {
        let documentSearchTimeout;
        documentSearch.addEventListener('input', function() {
            clearTimeout(documentSearchTimeout);
            documentSearchTimeout = setTimeout(() => {
                if (this.value.length >= 2) searchDocuments(this.value);
                else showDocumentDefaultMessage();
            }, 300);
        });
    }
});

function loadEmployeeTags() {
    fetch('/corporate-chat/employees/tags')
    .then(r => { if (!r.ok) throw new Error('Error al cargar tags'); return r.json(); })
    .then(data => {
        const tagsContainer = document.getElementById('employee-tags');
        let html = '';

        if (data.departments && data.departments.length > 0) {
            data.departments.slice(0, 8).forEach(dept => {
                const displayName = dept === 'Recursos Humanos' ? 'RRHH' :
                                   dept === 'Administración y Finanzas' ? 'Admin y Finanzas' :
                                   dept.length > 15 ? dept.substring(0, 12) + '...' : dept;
                html += `
                    <button onclick="selectEmployeeTag('department', '${dept}')" class="employee-tag-btn" data-type="department" data-value="${dept}" title="${dept}">
                        ${displayName}
                    </button>
                `;
            });
        }

        if (data.positions && data.positions.length > 0) {
            const mainPositions = ['Dirección General', 'Administración y Finanzas', 'Jefe de Área'];
            mainPositions.forEach(pos => {
                if (data.positions.includes(pos)) {
                    const displayName = pos === 'Dirección General' ? 'Dirección' :
                                       pos === 'Administración y Finanzas' ? 'Finanzas' : pos;
                    html += `
                        <button onclick="selectEmployeeTag('position', '${pos}')" class="employee-tag-btn" data-type="position" data-value="${pos}" title="${pos}">
                            ${displayName}
                        </button>
                    `;
                }
            });
        }

        html += `
            <button onclick="loadAllEmployees()" class="employee-tag-btn bg-blue-300" style="font-weight:700;">
                Ver todos
            </button>
        `;

        tagsContainer.innerHTML = html;
    })
    .catch(err => {
        console.error('Error loading tags:', err);
        const tagsContainer = document.getElementById('employee-tags');
        tagsContainer.innerHTML = `
            <button onclick="selectEmployeeTag('department', 'IT')" class="employee-tag-btn" data-type="department" data-value="IT">IT</button>
            <button onclick="selectEmployeeTag('department', 'Recursos Humanos')" class="employee-tag-btn" data-type="department" data-value="Recursos Humanos">RRHH</button>
            <button onclick="loadAllEmployees()" class="employee-tag-btn bg-blue-300" style="font-weight:700;">Ver todos</button>
        `;
    });
}

function loadDocumentTags() {
    fetch('/corporate-chat/documents/tags')
    .then(r => { if (!r.ok) throw new Error('Error al cargar categorías'); return r.json(); })
    .then(data => {
        const tagsContainer = document.getElementById('document-tags-container');
        let html = '';

        const categoryMap = {
            'contexto_planificacion': { name: 'Contexto y planificación', icon: 'fas fa-calendar-alt' },
            'procedimientos_normativos': { name: 'Políticas y normas', icon: 'fas fa-gavel' },
            'procedimientos_operativos': { name: 'Procedimientos operativos', icon: 'fas fa-cogs' },
            'mejora_continua': { name: 'Mejora continua', icon: 'fas fa-chart-line' },
            'general': { name: 'General', icon: 'fas fa-folder' }
        };

        data.categories.forEach(category => {
            const categoryInfo = categoryMap[category] || { name: category, icon: 'fas fa-file' };
            html += `
                <button onclick="selectDocumentCategory('${category}')" class="document-category-btn" data-category="${category}">
                    <i class="${categoryInfo.icon} mr-2"></i>
                    <span>${categoryInfo.name}</span>
                </button>
            `;
        });

        tagsContainer.innerHTML = html;
    })
    .catch(err => {
        console.error(err);
        const tagsContainer = document.getElementById('document-tags-container');
        tagsContainer.innerHTML = `
            <button onclick="selectDocumentCategory('general')" class="document-category-btn" data-category="general">
                <i class="fas fa-folder mr-2"></i>
                <span>General</span>
            </button>
        `;
    });
}

function searchEmployees(query) {
    const resultsContainer = document.getElementById('employee-results');
    resultsContainer.innerHTML = '<div class="text-center py-4 text-sm text-slate-500"><span class="eia-spinner" style="width:14px;height:14px;border-width:2px;vertical-align:middle;margin-right:8px;"></span>Buscando...</div>';

    fetch('/corporate-chat/employees/search?' + new URLSearchParams({ search: query }))
    .then(r => { if (!r.ok) throw new Error('Error en la respuesta'); return r.json(); })
    .then(data => displayEmployeeResults(data.employees || []))
    .catch(err => { console.error(err); resultsContainer.innerHTML = '<div class="text-center text-red-600 py-4 text-sm">Error al buscar empleados.</div>'; });
}

function selectEmployeeTag(type, value) {
    document.querySelectorAll('.employee-tag-btn').forEach(btn => {
        const btnType = btn.getAttribute('data-type');
        if (btnType === 'department') {
            btn.classList.remove('bg-blue-300', 'text-blue-900');
            btn.classList.add('bg-blue-100', 'text-blue-700');
        } else if (btnType === 'position') {
            btn.classList.remove('bg-blue-400', 'text-blue-900');
            btn.classList.add('bg-blue-200', 'text-blue-800');
        }
    });

    const selectedBtn = document.querySelector(`[data-type="${type}"][data-value="${value}"]`);
    if (selectedBtn) {
        if (type === 'department') {
            selectedBtn.classList.remove('bg-blue-100', 'text-blue-700');
            selectedBtn.classList.add('bg-blue-300', 'text-blue-900');
        } else if (type === 'position') {
            selectedBtn.classList.remove('bg-blue-200', 'text-blue-800');
            selectedBtn.classList.add('bg-blue-400', 'text-blue-900');
        }
    }
    searchEmployeesByType(type, value);
}

function searchEmployeesByType(type, value) {
    const resultsContainer = document.getElementById('employee-results');
    resultsContainer.innerHTML = '<div class="text-center py-4 text-sm text-slate-500"><span class="eia-spinner" style="width:14px;height:14px;border-width:2px;vertical-align:middle;margin-right:8px;"></span>Buscando...</div>';

    const params = {};
    params[type] = value;

    fetch('/corporate-chat/employees/search?' + new URLSearchParams(params))
    .then(r => { if (!r.ok) throw new Error('Error en la respuesta'); return r.json(); })
    .then(data => displayEmployeeResults(data.employees || []))
    .catch(err => { console.error(err); resultsContainer.innerHTML = '<div class="text-center text-red-600 py-4 text-sm">Error al buscar empleados.</div>'; });
}

function loadAllEmployees() {
    const resultsContainer = document.getElementById('employee-results');
    resultsContainer.innerHTML = '<div class="text-center py-4 text-sm text-slate-500"><span class="eia-spinner" style="width:14px;height:14px;border-width:2px;vertical-align:middle;margin-right:8px;"></span>Cargando todos los empleados...</div>';

    fetch('/corporate-chat/employees/search')
    .then(r => r.json())
    .then(data => {
        if (!data.employees || data.employees.length === 0) {
            resultsContainer.innerHTML = '<div class="text-center text-slate-500 py-8 text-sm">No se encontraron empleados.</div>';
            return;
        }

        let html = `
            <div class="mb-4 p-3 rounded-lg" style="background:#F8FAFC; border:1px solid var(--eia-border);">
                <p class="font-semibold text-slate-900 text-sm">Directorio completo de empleados (${data.employees.length} total)</p>
                <p class="text-xs text-slate-500 mt-0.5">Mostrando todos los empleados registrados</p>
            </div>
            <div class="space-y-3">
        `;

        const sortedEmployees = data.employees.sort((a, b) => {
            const deptA = a.department || 'ZZ Sin departamento';
            const deptB = b.department || 'ZZ Sin departamento';
            if (deptA !== deptB) return deptA.localeCompare(deptB);
            return (a.full_name || '').localeCompare(b.full_name || '');
        });

        let currentDept = '';
        sortedEmployees.forEach(emp => {
            const empDept = emp.department || 'Sin departamento';

            if (empDept !== currentDept) {
                html += `
                    <div class="px-3 py-2 rounded-lg mt-4 first:mt-0" style="background:#0F1419; color:#FFFFFF;">
                        <p class="font-semibold text-xs uppercase tracking-widest" style="color:var(--eia-gold-soft);">${empDept}</p>
                    </div>
                `;
                currentDept = empDept;
            }

            html += `
                <div class="rounded-lg p-4 ml-3" style="background:#FFFFFF; border:1px solid var(--eia-border);">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-slate-900 text-sm">${emp.full_name || 'Nombre no disponible'}</p>
                            <p class="text-xs text-slate-600 mt-0.5">${emp.position || 'Cargo no especificado'}</p>
                            ${emp.location ? `<p class="text-xs text-slate-500 mt-1"><i class="fas fa-map-marker-alt mr-1"></i>${emp.location}</p>` : ''}
                        </div>
                        <div class="text-right text-xs space-y-0.5">
                            ${emp.email ? `<a href="mailto:${emp.email}" class="block" style="color:var(--eia-red);"><i class="fas fa-envelope mr-1"></i>${emp.email}</a>` : ''}
                            ${emp.phone ? `<a href="tel:${emp.phone}" class="block" style="color:var(--eia-gold);"><i class="fas fa-phone mr-1"></i>${emp.phone}</a>` : ''}
                            ${emp.extension ? `<div class="text-slate-500">Ext: ${emp.extension}</div>` : ''}
                            ${emp.employee_id ? `<div class="text-slate-400">ID: ${emp.employee_id}</div>` : ''}
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        resultsContainer.innerHTML = html;
    })
    .catch(err => { console.error(err); resultsContainer.innerHTML = '<div class="text-center text-red-600 py-4 text-sm">Error al cargar empleados.</div>'; });
}

function displayEmployeeResults(employees) {
    const resultsContainer = document.getElementById('employee-results');

    if (!employees || employees.length === 0) {
        resultsContainer.innerHTML = '<div class="text-center text-slate-500 py-8 text-sm">No se encontraron empleados.</div>';
        return;
    }

    let html = '<div class="space-y-3">';
    employees.forEach(emp => {
        html += `
            <div class="rounded-lg p-4" style="background:#FFFFFF; border:1px solid var(--eia-border);">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-900 text-sm">${emp.full_name || 'Nombre no disponible'}</p>
                        <p class="text-xs text-slate-600 mt-0.5">${emp.position || 'Cargo no especificado'}</p>
                        <p class="text-xs text-slate-500">${emp.department || 'Departamento no especificado'}</p>
                        ${emp.location ? `<p class="text-xs text-slate-500 mt-1"><i class="fas fa-map-marker-alt mr-1"></i>${emp.location}</p>` : ''}
                    </div>
                    <div class="text-right text-xs space-y-0.5">
                        ${emp.email ? `<a href="mailto:${emp.email}" class="block" style="color:var(--eia-red);"><i class="fas fa-envelope mr-1"></i>${emp.email}</a>` : ''}
                        ${emp.phone ? `<a href="tel:${emp.phone}" class="block" style="color:var(--eia-gold);"><i class="fas fa-phone mr-1"></i>${emp.phone}</a>` : ''}
                        ${emp.extension ? `<div class="text-slate-500">Ext: ${emp.extension}</div>` : ''}
                        ${emp.employee_id ? `<div class="text-slate-400">ID: ${emp.employee_id}</div>` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';

    resultsContainer.innerHTML = html;
}

function showEmployeeDefaultMessage() {
    document.getElementById('employee-results').innerHTML = `
        <div class="text-center text-slate-500 py-8">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" class="inline-block mb-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/>
            </svg>
            <p class="text-sm">Usa el campo de búsqueda o los filtros para encontrar empleados</p>
        </div>
    `;
}

function searchDocuments(query) {
    const resultsContainer = document.getElementById('document-results');
    resultsContainer.innerHTML = '<div class="text-center py-4 text-sm text-slate-500"><span class="eia-spinner" style="width:14px;height:14px;border-width:2px;vertical-align:middle;margin-right:8px;"></span>Buscando...</div>';

    fetch('/corporate-chat/documents/search?' + new URLSearchParams({ search: query }))
    .then(r => { if (!r.ok) throw new Error('Error en la respuesta'); return r.json(); })
    .then(data => displayDocumentResults(data.documents || []))
    .catch(err => { console.error(err); resultsContainer.innerHTML = '<div class="text-center text-red-600 py-4 text-sm">Error al buscar documentos.</div>'; });
}

function selectDocumentCategory(category) {
    document.querySelectorAll('.document-category-btn').forEach(btn => {
        btn.classList.remove('bg-green-200', 'border-green-400', 'shadow-md');
        btn.classList.add('bg-green-50', 'border-green-200');
        const icon = btn.querySelector('i');
        const span = btn.querySelector('span');
        if (icon) icon.classList.remove('text-green-800');
        if (icon) icon.classList.add('text-green-600');
        if (span) span.classList.remove('text-green-800');
    });

    const selectedBtn = document.querySelector(`[data-category="${category}"]`);
    if (selectedBtn) {
        selectedBtn.classList.remove('bg-green-50', 'border-green-200');
        selectedBtn.classList.add('bg-green-200', 'border-green-400', 'shadow-md');
        const icon = selectedBtn.querySelector('i');
        const span = selectedBtn.querySelector('span');
        if (icon) { icon.classList.remove('text-green-600'); icon.classList.add('text-green-800'); }
        if (span) span.classList.add('text-green-800');
    }

    searchDocumentsByCategory(category);
}

function searchDocumentsByCategory(category) {
    const resultsContainer = document.getElementById('document-results');
    resultsContainer.innerHTML = '<div class="text-center py-4 text-sm text-slate-500"><span class="eia-spinner" style="width:14px;height:14px;border-width:2px;vertical-align:middle;margin-right:8px;"></span>Buscando...</div>';

    fetch('/corporate-chat/documents/search?' + new URLSearchParams({ category: category }))
    .then(r => { if (!r.ok) throw new Error('Error en la respuesta'); return r.json(); })
    .then(data => displayDocumentResults(data.documents || []))
    .catch(err => { console.error(err); resultsContainer.innerHTML = '<div class="text-center text-red-600 py-4 text-sm">Error al buscar documentos.</div>'; });
}

function displayDocumentResults(documents) {
    const resultsContainer = document.getElementById('document-results');

    if (!documents || documents.length === 0) {
        resultsContainer.innerHTML = '<div class="text-center text-slate-500 py-8 text-sm">No se encontraron documentos.</div>';
        return;
    }

    let html = '<div class="space-y-3">';
    documents.forEach(doc => {
        const categoryName = getCategoryDisplayName(doc.category);
        html += `
            <div class="rounded-lg p-4" style="background:#FFFFFF; border:1px solid var(--eia-border);">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-900 text-sm">${doc.title || 'Documento sin título'}</p>
                        ${doc.description ? `<p class="text-xs text-slate-600 mt-1">${doc.description}</p>` : ''}
                        <div class="flex items-center mt-2 gap-2 flex-wrap">
                            <span class="text-[10px] uppercase tracking-widest px-2 py-1 rounded-full font-semibold" style="background:#FFFBEB;color:#92400E;border:1px solid #FDE68A;">${categoryName}</span>
                            ${doc.type ? `<span class="text-[10px] uppercase tracking-widest px-2 py-1 rounded-full font-semibold" style="background:#F1F5F9;color:var(--eia-slate);border:1px solid var(--eia-border);">${doc.type}</span>` : ''}
                        </div>
                    </div>
                    <div class="ml-2 flex-shrink-0">
                        ${doc.external_url ? `
                            <a href="${doc.external_url}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded text-xs font-semibold transition-colors" style="background:var(--eia-black);color:#FFFFFF;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                Abrir
                            </a>
                        ` : `
                            <span class="inline-block px-3 py-1.5 rounded text-xs font-medium" style="background:#F1F5F9;color:#94A3B8;border:1px solid var(--eia-border);">Sin enlace</span>
                        `}
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';

    resultsContainer.innerHTML = html;
}

function getCategoryDisplayName(category) {
    const names = {
        'contexto_planificacion': 'Contexto de planificación',
        'procedimientos_normativos': 'Procedimientos normativos',
        'procedimientos_operativos': 'Procedimientos operativos',
        'mejora_continua': 'Mejora continua',
        'general': 'General'
    };
    return names[category] || 'Sin categoría';
}

function showDocumentDefaultMessage() {
    document.getElementById('document-results').innerHTML = `
        <div class="text-center text-slate-500 py-8">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" class="inline-block mb-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h5l2 2h7a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
            </svg>
            <p class="text-sm">Selecciona una categoría o busca documentos específicos</p>
        </div>
    `;
}
</script>
@endpush
@endsection
