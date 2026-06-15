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
    .doc-hero {
        background:
            radial-gradient(1000px 280px at 92% -40%, rgba(217, 119, 6, 0.18), transparent 60%),
            radial-gradient(800px 260px at 5% 130%, rgba(185, 28, 28, 0.22), transparent 60%),
            linear-gradient(180deg, #0F1419 0%, #1A1F26 100%);
        color: #F8FAFC;
        border-bottom: 1px solid var(--eia-graphite);
        position: relative;
    }
    .doc-hero::after {
        content: '';
        position: absolute;
        left: 0; right: 0; bottom: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--eia-red) 0%, var(--eia-gold) 100%);
        opacity: 0.85;
    }
    .doc-back {
        width: 38px; height: 38px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        background: rgba(255, 255, 255, 0.04);
        display: inline-flex; align-items: center; justify-content: center;
        color: #E2E8F0;
        transition: all .2s ease;
    }
    .doc-back:hover {
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

    /* Hero action buttons */
    .doc-action {
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
        cursor: pointer;
    }
    .doc-action:hover {
        background: rgba(217, 119, 6, 0.15);
        border-color: var(--eia-gold);
    }
    .doc-action.alt:hover {
        background: rgba(185, 28, 28, 0.18);
        border-color: var(--eia-red);
    }

    /* Panels */
    .doc-panel {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 14px;
    }
    .doc-panel-head {
        padding: 18px 22px;
        border-bottom: 1px solid var(--eia-border);
    }
    .doc-panel-title {
        font-size: 11px;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--eia-black);
    }
    .doc-panel-sub {
        font-size: 12px;
        color: var(--eia-mute);
        margin-top: 4px;
    }
    .doc-panel-body { padding: 20px 22px; }

    /* Tabs */
    .tab-button {
        position: relative;
        padding: 8px 14px;
        font-size: 12.5px;
        font-weight: 600;
        letter-spacing: 0.02em;
        color: var(--eia-slate);
        border-radius: 8px;
        background: #F8FAFC;
        border: 1px solid var(--eia-border);
        transition: color .2s ease, background .2s ease, border-color .2s ease;
        cursor: pointer;
    }
    .tab-button:hover { color: var(--eia-black); background: #F1F5F9; }
    .tab-button.active {
        color: #FFFFFF;
        background: var(--eia-black);
        border-color: var(--eia-black);
    }

    /* Document list items */
    .document-item {
        cursor: pointer;
        transition: all .2s ease;
        background: #FFFFFF;
        border: 1px solid var(--eia-border) !important;
        border-radius: 10px;
        position: relative;
        overflow: hidden;
    }
    .document-item::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--eia-red);
        opacity: 0;
        transition: opacity .2s ease;
    }
    .document-item:hover {
        background: #F8FAFC;
        border-color: #94A3B8 !important;
        transform: translateX(2px);
    }
    .document-item:hover::before { opacity: 1; }
    .document-item.bg-blue-100,
    .document-item.border-blue-500 {
        background: #FFFBEB !important;
        border-color: var(--eia-gold) !important;
        border-width: 1px !important;
    }
    .document-item.bg-blue-100::before,
    .document-item.border-blue-500::before {
        opacity: 1;
        background: var(--eia-gold);
    }
    .document-item .fa-file-pdf { color: var(--eia-red) !important; }

    .doc-action-btn {
        padding: 6px;
        border-radius: 6px;
        background: #F1F5F9;
        border: 1px solid var(--eia-border);
        color: var(--eia-slate);
        transition: all .15s ease;
    }
    .doc-action-btn:hover {
        background: var(--eia-black);
        color: #FFFFFF;
        border-color: var(--eia-black);
    }
    .doc-action-btn.preview:hover { background: var(--eia-red); border-color: var(--eia-red); }
    .doc-action-btn.download:hover { background: var(--eia-gold); border-color: var(--eia-gold); }

    /* Botón de consulta por voz */
    .doc-voice-btn {
        flex-shrink: 0;
        width: 46px; height: 46px;
        border-radius: 10px;
        border: 1px solid var(--eia-border);
        background: #FFFFFF;
        color: var(--eia-slate);
        display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer;
        font-size: 16px;
        transition: all .2s ease;
    }
    .doc-voice-btn:hover {
        border-color: var(--eia-black);
        color: var(--eia-black);
        box-shadow: 0 0 0 3px rgba(15, 20, 25, 0.06);
    }
    .doc-voice-btn:disabled { opacity: .55; cursor: not-allowed; }
    .doc-voice-btn.recording {
        background: linear-gradient(145deg, #DC2626 0%, #991B1B 100%);
        border-color: #991B1B;
        color: #FFFFFF;
        animation: doc-voice-pulse 1.4s ease-in-out infinite;
    }
    @keyframes doc-voice-pulse {
        0%   { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.45); }
        70%  { box-shadow: 0 0 0 12px rgba(220, 38, 38, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
    }

    /* Form inputs */
    .eia-input,
    .eia-textarea {
        width: 100%;
        padding: 11px 14px;
        border: 1px solid var(--eia-border);
        border-radius: 10px;
        background: #FFFFFF;
        color: var(--eia-black);
        font-size: 13.5px;
        transition: all .2s ease;
        outline: none;
    }
    .eia-input:focus,
    .eia-textarea:focus {
        border-color: var(--eia-black);
        box-shadow: 0 0 0 3px rgba(15, 20, 25, 0.08);
    }
    .eia-input::placeholder,
    .eia-textarea::placeholder { color: #94A3B8; }
    .eia-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--eia-mute);
        margin-bottom: 8px;
    }

    /* Buttons */
    .btn-eia-primary {
        background: var(--eia-black);
        color: #FFFFFF;
        font-size: 13px;
        font-weight: 600;
        padding: 12px 22px;
        border-radius: 10px;
        border: 1px solid var(--eia-black);
        transition: all .2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
    }
    .btn-eia-primary:hover {
        background: #1F2937;
        border-color: var(--eia-gold);
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15);
    }
    .btn-eia-outline {
        background: #FFFFFF;
        color: var(--eia-black);
        font-size: 13px;
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 10px;
        border: 1px solid var(--eia-border);
        transition: all .2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
    }
    .btn-eia-outline:hover {
        background: #F8FAFC;
        border-color: var(--eia-black);
    }

    /* Toggle switches (custom institutional) */
    .eia-toggle {
        position: relative;
        display: inline-block;
        width: 42px;
        height: 22px;
        flex-shrink: 0;
    }
    .eia-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .eia-toggle-slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background: #E2E8F0;
        border-radius: 999px;
        transition: .2s;
        border: 1px solid var(--eia-border);
    }
    .eia-toggle-slider::before {
        content: "";
        position: absolute;
        height: 16px; width: 16px;
        left: 2px; top: 2px;
        background: #FFFFFF;
        border-radius: 50%;
        transition: .25s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
    }
    .eia-toggle input:checked + .eia-toggle-slider {
        background: var(--eia-black);
        border-color: var(--eia-black);
    }
    .eia-toggle input:checked + .eia-toggle-slider::before {
        transform: translateX(20px);
        background: var(--eia-gold-soft);
    }
    .eia-toggle input:checked + .eia-toggle-slider.alt {
        background: var(--eia-red);
        border-color: var(--eia-red);
    }
    .eia-toggle input:checked + .eia-toggle-slider.alt::before {
        background: #FFFFFF;
    }

    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 14px;
        border: 1px solid var(--eia-border);
        border-radius: 10px;
        background: #FAFAFB;
    }
    .toggle-row-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--eia-black);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .toggle-row-sub {
        font-size: 11.5px;
        color: var(--eia-mute);
        margin-top: 2px;
    }

    /* Banner doc seleccionado */
    .selected-doc-banner {
        background: #FFFBEB;
        border: 1px solid #FDE68A;
        border-left: 3px solid var(--eia-gold);
        border-radius: 10px;
        padding: 12px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .selected-doc-banner-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: rgba(217, 119, 6, 0.12);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--eia-gold);
        flex-shrink: 0;
    }
    .selected-doc-banner-text {
        font-size: 12.5px;
        color: var(--eia-black);
    }
    .selected-doc-banner-text strong { color: var(--eia-black); font-weight: 700; }
    .selected-doc-banner button {
        color: var(--eia-slate);
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 4px 6px;
        border-radius: 6px;
        transition: all .15s ease;
    }
    .selected-doc-banner button:hover { background: rgba(0,0,0,0.06); color: var(--eia-black); }

    /* Result panel */
    .doc-result-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding-bottom: 14px;
        border-bottom: 1px solid var(--eia-border);
        margin-bottom: 14px;
    }
    .doc-result-title {
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--eia-black);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .doc-result-content {
        background: #FAFAFB;
        border: 1px solid var(--eia-border);
        border-left: 3px solid var(--eia-gold);
        border-radius: 10px;
        padding: 18px 20px;
        color: var(--eia-slate);
        font-size: 14px;
        line-height: 1.65;
        white-space: pre-wrap;
    }

    .doc-error {
        background: #FEF2F2;
        border: 1px solid #FECACA;
        border-left: 3px solid var(--eia-red);
        border-radius: 10px;
        padding: 14px 16px;
    }

    /* Search results semánticos */
    .sem-result {
        background: #FAFAFB;
        border: 1px solid var(--eia-border);
        border-radius: 10px;
        padding: 12px 14px;
        margin-top: 8px;
        transition: all .2s ease;
    }
    .sem-result:hover { border-color: #94A3B8; }
    .sem-score {
        background: rgba(217, 119, 6, 0.12);
        color: var(--eia-gold);
        border: 1px solid rgba(217, 119, 6, 0.3);
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.06em;
        padding: 3px 8px;
        border-radius: 999px;
        white-space: nowrap;
    }

    /* Spinner */
    .eia-spinner {
        width: 28px; height: 28px;
        border: 2.5px solid #E2E8F0;
        border-top-color: var(--eia-black);
        border-right-color: var(--eia-gold);
        border-radius: 50%;
        animation: spin .8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Avatar GPT — robot 3D dentro del badge circular */
    .gpt-avatar-badge {
        border-radius: 50%;
        background: #F3F4F6;
        border: none;
        overflow: hidden;
        flex-shrink: 0;
        position: relative;
    }
    .avatar-profile-btn {
        padding: 6px 10px;
        font-size: 14px;
        line-height: 1;
        border-radius: 8px;
        background: #FFFFFF;
        border: 1px solid var(--eia-border);
        cursor: pointer;
        transition: all .2s ease;
        color: var(--eia-slate);
    }
    .avatar-profile-btn:hover { background: #F3F4F6; border-color: #9CA3AF; }
    .avatar-profile-btn.active {
        background: #E5E7EB;
        border-color: #D1D5DB;
        color: var(--eia-slate);
    }

    /* Fade-in */
    .eia-fade { animation: eiaFade .55s ease-out both; }
    .eia-d1 { animation-delay: .05s; }
    .eia-d2 { animation-delay: .12s; }
    .eia-d3 { animation-delay: .2s; }
    @keyframes eiaFade {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="eia-bg min-h-screen">

    {{-- HERO --}}
    <section class="doc-hero px-4 sm:px-8 lg:px-12 py-10">
        <div class="flex items-start justify-between gap-6 flex-wrap">
            <div class="flex items-center gap-4">
                <a href="/" class="doc-back" aria-label="Volver al inicio">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                    </svg>
                </a>
                <div>
                    <span class="eia-eyebrow">Repositorio corporativo · RAG</span>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Buscador de documentos</h1>
                    <p class="mt-1 text-sm text-slate-300 max-w-2xl">
                        Consulta inteligente sobre documentación corporativa con IA y razonamiento avanzado.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <button id="btn-stats" class="doc-action" type="button">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                    </svg>
                    Estadísticas
                </button>
                <button id="btn-health" class="doc-action alt" type="button">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Health check
                </button>
            </div>
        </div>
    </section>

    {{-- BODY --}}
    <div class="px-4 sm:px-8 lg:px-12 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Documentos --}}
                <section class="doc-panel eia-fade eia-d1">
                    <div class="doc-panel-head flex items-start justify-between gap-3">
                        <div>
                            <p class="doc-panel-title">Documentos disponibles</p>
                            <p class="doc-panel-sub">Repositorio corporativo</p>
                        </div>
                    </div>
                    <div class="doc-panel-body">
                        <div class="flex gap-2 mb-4">
                            <button id="tab-all" class="tab-button active" type="button">Todos</button>
                            <button id="tab-recent" class="tab-button" type="button">Recientes</button>
                        </div>
                        <div id="documents-list" class="space-y-2 max-h-96 overflow-y-auto pr-1">
                            <div class="flex items-center justify-center py-8">
                                <div class="eia-spinner"></div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Búsqueda semántica --}}
                <section class="doc-panel eia-fade eia-d2">
                    <div class="doc-panel-head">
                        <p class="doc-panel-title">Búsqueda semántica</p>
                        <p class="doc-panel-sub">Disponible con modelo externo</p>
                    </div>
                    <div class="doc-panel-body">
                        <form id="semantic-search-form">
                            <label class="eia-label">Consulta</label>
                            <input type="text" id="semantic-query" class="eia-input"
                                   placeholder="Buscar en documentos...">
                            <button type="submit" class="btn-eia-outline mt-3">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/>
                                </svg>
                                Buscar
                            </button>
                        </form>
                        <div id="search-results" class="mt-4"></div>
                    </div>
                </section>
            </div>

            {{-- CENTER --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Modo de consulta --}}
                <section class="doc-panel eia-fade eia-d1">
                    <div class="doc-panel-head flex items-start justify-between gap-4 flex-wrap">
                        <div>
                            <p class="doc-panel-title">Modo de consulta</p>
                            <p class="doc-panel-sub" id="query-mode-description">Consulta rápida · IA externa</p>
                        </div>
                    </div>
                    <div class="doc-panel-body space-y-3">
                        <div id="deep-reasoning-container">
                            <div class="toggle-row">
                                <div>
                                    <div class="toggle-row-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        Razonamiento profundo
                                    </div>
                                    <p class="toggle-row-sub">Análisis detallado con hasta 20 chunks de contexto.</p>
                                </div>
                                <label class="eia-toggle">
                                    <input type="checkbox" id="deep-reasoning">
                                    <span class="eia-toggle-slider alt"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Consulta --}}
                <section class="doc-panel eia-fade eia-d2">
                    <div class="doc-panel-head" style="display: flex; align-items: center; gap: 14px;">
                        <div>
                            <p class="doc-panel-title" style="letter-spacing: 0.18em;">EVIA · Consulta documental</p>
                            <p class="doc-panel-sub">Dime qué necesitas saber, yo busco en tus documentos.</p>
                        </div>
                        <div style="margin-left: auto; display: flex; align-items: center; gap: 10px;">
                            <div id="avatar-profile-toggle" style="display: flex; flex-direction: column; gap: 6px;">
                                <button type="button" class="avatar-profile-btn" data-profile="field" title="Perfil de campo (EPP)" aria-label="Perfil de campo">⛑️</button>
                                <button type="button" class="avatar-profile-btn" data-profile="exec" title="Perfil ejecutivo" aria-label="Perfil ejecutivo">👔</button>
                            </div>
                            <div class="gpt-avatar-badge" data-gpt-avatar data-avatar-mode="full"
                                 data-fallback-src="{{ asset('storage/img/persona_logo.png') }}"
                                 style="width: 60px; height: 60px;" aria-label="EVIA"></div>
                        </div>
                    </div>
                    <div class="doc-panel-body">
                        <form id="query-form">
                            <div id="selected-doc-banner" class="selected-doc-banner mb-4 hidden">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <div class="selected-doc-banner-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <p class="selected-doc-banner-text truncate">
                                        Documento seleccionado: <strong id="selected-doc-name"></strong>
                                    </p>
                                </div>
                                <button type="button" id="clear-doc" aria-label="Quitar selección">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <label class="eia-label">Tu pregunta</label>
                            <textarea id="question-input" rows="4" required class="eia-textarea"
                                      placeholder="¿Qué información necesitas obtener de los documentos?"></textarea>

                            <div class="mt-4 flex items-center gap-3">
                                <button type="button" id="btn-voice-query" class="doc-voice-btn"
                                        title="Preguntar por voz" aria-label="Preguntar por voz">
                                    <i class="fas fa-microphone"></i>
                                </button>
                                <button type="submit" id="submit-btn" class="btn-eia-primary" style="margin-top:0;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 5l7 7-7 7"/>
                                    </svg>
                                    <span id="submit-text">Enviar consulta</span>
                                </button>
                            </div>
                            <p id="voice-status" class="text-xs mt-2" style="color: var(--eia-mute); min-height: 16px;"></p>
                        </form>
                    </div>
                </section>

                {{-- Resultados --}}
                <section id="results-container" class="doc-panel hidden eia-fade">
                    <div class="doc-panel-body">
                        <div class="doc-result-head">
                            <span class="doc-result-title" style="display: inline-flex; align-items: center; gap: 10px;">
                                <div class="gpt-avatar-badge" data-gpt-avatar data-avatar-mode="full"
                                     data-fallback-src="{{ asset('storage/img/persona_logo.png') }}"
                                     style="width: 32px; height: 32px;" aria-label="EVIA"></div>
                                EVIA responde
                            </span>
                            <span style="display: inline-flex; align-items: center; gap: 10px;">
                                <button type="button" id="btn-read-response" class="doc-action-btn" title="Leer respuesta en voz alta">
                                    <i class="fas fa-volume-up text-xs"></i>
                                </button>
                                <span id="response-time" class="text-xs text-slate-500 font-mono"></span>
                            </span>
                        </div>
                        <div id="response-content" class="doc-result-content"></div>
                    </div>
                </section>

                {{-- Loading --}}
                <div id="loading-indicator" class="doc-panel hidden">
                    <div class="doc-panel-body flex items-center justify-center gap-3 py-6">
                        <div class="gpt-avatar-badge" data-gpt-avatar data-avatar-mode="full"
                             data-fallback-src="{{ asset('storage/img/persona_logo.png') }}"
                             style="width: 32px; height: 32px;" aria-label="EVIA"></div>
                        <span class="text-sm text-slate-700 font-medium">EVIA está revisando los documentos…</span>
                        <div class="eia-spinner"></div>
                    </div>
                </div>

                {{-- Error --}}
                <div id="error-message" class="hidden">
                    <div class="doc-error">
                        <div class="flex items-start gap-3">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="color: var(--eia-red); flex-shrink: 0; margin-top: 2px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 110 18 9 9 0 010-18z"/>
                            </svg>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest" style="color: var(--eia-red);">Error</p>
                                <p id="error-text" class="text-sm text-slate-700 mt-1"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/gpt-avatar.js') }}"></script>
<script>
    let selectedDocumentId = null;
    let selectedDocumentName = '';
    // El bot usa siempre el proveedor externo (API OpenCode). La opción de IA local (Ollama) fue retirada.
    const useExternalAPI = true;
    let useDeepReasoning = false;

    document.addEventListener('DOMContentLoaded', function() {
        loadDocuments('all');
        setupDeepReasoningToggle();
        setupClearDocumentButton();
        updateQueryModeDescription();
        setupGptAvatar();
        setupVoiceInput();
    });

    /* ---------- Avatar GPT ---------- */
    function setupGptAvatar() {
        document.querySelectorAll('[data-gpt-avatar]').forEach(el => GPTAvatar.mount(el));

        // Toggle de perfil (campo / ejecutivo), persiste entre servicios
        const toggleButtons = document.querySelectorAll('#avatar-profile-toggle .avatar-profile-btn');
        function refreshProfileButtons() {
            toggleButtons.forEach(btn =>
                btn.classList.toggle('active', btn.dataset.profile === GPTAvatar.getProfile()));
        }
        toggleButtons.forEach(btn => btn.addEventListener('click', function() {
            GPTAvatar.setProfile(this.dataset.profile);
            refreshProfileButtons();
        }));
        refreshProfileButtons();

        // Leer la respuesta en voz alta (la boca del robot se sincroniza)
        const btnRead = document.getElementById('btn-read-response');
        const readIcon = btnRead.querySelector('i');
        let reading = false;
        function setReadingUI(active) {
            reading = active;
            readIcon.className = active ? 'fas fa-stop text-xs' : 'fas fa-volume-up text-xs';
            btnRead.title = active ? 'Detener lectura' : 'Leer respuesta en voz alta';
        }
        btnRead.addEventListener('click', function() {
            if (reading) { GPTAvatar.stop(); return; }
            const text = document.getElementById('response-content').textContent.trim();
            if (!text) return;
            setReadingUI(true);
            GPTAvatar.speak(text, { onend: () => setReadingUI(false) });
        });
    }

    /* ---------- Consulta por voz ----------
       Graba audio en el navegador, lo transcribe con el servicio de voz
       (voz.consulta, solo texto) y coloca la transcripción en el textarea
       para enviarla por el flujo normal de document-bot (respeta documento
       seleccionado y razonamiento profundo). */
    function setupVoiceInput() {
        const btnVoice = document.getElementById('btn-voice-query');
        const voiceStatus = document.getElementById('voice-status');
        const questionInput = document.getElementById('question-input');
        if (!btnVoice) return;

        // Sin soporte de grabación: ocultar el botón en vez de fallar.
        if (!navigator.mediaDevices || !window.MediaRecorder) {
            btnVoice.style.display = 'none';
            return;
        }

        const micIcon = btnVoice.querySelector('i');
        let mediaRecorder = null;
        let chunks = [];
        let stream = null;
        let recording = false;

        function setStatus(text) { voiceStatus.textContent = text || ''; }
        function setRecordingUI(active) {
            recording = active;
            btnVoice.classList.toggle('recording', active);
            if (micIcon) micIcon.className = active ? 'fas fa-stop' : 'fas fa-microphone';
            btnVoice.title = active ? 'Detener y enviar' : 'Preguntar por voz';
        }

        async function startRecording() {
            setStatus('');
            try {
                stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            } catch (e) {
                setStatus('No se pudo acceder al micrófono: ' + e.message);
                return;
            }
            const mime = MediaRecorder.isTypeSupported('audio/webm') ? 'audio/webm' : '';
            mediaRecorder = mime ? new MediaRecorder(stream, { mimeType: mime }) : new MediaRecorder(stream);
            chunks = [];
            mediaRecorder.ondataavailable = (e) => { if (e.data.size > 0) chunks.push(e.data); };
            mediaRecorder.onstop = onRecordingStop;
            mediaRecorder.start();
            setRecordingUI(true);
            setStatus('Grabando… pulsa de nuevo para enviar.');
        }

        function stopRecording() {
            if (!mediaRecorder || mediaRecorder.state === 'inactive') return;
            mediaRecorder.stop();
            setRecordingUI(false);
        }

        async function onRecordingStop() {
            if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
            const blob = new Blob(chunks, { type: 'audio/webm' });
            if (blob.size === 0) {
                setStatus('No se capturó audio. Intenta de nuevo.');
                return;
            }
            await transcribeAndSubmit(blob);
        }

        async function transcribeAndSubmit(blob) {
            btnVoice.disabled = true;
            setStatus('Transcribiendo tu pregunta…');

            const form = new FormData();
            form.append('file', blob, 'pregunta.webm');
            form.append('formato_respuesta', 'texto'); // solo texto: no necesitamos audio TTS

            try {
                const resp = await fetch('{{ route("voz.consulta", [], false) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: form
                });
                const result = await resp.json();
                const texto = result.success && result.data
                    ? (result.data.pregunta_transcrita || '').trim()
                    : '';

                if (!texto) {
                    setStatus(result.error || result.message || 'No se entendió el audio. Intenta de nuevo.');
                    return;
                }

                questionInput.value = texto;
                setStatus('');
                // Enviar por el flujo normal de consulta documental.
                document.getElementById('query-form').requestSubmit();
            } catch (e) {
                setStatus('Error de conexión con el servicio de voz: ' + e.message);
            } finally {
                btnVoice.disabled = false;
            }
        }

        btnVoice.addEventListener('click', () => {
            if (recording) stopRecording();
            else startRecording();
        });
    }

    function setupDeepReasoningToggle() {
        const checkbox = document.getElementById('deep-reasoning');
        checkbox.addEventListener('change', function() {
            useDeepReasoning = this.checked;
            updateQueryModeDescription();
        });
    }

    function setupClearDocumentButton() {
        document.getElementById('clear-doc').addEventListener('click', function() {
            selectedDocumentId = null;
            selectedDocumentName = '';
            document.getElementById('selected-doc-banner').classList.add('hidden');

            document.querySelectorAll('.document-item').forEach(item => {
                item.classList.remove('bg-blue-100', 'dark:bg-blue-900', 'border-2', 'border-blue-500');
            });

            updateQueryModeDescription();
        });
    }

    function updateQueryModeDescription() {
        const description = document.getElementById('query-mode-description');

        if (useDeepReasoning) {
            description.textContent = 'Razonamiento profundo · IA externa (hasta 20 chunks)';
        } else {
            description.textContent = 'Consulta rápida · IA externa (hasta 3 chunks)';
        }
    }

    function showSelectedDocument(id, name) {
        selectedDocumentId = id;
        selectedDocumentName = name;
        document.getElementById('selected-doc-name').textContent = name;
        document.getElementById('selected-doc-banner').classList.remove('hidden');
        updateQueryModeDescription();
    }

    document.getElementById('tab-all').addEventListener('click', function() {
        setActiveTab('all');
        loadDocuments('all');
    });
    document.getElementById('tab-recent').addEventListener('click', function() {
        setActiveTab('recent');
        loadDocuments('recent');
    });

    function setActiveTab(tab) {
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
    }

    async function loadDocuments(type) {
        const container = document.getElementById('documents-list');
        container.innerHTML = '<div class="flex items-center justify-center py-8"><div class="eia-spinner"></div></div>';

        try {
            const endpoint = type === 'recent'
                ? '{{ route("document-bot.simple.recent-documents", [], false) }}'
                : '{{ route("document-bot.simple.documents", [], false) }}';

            const response = await fetch(endpoint);
            const result = await response.json();

            if (result.success && result.data && result.data.documentos) {
                displayDocuments(result.data.documentos);
            } else if (result.success && result.data && Array.isArray(result.data)) {
                // Fallback: si la API devuelve el array directo en data
                displayDocuments(result.data);
            } else {
                const errMsg = result.error || result.message || 'No se encontraron documentos';
                const status = result.status ? ` (HTTP ${result.status})` : '';
                container.innerHTML = `
                    <div class="doc-error" style="margin: 0;">
                        <div class="flex items-start gap-3">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="color: var(--eia-red); flex-shrink: 0; margin-top: 2px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 110 18 9 9 0 010-18z"/>
                            </svg>
                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase tracking-widest" style="color: var(--eia-red);">No se pudo conectar al servicio</p>
                                <p class="text-xs text-slate-700 mt-1 break-words">${errMsg}${status}</p>
                                <p class="text-[11px] text-slate-500 mt-2">Verifica conexión con la API de bots o contacta al administrador.</p>
                            </div>
                        </div>
                    </div>
                `;
                console.error('Error API bots:', result);
            }
        } catch (error) {
            container.innerHTML = `
                <div class="doc-error" style="margin: 0;">
                    <div class="flex items-start gap-3">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="color: var(--eia-red); flex-shrink: 0; margin-top: 2px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 3a9 9 0 110 18 9 9 0 010-18z"/>
                        </svg>
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-widest" style="color: var(--eia-red);">Error de red</p>
                            <p class="text-xs text-slate-700 mt-1 break-words">${error.message}</p>
                        </div>
                    </div>
                </div>
            `;
            console.error('Error fetch:', error);
        }
    }

    function displayDocuments(documents) {
        const container = document.getElementById('documents-list');
        if (documents.length === 0) {
            container.innerHTML = '<p class="text-sm text-slate-500 text-center py-6">No hay documentos disponibles</p>';
            return;
        }

        container.innerHTML = documents.map(doc => {
            const previewUrl = doc.preview_url || null;
            const downloadUrl = doc.download_url || null;

            return `
                <div class="document-item p-3"
                     data-doc-id="${doc.id}" data-doc-name="${doc.title.replace(/'/g, "\\'")}">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-start flex-1 min-w-0 cursor-pointer"
                             onclick="selectDocument(${doc.id}, '${doc.title.replace(/'/g, "\\'")}')">
                            <i class="fas fa-file-pdf mt-1 mr-2"></i>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate">${doc.title}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 ml-1">
                            ${previewUrl ? `
                                <button onclick="previewDocument('${previewUrl}', '${doc.title.replace(/'/g, "\\'")}, event')"
                                        class="doc-action-btn preview" title="Previsualizar">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            ` : ''}
                            ${downloadUrl ? `
                                <button onclick="downloadDocument('${downloadUrl}', '${doc.title.replace(/'/g, "\\'")}, event')"
                                        class="doc-action-btn download" title="Descargar">
                                    <i class="fas fa-download text-xs"></i>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function previewDocument(url, title, event) {
        if (event) event.stopPropagation();
        if (!url) { alert('URL de previsualización no disponible'); return; }
        const link = document.createElement('a');
        link.href = url; link.target = '_blank'; link.rel = 'noopener noreferrer';
        document.body.appendChild(link); link.click(); document.body.removeChild(link);
    }

    function downloadDocument(url, title, event) {
        if (event) event.stopPropagation();
        if (!url) { alert('URL de descarga no disponible'); return; }
        const link = document.createElement('a');
        link.href = url; link.target = '_blank'; link.rel = 'noopener noreferrer'; link.download = title;
        document.body.appendChild(link); link.click(); document.body.removeChild(link);
    }

    function selectDocument(id, title) {
        document.querySelectorAll('.document-item').forEach(item => {
            item.classList.remove('bg-blue-100', 'dark:bg-blue-900', 'border-2', 'border-blue-500');
        });
        const selectedItem = document.querySelector(`[data-doc-id="${id}"]`);
        if (selectedItem) {
            selectedItem.classList.add('bg-blue-100', 'border-blue-500');
        }
        showSelectedDocument(id, title);
    }

    document.getElementById('query-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const question = document.getElementById('question-input').value;
        if (!question.trim()) return;

        showLoading();
        hideResults();
        hideError();
        GPTAvatar.stop();
        GPTAvatar.setState('thinking');

        try {
            let endpoint, payload;

            if (useDeepReasoning) {
                endpoint = '{{ route("document-bot.advanced.deep-reasoning", [], false) }}';
                payload = { pregunta: question, k: 20 };
            } else {
                endpoint = '{{ route("document-bot.advanced.quick-query", [], false) }}';
                payload = { pregunta: question };
            }

            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();
            hideLoading();
            GPTAvatar.setState('idle');

            if (result.success && result.data) {
                displayResults(result.data);
            } else {
                showError(result.error || result.message || 'Error en la consulta');
            }
        } catch (error) {
            hideLoading();
            GPTAvatar.setState('idle');
            showError('Error de conexión: ' + error.message);
            console.error('Error:', error);
        }
    });

    document.getElementById('semantic-search-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const query = document.getElementById('semantic-query').value;
        if (!query.trim()) return;

        const resultsContainer = document.getElementById('search-results');
        resultsContainer.innerHTML = '<div class="flex items-center justify-center py-4"><div class="eia-spinner"></div></div>';

        try {
            const response = await fetch('{{ route("document-bot.advanced.semantic-search", [], false) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ query: query, k: 5 })
            });

            const result = await response.json();

            if (result.success && result.data && result.data.resultados) {
                displaySearchResults(result.data.resultados);
            } else {
                resultsContainer.innerHTML = '<p class="text-sm text-slate-500 text-center py-3">No se encontraron resultados</p>';
            }
        } catch (error) {
            resultsContainer.innerHTML = '<p class="text-sm text-red-600 text-center py-3">Error en la búsqueda</p>';
            console.error('Error:', error);
        }
    });

    function displaySearchResults(results) {
        const container = document.getElementById('search-results');
        if (results.length === 0) {
            container.innerHTML = '<p class="text-sm text-slate-500 text-center py-3">No se encontraron resultados</p>';
            return;
        }

        container.innerHTML = results.map(r => {
            const previewUrl = r.preview_url || null;
            const downloadUrl = r.download_url || null;

            return `
                <div class="sem-result">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-900">${r.title}</p>
                            <p class="text-xs text-slate-500 mt-1">${r.preview ? r.preview.substring(0, 100) + '...' : ''}</p>
                        </div>
                        <div class="flex items-center gap-2 ml-2">
                            ${r.score ? `<span class="sem-score">${(r.score * 100).toFixed(0)}%</span>` : ''}
                            ${previewUrl ? `
                                <button onclick="previewDocument('${previewUrl}', '${r.title.replace(/'/g, "\\\\'")}, event')"
                                        class="doc-action-btn preview" title="Previsualizar">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            ` : ''}
                            ${downloadUrl ? `
                                <button onclick="downloadDocument('${downloadUrl}', '${r.title.replace(/'/g, "\\\\'")}, event')"
                                        class="doc-action-btn download" title="Descargar">
                                    <i class="fas fa-download text-xs"></i>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function displayResults(data) {
        const container = document.getElementById('results-container');
        const content = document.getElementById('response-content');
        const timeSpan = document.getElementById('response-time');

        content.textContent = data.respuesta;
        timeSpan.textContent = data.tiempo_respuesta ? `${data.tiempo_respuesta.toFixed(2)}s` : '';

        container.classList.remove('hidden');
        container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function showLoading() { document.getElementById('loading-indicator').classList.remove('hidden'); }
    function hideLoading() { document.getElementById('loading-indicator').classList.add('hidden'); }
    function showResults() { document.getElementById('results-container').classList.remove('hidden'); }
    function hideResults() { document.getElementById('results-container').classList.add('hidden'); }
    function showError(message) {
        document.getElementById('error-text').textContent = message;
        document.getElementById('error-message').classList.remove('hidden');
    }
    function hideError() { document.getElementById('error-message').classList.add('hidden'); }

    document.getElementById('btn-health').addEventListener('click', async function() {
        try {
            const endpoint = '{{ route("document-bot.advanced.health", [], false) }}';

            const response = await fetch(endpoint);
            const result = await response.json();

            if (result.success && result.data) {
                const data = result.data;
                let message = 'IA externa\n\n';

                message += `Estado: ${data.status}\n`;
                message += `IA Disponible: ${data.ia_disponible ? 'Sí' : 'No'}\n`;
                message += `ChromaDB: ${data.chromadb_disponible ? 'Sí' : 'No'}\n`;
                message += `Paperless: ${data.paperless_conectado ? 'Sí' : 'No'}\n`;
                message += `Total Documentos: ${data.total_documentos}\n`;

                if (data.total_vectores) {
                    message += `Total Vectores: ${data.total_vectores}\n`;
                }
                alert(message);
            } else {
                alert('Error al verificar estado del sistema\n\n' + (result.error || 'Error desconocido'));
            }
        } catch (error) {
            alert('Error de conexión\n\n' + error.message);
        }
    });

    document.getElementById('btn-stats').addEventListener('click', async function() {
        try {
            const response = await fetch('{{ route("document-bot.advanced.stats", [], false) }}');
            const result = await response.json();

            if (result.success && result.data) {
                let message = 'Estadísticas del sistema\n\n';
                message += `Documentos: ${result.data.total_documentos}\n`;
                message += `Vectores: ${result.data.total_vectores}\n`;
                message += `Modo: ${result.data.modo}\n`;
                message += `Modelo Rápido: ${result.data.modelo_rapido}\n`;
                message += `Modelo Razonamiento: ${result.data.modelo_razonamiento}\n`;
                alert(message);
            } else {
                alert('Error al obtener estadísticas');
            }
        } catch (error) {
            alert('Error de conexión: ' + error.message);
        }
    });
</script>
@endpush
