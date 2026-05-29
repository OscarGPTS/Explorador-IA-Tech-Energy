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
    .news-hero {
        background:
            radial-gradient(1000px 280px at 92% -40%, rgba(217, 119, 6, 0.18), transparent 60%),
            radial-gradient(800px 260px at 5% 130%, rgba(185, 28, 28, 0.22), transparent 60%),
            linear-gradient(180deg, #0F1419 0%, #1A1F26 100%);
        color: #F8FAFC;
        border-bottom: 1px solid var(--eia-graphite);
        position: relative;
    }
    .news-hero::after {
        content: '';
        position: absolute;
        left: 0; right: 0; bottom: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--eia-red) 0%, var(--eia-gold) 100%);
        opacity: 0.85;
    }
    .news-back {
        width: 38px; height: 38px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        background: rgba(255, 255, 255, 0.04);
        display: inline-flex; align-items: center; justify-content: center;
        color: #E2E8F0;
        transition: all .2s ease;
    }
    .news-back:hover {
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

    /* Botón Personalizar (oscuro corporativo) */
    .news-personalize {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 18px;
        border: 1px solid rgba(255, 255, 255, 0.22);
        background: rgba(255, 255, 255, 0.06);
        color: #FFFFFF;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.02em;
        transition: all .2s ease;
        cursor: pointer;
    }
    .news-personalize:hover {
        background: rgba(217, 119, 6, 0.15);
        border-color: var(--eia-gold);
    }

    /* Tabs */
    .news-tabs-wrap {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 12px;
        padding: 6px;
        display: inline-flex;
        gap: 4px;
        flex-wrap: wrap;
    }
    .tab-button {
        position: relative;
        padding: 9px 16px;
        font-size: 12.5px;
        font-weight: 600;
        letter-spacing: 0.02em;
        color: var(--eia-slate);
        border-radius: 8px;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: color .2s ease, background .2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .tab-button:hover { color: var(--eia-black); background: #F1F5F9; }
    .tab-button.active {
        color: #FFFFFF;
        background: var(--eia-black);
    }
    .tab-button.active::after {
        content: '';
        position: absolute;
        left: 12px; right: 12px; bottom: 3px;
        height: 2px;
        background: var(--eia-gold);
        border-radius: 2px;
    }
    .tab-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 18px;
        padding: 0 6px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.04em;
        background: #FEF2F2;
        color: var(--eia-red);
        border: 1px solid #FECACA;
    }
    .tab-button.active .tab-count {
        background: rgba(217, 119, 6, 0.18);
        color: var(--eia-gold-soft);
        border-color: rgba(217, 119, 6, 0.4);
    }

    /* CARD */
    .news-card {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 14px;
        overflow: hidden;
        transition: border-color .25s ease, box-shadow .25s ease, transform .25s ease;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .news-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--eia-red);
        opacity: 0;
        transition: opacity .25s ease;
        z-index: 3;
    }
    .news-card:hover {
        border-color: #94A3B8;
        box-shadow: 0 18px 36px -22px rgba(15, 20, 25, 0.4);
        transform: translateY(-4px);
    }
    .news-card:hover::before { opacity: 1; }

    .news-media {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9;
        overflow: hidden;
        background: linear-gradient(135deg, #1F2937 0%, #0F1419 100%);
    }
    .news-image {
        position: absolute;
        inset: 0;
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform .5s ease;
        filter: saturate(0.92) contrast(1.02);
    }
    .news-card:hover .news-image { transform: scale(1.04); }
    .news-media::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 20, 25, 0) 55%, rgba(15, 20, 25, 0.55) 100%);
    }
    .news-media-empty {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.25);
    }

    .news-source-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        padding: 5px 10px;
        border-radius: 999px;
        background: rgba(15, 20, 25, 0.85);
        color: #FFFFFF;
        backdrop-filter: blur(6px);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }
    .news-source-badge .dot {
        width: 5px; height: 5px;
        border-radius: 50%;
        background: var(--eia-gold);
    }

    .news-body {
        padding: 18px 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        flex: 1;
    }
    .news-title {
        font-size: 15.5px;
        font-weight: 600;
        color: var(--eia-black);
        letter-spacing: -0.01em;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .news-desc {
        font-size: 13px;
        color: var(--eia-slate);
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-foot {
        margin-top: auto;
        padding: 12px 20px;
        border-top: 1px solid var(--eia-border);
        background: #FAFAFB;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }
    .news-foot-meta {
        font-size: 11px;
        color: var(--eia-mute);
        letter-spacing: 0.04em;
    }
    .news-cta {
        font-size: 12px;
        font-weight: 600;
        color: var(--eia-black);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0;
    }
    .news-cta svg { transition: transform .25s ease; }
    .news-card:hover .news-cta svg { transform: translateX(3px); }

    /* Empty state */
    .news-empty-state {
        background: var(--eia-surface);
        border: 1px dashed #CBD5E1;
        border-radius: 14px;
        padding: 60px 28px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 12px;
        color: var(--eia-mute);
    }
    .news-empty-icon {
        width: 56px; height: 56px;
        border-radius: 14px;
        background: #F1F5F9;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--eia-mute);
    }

    /* Section header */
    .news-section-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 18px;
        gap: 16px;
        flex-wrap: wrap;
    }
    .news-section-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: var(--eia-mute);
    }
    .news-section-sub {
        font-size: 13px;
        color: var(--eia-slate);
        margin-top: 4px;
    }

    /* Modal restyle */
    .eia-modal-shell {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 30px 60px -20px rgba(15, 20, 25, 0.4);
    }
    .eia-modal-head {
        padding: 22px 26px;
        border-bottom: 1px solid var(--eia-border);
        background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%);
    }
    .eia-modal-title {
        font-size: 18px;
        font-weight: 600;
        color: var(--eia-black);
        letter-spacing: -0.01em;
    }
    .eia-modal-sub {
        font-size: 12.5px;
        color: var(--eia-mute);
        margin-top: 4px;
    }
    .eia-modal-close {
        width: 34px; height: 34px;
        border-radius: 8px;
        background: #F1F5F9;
        color: var(--eia-slate);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--eia-border);
        cursor: pointer;
        transition: all .2s ease;
    }
    .eia-modal-close:hover {
        background: var(--eia-black);
        color: #FFFFFF;
        border-color: var(--eia-black);
    }

    /* Category checkbox option */
    .cat-option {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border: 1px solid var(--eia-border);
        border-radius: 10px;
        background: #FFFFFF;
        cursor: pointer;
        transition: all .2s ease;
    }
    .cat-option:hover {
        border-color: #94A3B8;
        background: #F8FAFC;
    }
    .cat-option input {
        accent-color: var(--eia-red);
        width: 16px; height: 16px;
    }
    .cat-option:has(input:checked) {
        border-color: var(--eia-black);
        background: #F8FAFC;
        box-shadow: inset 3px 0 0 var(--eia-red);
    }
    .cat-option-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--eia-black);
    }

    /* Buttons */
    .btn-eia-primary {
        background: var(--eia-black);
        color: #FFFFFF;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 8px;
        border: 1px solid var(--eia-black);
        transition: all .2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-eia-primary:hover {
        background: #1F2937;
        border-color: var(--eia-gold);
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15);
    }
    .btn-eia-secondary {
        background: #FFFFFF;
        color: var(--eia-slate);
        font-size: 13px;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 8px;
        border: 1px solid var(--eia-border);
        transition: all .2s ease;
        cursor: pointer;
    }
    .btn-eia-secondary:hover {
        background: #F1F5F9;
        color: var(--eia-black);
    }
    .btn-external {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: var(--eia-red);
        color: #FFFFFF;
        font-size: 13px;
        font-weight: 600;
        border-radius: 8px;
        border: 1px solid var(--eia-red);
        transition: all .2s ease;
    }
    .btn-external:hover {
        background: #991B1B;
        box-shadow: 0 6px 16px -8px rgba(185, 28, 28, 0.6);
    }

    /* Detail modal extras */
    .detail-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        border-radius: 999px;
        background: #F1F5F9;
        color: var(--eia-slate);
        border: 1px solid var(--eia-border);
    }
    .detail-chip.red { background: #FEF2F2; color: var(--eia-red); border-color: #FECACA; }
    .detail-quote {
        background: #FAFAFB;
        border: 1px solid var(--eia-border);
        border-left: 3px solid var(--eia-gold);
        border-radius: 10px;
        padding: 18px 22px;
        color: var(--eia-slate);
        font-size: 14px;
        line-height: 1.65;
    }
    .detail-source-band {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 16px 18px;
        background: linear-gradient(135deg, #0F1419 0%, #1F2937 100%);
        color: #F8FAFC;
        border-radius: 12px;
        border: 1px solid var(--eia-graphite);
    }
    .detail-source-band .icon-wrap {
        width: 38px; height: 38px;
        background: rgba(217, 119, 6, 0.18);
        border: 1px solid rgba(217, 119, 6, 0.4);
        border-radius: 10px;
        display: inline-flex; align-items: center; justify-content: center;
        color: var(--eia-gold-soft);
    }

    /* Fade */
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
    <section class="news-hero px-4 sm:px-8 lg:px-12 py-10">
        <div class="max-w-7xl mx-auto flex items-start justify-between gap-6 flex-wrap">
            <div class="flex items-center gap-4">
                <a href="/" class="news-back" aria-label="Volver al inicio">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                    </svg>
                </a>
                <div>
                    <span class="eia-eyebrow">Inteligencia sectorial</span>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Noticias del sector</h1>
                    <p class="mt-1 text-sm text-slate-300 max-w-2xl">
                        Mercado energético, industria petrolera y novedades estratégicas en tiempo real.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                @permission('manage-news')
                    <a href="{{ route('admin.news.index') }}" class="news-personalize" style="background:#0F1419;color:#fff;border-color:#0F1419;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Administrar
                    </a>
                @endpermission
                <button data-modal-target="top-right-modal" data-modal-toggle="top-right-modal"
                        class="news-personalize" type="button">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M6 12h12M10 17h4"/>
                    </svg>
                    Personalizar feed
                </button>
            </div>
        </div>
    </section>

    {{-- Modal de personalización --}}
    <div id="top-right-modal" data-modal-placement="center" tabindex="-1"
         class="fixed inset-0 z-50 hidden w-full h-full" style="background: rgba(15, 20, 25, 0.55); backdrop-filter: blur(4px);">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="eia-modal-shell w-full max-w-2xl">
                <div class="eia-modal-head flex items-start justify-between">
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.22em] font-semibold" style="color: var(--eia-red);">Preferencias</p>
                        <h3 class="eia-modal-title mt-1">Personaliza tu feed de noticias</h3>
                        <p class="eia-modal-sub">Selecciona las categorías relevantes para tu rol.</p>
                    </div>
                    <button type="button" class="eia-modal-close" data-modal-hide="top-right-modal" aria-label="Cerrar">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    <form action="{{ route('news.updatePreferences') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($news as $id => $name)
                                <label class="cat-option">
                                    <input type="checkbox" name="news[]" value="{{ $id }}"
                                           @if(in_array($id, $userNewsIds)) checked @endif>
                                    <span class="cat-option-label">{{ $name }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="flex justify-end gap-3 pt-5 border-t border-slate-200">
                            <button data-modal-hide="top-right-modal" type="button" class="btn-eia-secondary">
                                Cancelar
                            </button>
                            <button type="submit" class="btn-eia-primary">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Guardar preferencias
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <section class="px-4 sm:px-8 lg:px-12 pt-7">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4 flex-wrap">
            <div class="news-tabs-wrap eia-fade eia-d1" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
                @foreach ($newsData as $index => $category)
                    <button class="tab-button {{ $index === 0 ? 'active' : '' }}"
                            id="tab-{{ $category->id }}"
                            data-tab="content-{{ $category->id }}"
                            data-tabs-target="#content-{{ $category->id }}"
                            type="button" role="tab"
                            aria-controls="content-{{ $category->id }}"
                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                        <span>{{ $category->category }}</span>
                        @if(count($category->news) > 0)
                            <span class="tab-count">{{ count($category->news) }}</span>
                        @endif
                    </button>
                @endforeach
            </div>
            <p class="text-xs text-slate-500 tracking-wider uppercase font-semibold eia-fade eia-d1">
                Actualizado · {{ now()->locale('es')->translatedFormat('d M Y') }}
            </p>
        </div>
    </section>

    {{-- Contenido --}}
    <section class="px-4 sm:px-8 lg:px-12 py-8">
        <div class="max-w-7xl mx-auto">

            <div id="default-tab-content">
                @foreach ($newsData as $index => $category)
                    <div class="{{ $index === 0 ? '' : 'hidden' }}"
                         id="content-{{ $category->id }}" role="tabpanel" aria-labelledby="tab-{{ $category->id }}">

                        <div class="news-section-head eia-fade eia-d2">
                            <div>
                                <p class="news-section-title">{{ $category->category }} · Feed</p>
                                <p class="news-section-sub">{{ count($category->news) }} {{ count($category->news) === 1 ? 'publicación disponible' : 'publicaciones disponibles' }}</p>
                            </div>
                        </div>

                        @if(count($category->news) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                                @foreach ($category->news as $itemIndex => $item)
                                    <article class="news-card eia-fade eia-d{{ ($itemIndex % 4) + 1 }}">
                                        <div class="news-media">
                                            <span class="news-source-badge"><span class="dot"></span>{{ ucfirst($item->source) }}</span>
                                            @if($item->image_url)
                                                <img class="news-image"
                                                     src="{{ $item->image_url }}"
                                                     alt="{{ $item->title }}"
                                                     loading="lazy">
                                            @else
                                                <div class="news-media-empty">
                                                    <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 17V7a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2zM4 17l4-5 3 4 2-2 7 7"/>
                                                        <circle cx="8.5" cy="9.5" r="1.5"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="news-body">
                                            <h4 class="news-title">{{ $item->title }}</h4>
                                            <p class="news-desc">{{ $item->description }}</p>
                                        </div>

                                        <div class="news-foot">
                                            <span class="news-foot-meta">{{ $item->created_at->diffForHumans() }}</span>
                                            <button class="news-cta" data-modal-target="modal-{{ $item->id }}" data-modal-toggle="modal-{{ $item->id }}" type="button">
                                                Ver detalle
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </article>

                                    {{-- Modal detalle --}}
                                    <div id="modal-{{ $item->id }}" tabindex="-1" aria-hidden="true"
                                         class="hidden fixed inset-0 z-50 w-full h-full" style="background: rgba(15, 20, 25, 0.55); backdrop-filter: blur(4px);">
                                        <div class="flex items-start justify-center min-h-screen px-4 py-8">
                                            <div class="eia-modal-shell w-full max-w-4xl max-h-[90vh] flex flex-col">
                                                <div class="eia-modal-head flex items-start justify-between">
                                                    <div class="flex-1 pr-4">
                                                        <div class="flex items-center gap-2 flex-wrap">
                                                            <span class="detail-chip red">{{ ucfirst($item->source) }}</span>
                                                            <span class="detail-chip">{{ $item->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <h3 class="eia-modal-title mt-3" style="font-size: 22px; line-height: 1.3;">{{ $item->title }}</h3>
                                                    </div>
                                                    <button type="button" class="eia-modal-close" data-modal-hide="modal-{{ $item->id }}" aria-label="Cerrar">
                                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                        </svg>
                                                    </button>
                                                </div>

                                                <div class="overflow-y-auto p-6 space-y-6" style="flex: 1;">
                                                    @if ($item->image_url)
                                                        <div class="rounded-xl overflow-hidden border border-slate-200" style="aspect-ratio: 16 / 9;">
                                                            <img class="w-full h-full object-cover"
                                                                 src="{{ $item->image_url }}"
                                                                 alt="{{ $item->title }}">
                                                        </div>
                                                    @endif

                                                    <p class="text-base text-slate-700 leading-relaxed">
                                                        {{ $item->description }}
                                                    </p>

                                                    <div class="detail-quote">
                                                        {{ $item->content }}
                                                    </div>

                                                    <div class="detail-source-band">
                                                        <div class="flex items-center gap-3">
                                                            <div class="icon-wrap">
                                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-semibold">Leer artículo completo</p>
                                                                <p class="text-xs text-slate-400">Fuente: {{ ucfirst($item->source) }}</p>
                                                            </div>
                                                        </div>
                                                        <a href="{{ $item->external_link }}" target="_blank" class="btn-external">
                                                            Ir al sitio
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="news-empty-state">
                                <div class="news-empty-icon">
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 10h6M7 14h10M7 18h7"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold uppercase tracking-widest text-slate-700">No hay noticias disponibles</p>
                                <p class="text-sm text-slate-500 max-w-md">
                                    Aún no tenemos publicaciones en esta categoría. Revisa más tarde o ajusta tus preferencias del feed.
                                </p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('[role="tabpanel"]');

    function switchTab(targetTab) {
        tabButtons.forEach(button => {
            button.classList.remove('active');
            button.setAttribute('aria-selected', 'false');
        });
        tabContents.forEach(content => content.classList.add('hidden'));

        const activeButton = document.querySelector(`[data-tab="${targetTab}"]`);
        if (activeButton) {
            activeButton.classList.add('active');
            activeButton.setAttribute('aria-selected', 'true');
        }
        const activeContent = document.getElementById(targetTab);
        if (activeContent) activeContent.classList.remove('hidden');
    }

    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const targetTab = this.getAttribute('data-tab');
            switchTab(targetTab);
        });
    });

    if (tabButtons.length > 0) {
        const firstTab = tabButtons[0].getAttribute('data-tab');
        switchTab(firstTab);
    }
});
</script>
@endsection
