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
        flex-wrap: wrap;
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
    .rec-tab-count {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 18px; height: 18px; padding: 0 5px; margin-left: 6px;
        font-size: 10px; font-weight: 700; border-radius: 999px;
        background: #E2E8F0; color: var(--eia-slate); vertical-align: middle;
    }
    .rec-tab.active .rec-tab-count { background: var(--eia-gold); color: #fff; }
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

    /* Acciones admin sobre la card */
    .rec-admin-actions {
        position: absolute;
        top: 12px; right: 12px;
        z-index: 3;
        display: flex;
        gap: 6px;
    }
    .rec-admin-btn {
        width: 30px; height: 30px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(15, 20, 25, 0.85);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.18);
        cursor: pointer;
        transition: all .15s ease;
    }
    .rec-admin-btn:hover { background: var(--eia-gold); }
    .rec-admin-btn.danger:hover { background: var(--eia-red); }

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
        cursor: pointer;
        background: none; border: none;
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

    /* Botones admin */
    .eia-btn-primary {
        display: inline-flex; align-items: center; gap: 8px;
        background: var(--eia-black); color: #fff;
        font-size: 12.5px; font-weight: 600;
        padding: 10px 16px; border-radius: 10px;
        border: 1px solid var(--eia-black); cursor: pointer;
        transition: all .2s ease;
    }
    .eia-btn-primary:hover { background: var(--eia-gold); border-color: var(--eia-gold); }

    /* Form modal */
    .eia-field-label { font-size: 11.5px; font-weight: 600; color: var(--eia-slate); margin-bottom: 5px; display:block; letter-spacing:.02em; }
    .eia-input, .eia-textarea, .eia-select {
        width: 100%; font-size: 13px; color: var(--eia-black);
        border: 1px solid var(--eia-border); border-radius: 9px;
        padding: 9px 11px; background: #fff; transition: border-color .15s ease;
    }
    .eia-input:focus, .eia-textarea:focus, .eia-select:focus { outline: none; border-color: var(--eia-gold); }
    .eia-textarea { resize: vertical; min-height: 64px; }

    /* Flash */
    .eia-flash {
        background: #ECFDF5; border: 1px solid #A7F3D0; color: #065F46;
        padding: 11px 16px; border-radius: 10px; font-size: 13px; font-weight: 500;
        display:flex; align-items:center; gap:8px;
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
                <div class="flex-1">
                    <span class="eia-eyebrow">Desarrollo profesional</span>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Recomendaciones</h1>
                    <p class="mt-1 text-sm text-slate-300 max-w-2xl">
                        Congresos, cursos, capacitaciones y certificaciones del sector Oil &amp; Gas.
                    </p>
                </div>
                @if($canManage)
                    <button type="button" class="eia-btn-primary" onclick="openRecModal('create')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                        Nueva recomendación
                    </button>
                @endif
            </div>
        </div>
    </section>

    {{-- Flash --}}
    @if(session('status'))
        <div class="px-4 sm:px-8 lg:px-12 pt-5 max-w-7xl mx-auto w-full">
            <div class="eia-flash">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ session('status') }}
            </div>
        </div>
    @endif

    @php
        // Solo áreas (tipos) que tienen recomendaciones.
        $typesWithItems = $types->filter(fn($t) => isset($recommendations[$t->id]) && $recommendations[$t->id]->count() > 0)->values();
        // Todas las recomendaciones, más recientes primero (grid único filtrable).
        $allRecs = $recommendations->flatten()->sortByDesc('created_at')->values();
        $catsMeta = $typesWithItems->mapWithKeys(fn($t) => [$t->id => [
            'name' => $t->name,
            'desc' => $t->description,
            'count' => $recommendations[$t->id]->count(),
        ]]);
    @endphp

    {{-- Tabs --}}
    <section class="px-4 sm:px-8 lg:px-12 pt-7">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4 flex-wrap">
            <div class="rec-tabs eia-fade eia-d1" role="tablist">
                <button class="rec-tab active" role="tab" type="button" data-type="all"
                        onclick="filterRecs(this,'all')">
                    Todas
                    <span class="rec-tab-count">{{ $allRecs->count() }}</span>
                </button>
                @foreach($typesWithItems as $type)
                    <button class="rec-tab" role="tab" type="button" data-type="{{ $type->id }}"
                            onclick="filterRecs(this,'{{ $type->id }}')">
                        {{ $type->name }}
                        <span class="rec-tab-count">{{ $recommendations[$type->id]->count() }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Contenido --}}
    <section class="px-4 sm:px-8 lg:px-12 py-8">
        <div class="max-w-7xl mx-auto">

            @if($allRecs->count() > 0)
                <div class="rec-section-head eia-fade eia-d2">
                    <div>
                        <p class="rec-section-title" id="rec-head-title">Todas las recomendaciones</p>
                        <p class="rec-section-sub" id="rec-head-sub">Oportunidades de formación y eventos del sector Oil &amp; Gas para GPT Services.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5" id="rec-grid">
                    @foreach($allRecs as $rec)
                        @php
                            $img = $rec->image
                                ? asset('storage/recommendations/' . $rec->image)
                                : ($rec->image_url ?: null);
                        @endphp
                        <article class="rec-card gold eia-fade eia-d2 rec-item" data-type="{{ $rec->recommendation_type_id }}">
                            <div class="rec-media">
                                @if($rec->sub_area)
                                    <span class="rec-badge"><span class="dot"></span>{{ $rec->sub_area }}</span>
                                @endif
                                @if($canManage)
                                    <div class="rec-admin-actions">
                                        <button type="button" class="rec-admin-btn" title="Editar"
                                                onclick='openRecModal("edit", {{ $rec->id }})'>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button type="button" class="rec-admin-btn danger" title="Eliminar"
                                                onclick="confirmDeleteRec({{ $rec->id }}, @js($rec->title))">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                @endif
                                @if($img)
                                    <img class="rec-image" src="{{ $img }}" alt="{{ $rec->title }}" loading="lazy">
                                @endif
                            </div>
                            <div class="rec-body">
                                <h4 class="rec-title">{{ $rec->title }}</h4>
                                @if($rec->description)
                                    <p class="rec-desc">{{ \Illuminate\Support\Str::limit($rec->description, 140) }}</p>
                                @endif
                            </div>
                            <div class="rec-foot">
                                <span class="text-[10px] uppercase tracking-widest font-semibold text-slate-500">
                                    {{ $rec->is_scraped ? 'Scraping · ' . ($rec->source ?? 'web') : 'Curado' }}
                                </span>
                                <button type="button" class="rec-cta" onclick="openRecDetail({{ $rec->id }})">
                                    Ver detalle
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="rec-empty eia-fade eia-d2" id="rec-no-results" style="min-height:200px;display:none;">
                    <p class="text-sm font-semibold text-slate-700">No hay recomendaciones en esta categoría todavía.</p>
                </div>
            @else
                <div class="rec-empty eia-fade eia-d2" style="min-height:240px;">
                    <div class="icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.847a4.5 4.5 0 003.09 3.09L15.75 12l-2.847.813a4.5 4.5 0 00-3.09 3.091z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Aún no hay recomendaciones.</p>
                    @if($canManage)
                        <p class="text-sm text-slate-500">Crea la primera con el botón "Nueva recomendación".</p>
                    @else
                        <p class="text-sm text-slate-500">Nuestro equipo curará contenido relevante para tu perfil.</p>
                    @endif
                </div>
            @endif

        </div>
    </section>
</div>

<script>window.REC_CATS = @json($catsMeta);</script>

{{-- ====== Datos para JS (detalle / edición) ====== --}}
@php
    $recsForJs = $recommendations->flatten()->mapWithKeys(fn($r) => [$r->id => [
        'id' => $r->id,
        'title' => $r->title,
        'description' => $r->description,
        'content' => $r->content,
        'recommendation_type_id' => $r->recommendation_type_id,
        'sub_area' => $r->sub_area,
        'external_link' => $r->external_link,
        'image_url' => $r->image_url,
        'image' => $r->image ? asset('storage/recommendations/' . $r->image) : ($r->image_url ?: null),
        'type_name' => optional($r->recommendationType)->name,
    ]]);
@endphp
<script>window.RECS = @json($recsForJs);</script>

{{-- ====== Modal detalle ====== --}}
<div id="rec-detail-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(10,14,20,0.65);backdrop-filter:blur(4px);">
    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden" style="max-height:90vh;">
        <div class="relative h-52 overflow-hidden" style="background:#0F1419">
            <img id="rec-detail-img" src="" alt="" class="w-full h-full object-cover opacity-80">
            <div class="absolute inset-0" style="background:linear-gradient(180deg,rgba(15,20,25,0) 40%,rgba(15,20,25,0.85) 100%);"></div>
            <button type="button" onclick="document.getElementById('rec-detail-modal').classList.add('hidden')"
                    class="absolute top-4 right-4 w-8 h-8 rounded-full flex items-center justify-center"
                    style="background:rgba(15,20,25,0.7);color:#fff;border:1px solid rgba(255,255,255,0.2);" aria-label="Cerrar">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
            </button>
            <div class="absolute bottom-4 left-6 right-6">
                <span id="rec-detail-badge" class="inline-block text-[10px] font-bold uppercase tracking-[0.2em] px-2.5 py-1 rounded-full mb-2" style="background:rgba(217,119,6,0.9);color:#fff;"></span>
                <h2 id="rec-detail-title" class="text-xl font-semibold text-white leading-snug tracking-tight"></h2>
            </div>
        </div>
        <div class="overflow-y-auto p-7 space-y-4" style="max-height:calc(90vh - 208px);">
            <p id="rec-detail-desc" class="text-sm leading-relaxed font-medium" style="color:#1F2937;"></p>
            <p id="rec-detail-content" class="text-sm leading-relaxed whitespace-pre-line" style="color:#374151;"></p>
            <a id="rec-detail-link" href="#" target="_blank" rel="noopener" class="hidden items-center gap-2 text-sm font-semibold" style="color:#B45309;">
                Ver fuente original
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5h5v5M19 5l-9 9M10 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-4"/></svg>
            </a>
        </div>
    </div>
</div>

@if($canManage)
{{-- ====== Modal crear/editar ====== --}}
<div id="rec-form-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(10,14,20,0.65);backdrop-filter:blur(4px);">
    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden" style="max-height:92vh;">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color:var(--eia-border);">
            <h2 id="rec-form-title" class="text-base font-semibold text-slate-900">Nueva recomendación</h2>
            <button type="button" onclick="document.getElementById('rec-form-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700">
                <svg width="18" height="18" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
            </button>
        </div>
        <form id="rec-form" method="POST" enctype="multipart/form-data" action="{{ route('admin.recommendations.store') }}">
            @csrf
            <input type="hidden" name="_method" id="rec-form-method" value="POST">
            <div class="overflow-y-auto p-6 space-y-4" style="max-height:calc(92vh - 130px);">
                <div>
                    <label class="eia-field-label">Título *</label>
                    <input type="text" name="title" id="rec-f-title" class="eia-input" required maxlength="255">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="eia-field-label">Área *</label>
                        <select name="recommendation_type_id" id="rec-f-type" class="eia-select" required>
                            <option value="">Selecciona un área…</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="eia-field-label">Sub-área / etiqueta</label>
                        <input type="text" name="sub_area" id="rec-f-subarea" class="eia-input" placeholder="Ej. Marketing" maxlength="255">
                    </div>
                </div>
                <div>
                    <label class="eia-field-label">Descripción breve</label>
                    <textarea name="description" id="rec-f-desc" class="eia-textarea" placeholder="Resumen que aparece en la tarjeta…"></textarea>
                </div>
                <div>
                    <label class="eia-field-label">Contenido completo</label>
                    <textarea name="content" id="rec-f-content" class="eia-textarea" style="min-height:120px;" placeholder="Texto detallado que se muestra en el modal de detalle…"></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="eia-field-label">Enlace externo</label>
                        <input type="url" name="external_link" id="rec-f-link" class="eia-input" placeholder="https://…">
                    </div>
                    <div>
                        <label class="eia-field-label">URL de imagen (opcional)</label>
                        <input type="url" name="image_url" id="rec-f-imgurl" class="eia-input" placeholder="https://…/imagen.jpg">
                    </div>
                </div>
                <div>
                    <label class="eia-field-label">…o subir imagen</label>
                    <input type="file" name="image_file" id="rec-f-imgfile" accept="image/*" class="eia-input">
                    <p class="text-[11px] text-slate-400 mt-1">JPG, PNG o WEBP. Máx 4MB. Tiene prioridad sobre la URL.</p>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t" style="border-color:var(--eia-border);background:#FAFAFB;">
                <button type="button" onclick="document.getElementById('rec-form-modal').classList.add('hidden')" class="btn-modal-secondary" style="background:#fff;color:var(--eia-slate);border:1px solid var(--eia-border);font-size:13px;font-weight:600;padding:9px 16px;border-radius:9px;">Cancelar</button>
                <button type="submit" class="eia-btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Form oculto para eliminar --}}
<form id="rec-delete-form" method="POST" action="" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endif

@endsection

@push('scripts')
<script>
    function filterRecs(btn, typeId) {
        document.querySelectorAll('.rec-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');

        let visible = 0;
        document.querySelectorAll('.rec-item').forEach(card => {
            const show = (typeId === 'all') || (card.getAttribute('data-type') === String(typeId));
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        // Encabezado dinámico según la categoría.
        const head = document.getElementById('rec-head-title');
        const sub = document.getElementById('rec-head-sub');
        const cats = window.REC_CATS || {};
        if (typeId === 'all') {
            head.textContent = 'Todas las recomendaciones';
            sub.textContent = 'Oportunidades de formación y eventos del sector Oil & Gas para GPT Services.';
        } else if (cats[typeId]) {
            head.textContent = cats[typeId].name;
            sub.textContent = cats[typeId].desc || '';
        }

        const noRes = document.getElementById('rec-no-results');
        const grid = document.getElementById('rec-grid');
        if (noRes && grid) {
            noRes.style.display = visible === 0 ? '' : 'none';
            grid.style.display = visible === 0 ? 'none' : '';
        }
    }

    function openRecDetail(id) {
        const r = window.RECS[id];
        if (!r) return;
        document.getElementById('rec-detail-title').textContent = r.title;
        document.getElementById('rec-detail-badge').textContent = r.sub_area || r.type_name || 'Recomendación';
        document.getElementById('rec-detail-desc').textContent = r.description || '';
        document.getElementById('rec-detail-content').textContent = r.content || '';
        const img = document.getElementById('rec-detail-img');
        img.src = r.image || 'https://placehold.co/800x400/0F1419/D97706?text=Recomendaci%C3%B3n';
        const link = document.getElementById('rec-detail-link');
        if (r.external_link) { link.href = r.external_link; link.classList.remove('hidden'); link.style.display='inline-flex'; }
        else { link.classList.add('hidden'); link.style.display='none'; }
        document.getElementById('rec-detail-modal').classList.remove('hidden');
    }

    @if($canManage)
    const REC_STORE_URL = "{{ route('admin.recommendations.store') }}";
    const REC_UPDATE_BASE = "{{ url('admin/recommendations') }}";

    function openRecModal(mode, id) {
        const form = document.getElementById('rec-form');
        const methodInput = document.getElementById('rec-form-method');
        form.reset();

        if (mode === 'edit' && window.RECS[id]) {
            const r = window.RECS[id];
            document.getElementById('rec-form-title').textContent = 'Editar recomendación';
            form.action = REC_UPDATE_BASE + '/' + id;
            methodInput.value = 'PUT';
            document.getElementById('rec-f-title').value = r.title || '';
            document.getElementById('rec-f-type').value = r.recommendation_type_id || '';
            document.getElementById('rec-f-subarea').value = r.sub_area || '';
            document.getElementById('rec-f-desc').value = r.description || '';
            document.getElementById('rec-f-content').value = r.content || '';
            document.getElementById('rec-f-link').value = r.external_link || '';
            document.getElementById('rec-f-imgurl').value = r.image_url || '';
        } else {
            document.getElementById('rec-form-title').textContent = 'Nueva recomendación';
            form.action = REC_STORE_URL;
            methodInput.value = 'POST';
        }
        document.getElementById('rec-form-modal').classList.remove('hidden');
    }

    function confirmDeleteRec(id, title) {
        if (!confirm('¿Eliminar la recomendación "' + title + '"? Esta acción no se puede deshacer.')) return;
        const f = document.getElementById('rec-delete-form');
        f.action = REC_UPDATE_BASE + '/' + id;
        f.submit();
    }
    @endif

    // Cerrar modales al hacer click en el backdrop
    ['rec-detail-modal', 'rec-form-modal'].forEach(idm => {
        const el = document.getElementById(idm);
        if (el) el.addEventListener('click', e => { if (e.target === el) el.classList.add('hidden'); });
    });
</script>
@endpush
