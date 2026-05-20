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

    /* Hero corporativo */
    .rec-hero {
        background:
            radial-gradient(1000px 280px at 92% -40%, rgba(217, 119, 6, 0.18), transparent 60%),
            radial-gradient(800px 260px at 5% 130%, rgba(185, 28, 28, 0.22), transparent 60%),
            linear-gradient(180deg, #0F1419 0%, #1A1F26 100%);
        color: #F8FAFC;
        border-bottom: 1px solid var(--eia-graphite);
        position: relative;
    }
    .rec-hero::after {
        content: '';
        position: absolute;
        left: 0; right: 0; bottom: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--eia-red) 0%, var(--eia-gold) 100%);
        opacity: 0.85;
    }
    .rec-back {
        width: 38px; height: 38px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        background: rgba(255, 255, 255, 0.04);
        display: inline-flex; align-items: center; justify-content: center;
        color: #E2E8F0;
        transition: all .2s ease;
    }
    .rec-back:hover {
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

    /* Tabs */
    .rec-tabs {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 12px;
        padding: 6px;
        display: inline-flex;
        gap: 4px;
    }
    .rec-tab {
        position: relative;
        padding: 9px 18px;
        font-size: 12.5px;
        font-weight: 600;
        letter-spacing: 0.02em;
        color: var(--eia-slate);
        border-radius: 8px;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: color .2s ease, background .2s ease;
    }
    .rec-tab:hover { color: var(--eia-black); background: #F1F5F9; }
    .rec-tab.active {
        color: #FFFFFF;
        background: var(--eia-black);
    }
    .rec-tab.active::after {
        content: '';
        position: absolute;
        left: 12px; right: 12px; bottom: 3px;
        height: 2px;
        background: var(--eia-gold);
        border-radius: 2px;
    }

    /* Card */
    .rec-card {
        background: var(--eia-surface);
        border: 1px solid var(--eia-border);
        border-radius: 14px;
        overflow: hidden;
        transition: border-color .25s ease, box-shadow .25s ease, transform .25s ease;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .rec-card::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--eia-red);
        opacity: 0;
        transition: opacity .25s ease;
        z-index: 2;
    }
    .rec-card:hover {
        border-color: #94A3B8;
        box-shadow: 0 18px 36px -22px rgba(15, 20, 25, 0.4);
        transform: translateY(-4px);
    }
    .rec-card:hover::before { opacity: 1; }
    .rec-card.gold::before { background: var(--eia-gold); }
    .rec-card.black::before { background: var(--eia-black); }

    .rec-media {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9;
        overflow: hidden;
        background: linear-gradient(135deg, #1F2937 0%, #0F1419 100%);
    }
    .rec-image {
        position: absolute;
        inset: 0;
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform .5s ease;
        filter: saturate(0.92) contrast(1.02);
    }
    .rec-card:hover .rec-image { transform: scale(1.04); }
    .rec-media::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(15, 20, 25, 0) 55%, rgba(15, 20, 25, 0.55) 100%);
    }

    .rec-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        padding: 5px 10px;
        border-radius: 999px;
        background: rgba(15, 20, 25, 0.85);
        color: #FFFFFF;
        backdrop-filter: blur(6px);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }
    .rec-badge .dot {
        width: 5px; height: 5px;
        border-radius: 50%;
        background: var(--eia-gold);
    }

    .rec-body {
        padding: 22px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex: 1;
    }
    .rec-title {
        font-size: 17px;
        font-weight: 600;
        color: var(--eia-black);
        letter-spacing: -0.01em;
        line-height: 1.3;
    }
    .rec-desc {
        font-size: 13px;
        color: var(--eia-slate);
        line-height: 1.55;
    }
    .rec-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        font-size: 11.5px;
        color: var(--eia-mute);
    }
    .rec-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .rec-meta-item svg { width: 13px; height: 13px; color: var(--eia-mute); }

    .rec-foot {
        margin-top: auto;
        padding: 14px 22px;
        border-top: 1px solid var(--eia-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #FAFAFB;
    }
    .rec-cta {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--eia-black);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .rec-cta svg { transition: transform .25s ease; }
    .rec-card:hover .rec-cta svg { transform: translateX(3px); }

    /* Empty state placeholder */
    .rec-empty {
        background: var(--eia-surface);
        border: 1px dashed #CBD5E1;
        border-radius: 14px;
        padding: 28px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
        justify-content: center;
        min-height: 380px;
        color: var(--eia-mute);
    }
    .rec-empty .icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: #F1F5F9;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--eia-mute);
    }

    /* Section header */
    .rec-section-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 18px;
    }
    .rec-section-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: var(--eia-mute);
    }
    .rec-section-sub {
        font-size: 13px;
        color: var(--eia-slate);
        margin-top: 4px;
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
    <section class="rec-hero px-4 sm:px-8 lg:px-12 py-10">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center gap-4">
                <a href="/" class="rec-back" aria-label="Volver al inicio">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                    </svg>
                </a>
                <div>
                    <span class="eia-eyebrow">Inteligencia personalizada</span>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Recomendaciones</h1>
                    <p class="mt-1 text-sm text-slate-300 max-w-2xl">
                        Contenido curado para tu perfil ejecutivo en el sector energético.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Tabs --}}
    <section class="px-4 sm:px-8 lg:px-12 pt-7">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4 flex-wrap">
            <div class="rec-tabs eia-fade eia-d1" role="tablist">
                <button class="rec-tab active" role="tab" aria-selected="true" type="button">Marketing</button>
                {{-- Tabs futuras: --}}
                {{-- <button class="rec-tab" role="tab" type="button">Operaciones</button> --}}
                {{-- <button class="rec-tab" role="tab" type="button">Capacitación</button> --}}
            </div>
            <p class="text-xs text-slate-500 tracking-wider uppercase font-semibold eia-fade eia-d1">
                1 recomendación · actualizado hoy
            </p>
        </div>
    </section>

    {{-- Contenido --}}
    <section class="px-4 sm:px-8 lg:px-12 py-8">
        <div class="max-w-7xl mx-auto">

            <div class="rec-section-head eia-fade eia-d2">
                <div>
                    <p class="rec-section-title">Marketing · Industria</p>
                    <p class="rec-section-sub">Eventos y oportunidades estratégicas del sector petrolero.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">

                {{-- Card: Congreso Mexicano del Petróleo --}}
                <article class="rec-card gold eia-fade eia-d2">
                    <div class="rec-media">
                        <span class="rec-badge"><span class="dot"></span>Marketing</span>
                        <img class="rec-image"
                             src="{{ asset('storage/recommendations/congreso_petroleo.jpg') }}"
                             alt="Congreso Mexicano del Petróleo"
                             loading="lazy">
                    </div>
                    <div class="rec-body">
                        <h4 class="rec-title">Congreso Mexicano del Petróleo</h4>
                        <p class="rec-desc">
                            Encuentro estratégico de la industria petrolera nacional en el WTC, Boca del Río, Veracruz.
                        </p>
                        <div class="rec-meta">
                            <span class="rec-meta-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                03 – 06 jun 2026
                            </span>
                            <span class="rec-meta-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-7.5 8-13a8 8 0 10-16 0c0 5.5 8 13 8 13z"/><circle cx="12" cy="9" r="2.5"/></svg>
                                Veracruz, MX
                            </span>
                        </div>
                    </div>
                    <div class="rec-foot">
                        <span class="text-[10px] uppercase tracking-widest font-semibold text-slate-500">Evento corporativo</span>
                        <button type="button" onclick="document.getElementById('modal-cmp').classList.remove('hidden')" class="rec-cta">
                            Ver detalle
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </div>
                </article>

                {{-- Placeholder cards para mantener equilibrio visual del grid --}}
                <div class="rec-empty eia-fade eia-d3">
                    <div class="icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-600">Próximamente</p>
                    <p class="text-sm text-slate-500">Nuevas recomendaciones personalizadas según tu actividad y rol.</p>
                </div>
                <div class="rec-empty eia-fade eia-d3 hidden lg:flex">
                    <div class="icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.847a4.5 4.5 0 003.09 3.09L15.75 12l-2.847.813a4.5 4.5 0 00-3.09 3.091z"/></svg>
                    </div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-600">En curaduría</p>
                    <p class="text-sm text-slate-500">Nuestro equipo revisa contenido relevante para tu perfil.</p>
                </div>
                <div class="rec-empty eia-fade eia-d3 hidden xl:flex">
                    <div class="icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2 4 4 8-8 4 4"/></svg>
                    </div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-600">Inteligencia</p>
                    <p class="text-sm text-slate-500">Recomendaciones impulsadas por análisis de comportamiento.</p>
                </div>

            </div>
        </div>
    </section>

</div>

{{-- Modal: Congreso Mexicano del Petróleo --}}
<div id="modal-cmp" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(10,14,20,0.65);backdrop-filter:blur(4px);">
    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden" style="max-height:90vh;">

        {{-- Franja superior con imagen --}}
        <div class="relative h-52 overflow-hidden" style="background:#0F1419">
            <img src="{{ asset('storage/recommendations/congreso_petroleo.jpg') }}"
                 alt="Congreso Mexicano del Petróleo"
                 class="w-full h-full object-cover opacity-80">
            <div class="absolute inset-0" style="background:linear-gradient(180deg,rgba(15,20,25,0) 40%,rgba(15,20,25,0.85) 100%);"></div>
            <button type="button"
                    onclick="document.getElementById('modal-cmp').classList.add('hidden')"
                    class="absolute top-4 right-4 w-8 h-8 rounded-full flex items-center justify-center"
                    style="background:rgba(15,20,25,0.7);color:#fff;border:1px solid rgba(255,255,255,0.2);"
                    aria-label="Cerrar">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
            </button>
            <div class="absolute bottom-4 left-6 right-6">
                <span class="inline-block text-[10px] font-bold uppercase tracking-[0.2em] px-2.5 py-1 rounded-full mb-2"
                      style="background:rgba(217,119,6,0.9);color:#fff;">Marketing · Evento corporativo</span>
                <h2 class="text-xl font-semibold text-white leading-snug tracking-tight">
                    Congreso Mexicano del Petróleo 2026 | WTC Boca del Río, Veracruz
                </h2>
            </div>
        </div>

        {{-- Cuerpo --}}
        <div class="overflow-y-auto p-7 space-y-5" style="max-height:calc(90vh - 208px);">

            {{-- Fecha destacada --}}
            <div class="flex items-center gap-2.5 text-sm font-semibold" style="color:#B45309;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                03 al 06 de junio de 2026
            </div>

            <hr style="border-color:#E5E7EB;">

            <p class="text-sm leading-relaxed" style="color:#374151;">
                El Congreso Mexicano del Petróleo se ha consolidado como uno de los encuentros más relevantes de la industria
                energética y del sector Oil &amp; Gas en México y Latinoamérica, reuniendo a especialistas, empresas, instituciones
                y líderes del sector para impulsar el intercambio de conocimiento, innovación y desarrollo tecnológico.
            </p>

            <p class="text-sm leading-relaxed" style="color:#374151;">
                El evento contempla un amplio programa de conferencias técnicas, paneles especializados y una destacada
                exposición industrial en la que participan compañías nacionales e internacionales enfocadas en exploración,
                producción, mantenimiento, infraestructura y soluciones tecnológicas para la industria petrolera.
            </p>

            <p class="text-sm leading-relaxed" style="color:#374151;">
                Además de promover la actualización técnica y científica, el CMP representa una plataforma estratégica
                para fortalecer alianzas, generar oportunidades de negocio y fomentar la colaboración entre empresas,
                expertos y organismos vinculados al desarrollo energético del país.
            </p>
        </div>
    </div>
</div>

@endsection
