@extends('layouts.app')

@push('styles')
<style>
    :root {
        --eia-black:#0F1419; --eia-graphite:#1F2937; --eia-slate:#475569; --eia-mute:#64748B;
        --eia-border:#E5E7EB; --eia-surface:#FFFFFF; --eia-bg:#F8FAFC;
        --eia-red:#B91C1C; --eia-gold:#D97706; --eia-gold-soft:#FBBF24; --eia-green:#059669;
    }
    .eia-bg { background:var(--eia-bg); }
    .na-hero {
        background:
            radial-gradient(1000px 280px at 92% -40%, rgba(217,119,6,.18), transparent 60%),
            radial-gradient(800px 260px at 5% 130%, rgba(185,28,28,.22), transparent 60%),
            linear-gradient(180deg,#0F1419 0%,#1A1F26 100%);
        color:#F8FAFC; position:relative; border-bottom:1px solid var(--eia-graphite);
    }
    .na-hero::after { content:''; position:absolute; left:0; right:0; bottom:0; height:2px;
        background:linear-gradient(90deg,var(--eia-red) 0%,var(--eia-gold) 100%); opacity:.85; }
    .eia-eyebrow { font-size:11px; letter-spacing:.2em; text-transform:uppercase; color:var(--eia-gold-soft); font-weight:600; }
    .na-back { width:38px;height:38px;border-radius:10px;border:1px solid rgba(255,255,255,.18);
        background:rgba(255,255,255,.04);display:inline-flex;align-items:center;justify-content:center;color:#E2E8F0;transition:all .2s; }
    .na-back:hover { background:rgba(255,255,255,.1); border-color:var(--eia-gold); color:#fff; }

    .na-card { background:var(--eia-surface); border:1px solid var(--eia-border); border-radius:14px; }
    .na-stat { padding:16px 18px; }
    .na-stat .label { font-size:10.5px; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:var(--eia-mute); }
    .na-stat .value { font-size:26px; font-weight:700; color:var(--eia-black); margin-top:4px; }

    .badge { display:inline-flex; align-items:center; gap:5px; font-size:10.5px; font-weight:700;
        letter-spacing:.06em; text-transform:uppercase; padding:4px 9px; border-radius:999px; }
    .badge .dot { width:6px; height:6px; border-radius:50%; }
    .badge-ok { background:#ECFDF5; color:#065F46; } .badge-ok .dot { background:var(--eia-green); }
    .badge-err { background:#FEF2F2; color:#991B1B; } .badge-err .dot { background:var(--eia-red); }
    .badge-never { background:#F1F5F9; color:#475569; } .badge-never .dot { background:#94A3B8; }
    .badge-off { background:#F8FAFC; color:#94A3B8; border:1px dashed #CBD5E1; }
    .badge-mod-news { background:#FEF2F2; color:#991B1B; }
    .badge-mod-rec { background:#FFFBEB; color:#92400E; }

    .eia-btn-primary { display:inline-flex; align-items:center; gap:8px; background:var(--eia-black); color:#fff;
        font-size:12.5px; font-weight:600; padding:10px 16px; border-radius:10px; border:1px solid var(--eia-black); cursor:pointer; transition:all .2s; }
    .eia-btn-primary:hover { background:var(--eia-gold); border-color:var(--eia-gold); }
    .eia-btn-ghost { display:inline-flex; align-items:center; gap:7px; background:#fff; color:var(--eia-slate);
        font-size:12px; font-weight:600; padding:8px 13px; border-radius:9px; border:1px solid var(--eia-border); cursor:pointer; transition:all .2s; }
    .eia-btn-ghost:hover { border-color:var(--eia-black); color:var(--eia-black); }
    .eia-btn-ghost.danger:hover { border-color:var(--eia-red); color:var(--eia-red); }

    .na-tabs { display:inline-flex; gap:4px; background:#fff; border:1px solid var(--eia-border); border-radius:12px; padding:6px; }
    .na-tab { padding:9px 18px; font-size:12.5px; font-weight:600; color:var(--eia-slate); border:none; background:none; border-radius:8px; cursor:pointer; }
    .na-tab.active { background:var(--eia-black); color:#fff; }

    table.na-table { width:100%; border-collapse:collapse; }
    .na-table th { text-align:left; font-size:10.5px; font-weight:700; letter-spacing:.1em; text-transform:uppercase;
        color:var(--eia-mute); padding:10px 12px; border-bottom:1px solid var(--eia-border); }
    .na-table td { padding:11px 12px; border-bottom:1px solid #F1F5F9; font-size:13px; color:var(--eia-graphite); vertical-align:middle; }
    .na-table tr:hover td { background:#FAFAFB; }

    .eia-field-label { font-size:11.5px; font-weight:600; color:var(--eia-slate); margin-bottom:5px; display:block; }
    .eia-input,.eia-textarea,.eia-select { width:100%; font-size:13px; color:var(--eia-black); border:1px solid var(--eia-border);
        border-radius:9px; padding:9px 11px; background:#fff; }
    .eia-input:focus,.eia-textarea:focus,.eia-select:focus { outline:none; border-color:var(--eia-gold); }
    .eia-textarea { resize:vertical; min-height:64px; }
    .eia-flash { background:#ECFDF5; border:1px solid #A7F3D0; color:#065F46; padding:11px 16px; border-radius:10px; font-size:13px; font-weight:500; display:flex; align-items:center; gap:8px; }
    .eia-err { background:#FEF2F2; border:1px solid #FECACA; color:#991B1B; padding:11px 16px; border-radius:10px; font-size:13px; }
    .modal-bd { background:rgba(10,14,20,.65); backdrop-filter:blur(4px); }
</style>
@endpush

@section('content')
<div class="eia-bg min-h-screen pb-16">

    {{-- HERO --}}
    <section class="na-hero px-4 sm:px-8 lg:px-12 py-9">
        <div class="max-w-7xl mx-auto flex items-center gap-4">
            <a href="{{ route('news.index') }}" class="na-back" aria-label="Volver"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg></a>
            <div class="flex-1">
                <span class="eia-eyebrow">Panel administrativo</span>
                <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Noticias &amp; Scraping</h1>
                <p class="mt-1 text-sm text-slate-300">Gestiona noticias, fuentes de obtención y el estado del servicio.</p>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-8 lg:px-12">

        {{-- Flash / errores --}}
        @if(session('status'))
            <div class="eia-flash mt-6"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="eia-err mt-6">{{ $errors->first() }}</div>
        @endif

        {{-- ESTADO DEL SERVICIO --}}
        <section class="mt-7">
            <div class="flex items-center justify-between flex-wrap gap-3 mb-3">
                <h2 class="text-sm font-bold uppercase tracking-widest text-slate-500">Estado del servicio</h2>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('admin.news.scrape') }}">
                        @csrf <input type="hidden" name="module" value="news">
                        <button class="eia-btn-primary" type="submit">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Scrapear noticias ahora
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.news.scrape') }}">
                        @csrf <input type="hidden" name="module" value="recommendations">
                        <button class="eia-btn-ghost" type="submit">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.847a4.5 4.5 0 003.09 3.09L15.75 12l-2.847.813a4.5 4.5 0 00-3.09 3.091z"/></svg>
                            Scrapear recomendaciones
                        </button>
                    </form>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                <div class="na-card na-stat"><div class="label">Fuentes</div><div class="value">{{ $serviceStatus['total'] }}</div></div>
                <div class="na-card na-stat"><div class="label">Activas</div><div class="value">{{ $serviceStatus['active'] }}</div></div>
                <div class="na-card na-stat"><div class="label" style="color:var(--eia-green)">OK</div><div class="value">{{ $serviceStatus['ok'] }}</div></div>
                <div class="na-card na-stat"><div class="label" style="color:var(--eia-red)">Con error</div><div class="value">{{ $serviceStatus['error'] }}</div></div>
                <div class="na-card na-stat"><div class="label">Última corrida</div><div class="value" style="font-size:14px;font-weight:600;margin-top:8px;">{{ $serviceStatus['last_run'] ? \Illuminate\Support\Carbon::parse($serviceStatus['last_run'])->diffForHumans() : 'Nunca' }}</div></div>
            </div>
            <p class="text-[11px] text-slate-400 mt-2">El scraping corre en segundo plano (cola). Refresca esta página unos segundos después de dispararlo para ver los resultados.</p>
        </section>

        {{-- TABS --}}
        <div class="na-tabs mt-8" role="tablist">
            <button class="na-tab active" type="button" onclick="naSwitch(this,'tab-sources')">Fuentes de scraping</button>
            <button class="na-tab" type="button" onclick="naSwitch(this,'tab-news')">Noticias ({{ $news->total() }})</button>
        </div>

        {{-- ===================== FUENTES ===================== --}}
        <section id="tab-sources" class="na-panel mt-5">
            <div class="na-card overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color:var(--eia-border)">
                    <h3 class="text-sm font-semibold text-slate-800">Fuentes configuradas</h3>
                    <button class="eia-btn-primary" onclick="openSourceModal('create')">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                        Nueva fuente
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="na-table">
                        <thead><tr>
                            <th>Nombre</th><th>Módulo</th><th>Área</th><th>Tipo</th><th>Estado</th><th>Items</th><th>Última</th><th></th>
                        </tr></thead>
                        <tbody>
                        @forelse($sources as $s)
                            <tr>
                                <td><div class="font-semibold text-slate-800">{{ $s->name }}</div>
                                    <a href="{{ $s->url }}" target="_blank" rel="noopener" class="text-[11px] text-slate-400 hover:text-slate-600">{{ \Illuminate\Support\Str::limit($s->url, 50) }}</a>
                                </td>
                                <td><span class="badge {{ $s->module === 'news' ? 'badge-mod-news' : 'badge-mod-rec' }}">{{ $s->module === 'news' ? 'Noticias' : 'Recom.' }}</span></td>
                                <td class="text-slate-600">{{ $s->type_name ?? '—' }}</td>
                                <td class="uppercase text-[11px] font-semibold text-slate-500">{{ $s->feed_type }}</td>
                                <td>
                                    @if(!$s->is_active)
                                        <span class="badge badge-off">Inactiva</span>
                                    @elseif($s->last_status === 'ok')
                                        <span class="badge badge-ok"><span class="dot"></span>OK</span>
                                    @elseif($s->last_status === 'error')
                                        <span class="badge badge-err" title="{{ $s->last_error }}"><span class="dot"></span>Error</span>
                                    @else
                                        <span class="badge badge-never"><span class="dot"></span>Sin correr</span>
                                    @endif
                                </td>
                                <td class="text-slate-600">{{ $s->last_items }}</td>
                                <td class="text-[11px] text-slate-500">{{ $s->last_run_at ? $s->last_run_at->diffForHumans() : '—' }}</td>
                                <td>
                                    <div class="flex items-center gap-1.5 justify-end">
                                        <form method="POST" action="{{ route('admin.news.scrape') }}">@csrf
                                            <input type="hidden" name="source_id" value="{{ $s->id }}">
                                            <button class="eia-btn-ghost" title="Scrapear esta fuente" type="submit">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            </button>
                                        </form>
                                        <button class="eia-btn-ghost" title="Editar" onclick='openSourceModal("edit", @json($s))'>
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form method="POST" action="{{ route('admin.news.sources.toggle', $s) }}">@csrf
                                            <button class="eia-btn-ghost" title="{{ $s->is_active ? 'Desactivar' : 'Activar' }}" type="submit">
                                                {{ $s->is_active ? 'On' : 'Off' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.news.sources.destroy', $s) }}" onsubmit="return confirm('¿Eliminar la fuente «{{ $s->name }}»?')">@csrf @method('DELETE')
                                            <button class="eia-btn-ghost danger" title="Eliminar" type="submit">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-slate-400 py-8">No hay fuentes configuradas. Crea la primera.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        {{-- ===================== NOTICIAS ===================== --}}
        <section id="tab-news" class="na-panel mt-5" style="display:none;">
            <div class="na-card overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color:var(--eia-border)">
                    <h3 class="text-sm font-semibold text-slate-800">Noticias</h3>
                    <button class="eia-btn-primary" onclick="openNewsModal('create')">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                        Nueva noticia
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="na-table">
                        <thead><tr><th>Título</th><th>Área</th><th>Origen</th><th>Fecha</th><th></th></tr></thead>
                        <tbody>
                        @forelse($news as $n)
                            <tr>
                                <td class="max-w-md"><div class="font-semibold text-slate-800">{{ \Illuminate\Support\Str::limit($n->title, 80) }}</div></td>
                                <td class="text-slate-600">{{ optional($n->newsType)->name ?? '—' }}</td>
                                <td>
                                    @if($n->is_scraped)
                                        <span class="badge badge-mod-rec">Scraping</span> <span class="text-[11px] text-slate-400">{{ $n->source }}</span>
                                    @else
                                        <span class="badge badge-ok">Manual</span>
                                    @endif
                                </td>
                                <td class="text-[11px] text-slate-500">{{ $n->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="flex items-center gap-1.5 justify-end">
                                        @php $newsPayload = ['id'=>$n->id,'title'=>$n->title,'description'=>$n->description,'content'=>$n->content,'news_type_id'=>$n->news_type_id,'external_link'=>$n->external_link,'image_url'=>$n->image_url]; @endphp
                                        <button class="eia-btn-ghost" title="Editar" onclick='openNewsModal("edit", @json($newsPayload))'>
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <form method="POST" action="{{ route('admin.news.items.destroy', $n) }}" onsubmit="return confirm('¿Eliminar esta noticia?')">@csrf @method('DELETE')
                                            <button class="eia-btn-ghost danger" title="Eliminar" type="submit">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-slate-400 py-8">No hay noticias todavía.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-4">{{ $news->links() }}</div>
            </div>
        </section>
    </div>
</div>

{{-- ===================== MODAL FUENTE ===================== --}}
<div id="source-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-bd">
    <div class="relative w-full max-w-xl bg-white rounded-2xl shadow-2xl overflow-hidden" style="max-height:92vh;">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color:var(--eia-border)">
            <h2 id="source-modal-title" class="text-base font-semibold text-slate-900">Nueva fuente</h2>
            <button onclick="document.getElementById('source-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700"><svg width="18" height="18" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg></button>
        </div>
        <form id="source-form" method="POST" action="{{ route('admin.news.sources.store') }}">
            @csrf <input type="hidden" name="_method" id="source-method" value="POST">
            <div class="overflow-y-auto p-6 space-y-4" style="max-height:calc(92vh - 130px)">
                <div><label class="eia-field-label">Nombre *</label><input type="text" name="name" id="src-name" class="eia-input" required></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="eia-field-label">Módulo *</label>
                        <select name="module" id="src-module" class="eia-select" required onchange="syncSourceTypes()">
                            <option value="news">Noticias</option>
                            <option value="recommendations">Recomendaciones</option>
                        </select>
                    </div>
                    <div><label class="eia-field-label">Tipo de feed *</label>
                        <select name="feed_type" id="src-feed" class="eia-select" required onchange="toggleSelectors()">
                            <option value="rss">RSS (recomendado)</option>
                            <option value="html">HTML (selectores)</option>
                        </select>
                    </div>
                </div>
                <div><label class="eia-field-label">URL *</label><input type="url" name="url" id="src-url" class="eia-input" placeholder="https://…/feed o https://…/seccion" required></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="eia-field-label">Área destino</label>
                        <select name="type_id" id="src-type" class="eia-select"><option value="">—</option></select>
                    </div>
                    <div><label class="eia-field-label">Máx. items / corrida</label><input type="number" name="max_items" id="src-max" class="eia-input" value="10" min="1" max="50"></div>
                </div>
                <div id="src-subarea-wrap"><label class="eia-field-label">Sub-área (solo recomendaciones)</label><input type="text" name="sub_area" id="src-subarea" class="eia-input" placeholder="Ej. Marketing"></div>
                <div id="src-selectors-wrap" style="display:none;">
                    <label class="eia-field-label">Selectores XPath (JSON, solo HTML)</label>
                    <textarea name="selectors" id="src-selectors" class="eia-textarea" placeholder='{"item":"//article","title":".//h2","link":".//a/@href","summary":".//p","image":".//img/@src"}'></textarea>
                    <p class="text-[11px] text-slate-400 mt-1">Déjalo vacío para usar selectores genéricos.</p>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t" style="border-color:var(--eia-border);background:#FAFAFB">
                <button type="button" onclick="document.getElementById('source-modal').classList.add('hidden')" class="eia-btn-ghost">Cancelar</button>
                <button type="submit" class="eia-btn-primary">Guardar fuente</button>
            </div>
        </form>
    </div>
</div>

{{-- ===================== MODAL NOTICIA ===================== --}}
<div id="news-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-bd">
    <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden" style="max-height:92vh;">
        <div class="flex items-center justify-between px-6 py-4 border-b" style="border-color:var(--eia-border)">
            <h2 id="news-modal-title" class="text-base font-semibold text-slate-900">Nueva noticia</h2>
            <button onclick="document.getElementById('news-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700"><svg width="18" height="18" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg></button>
        </div>
        <form id="news-form" method="POST" enctype="multipart/form-data" action="{{ route('admin.news.items.store') }}">
            @csrf <input type="hidden" name="_method" id="news-method" value="POST">
            <div class="overflow-y-auto p-6 space-y-4" style="max-height:calc(92vh - 130px)">
                <div><label class="eia-field-label">Título *</label><input type="text" name="title" id="news-title" class="eia-input" required maxlength="255"></div>
                <div><label class="eia-field-label">Área *</label>
                    <select name="news_type_id" id="news-type" class="eia-select" required>
                        <option value="">Selecciona…</option>
                        @foreach($newsTypes as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
                    </select>
                </div>
                <div><label class="eia-field-label">Descripción breve</label><textarea name="description" id="news-desc" class="eia-textarea"></textarea></div>
                <div><label class="eia-field-label">Contenido completo</label><textarea name="content" id="news-content" class="eia-textarea" style="min-height:120px;"></textarea></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="eia-field-label">Enlace externo</label><input type="url" name="external_link" id="news-link" class="eia-input" placeholder="https://…"></div>
                    <div><label class="eia-field-label">URL de imagen</label><input type="url" name="image_url" id="news-imgurl" class="eia-input" placeholder="https://…"></div>
                </div>
                <div><label class="eia-field-label">…o subir imagen</label><input type="file" name="image_file" accept="image/*" class="eia-input"><p class="text-[11px] text-slate-400 mt-1">JPG/PNG/WEBP. Máx 4MB.</p></div>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t" style="border-color:var(--eia-border);background:#FAFAFB">
                <button type="button" onclick="document.getElementById('news-modal').classList.add('hidden')" class="eia-btn-ghost">Cancelar</button>
                <button type="submit" class="eia-btn-primary">Guardar noticia</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const NEWS_TYPES = @json($newsTypes->map(fn($t)=>['id'=>$t->id,'name'=>$t->name]));
    const REC_TYPES  = @json($recommendationTypes->map(fn($t)=>['id'=>$t->id,'name'=>$t->name]));
    const SRC_STORE = "{{ route('admin.news.sources.store') }}";
    const SRC_BASE  = "{{ url('admin/news/sources') }}";
    const NEWS_STORE = "{{ route('admin.news.items.store') }}";
    const NEWS_BASE  = "{{ url('admin/news/items') }}";

    function naSwitch(btn, panelId) {
        document.querySelectorAll('.na-tab').forEach(t=>t.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.na-panel').forEach(p=>p.style.display='none');
        document.getElementById(panelId).style.display='';
    }

    function syncSourceTypes(selected) {
        const mod = document.getElementById('src-module').value;
        const list = mod === 'news' ? NEWS_TYPES : REC_TYPES;
        const sel = document.getElementById('src-type');
        sel.innerHTML = '<option value="">—</option>';
        list.forEach(t => {
            const o = document.createElement('option');
            o.value = t.id; o.textContent = t.name;
            if (selected && Number(selected) === t.id) o.selected = true;
            sel.appendChild(o);
        });
        document.getElementById('src-subarea-wrap').style.display = mod === 'recommendations' ? '' : 'none';
    }
    function toggleSelectors() {
        document.getElementById('src-selectors-wrap').style.display =
            document.getElementById('src-feed').value === 'html' ? '' : 'none';
    }

    function openSourceModal(mode, s) {
        const form = document.getElementById('source-form');
        form.reset();
        if (mode === 'edit' && s) {
            document.getElementById('source-modal-title').textContent = 'Editar fuente';
            form.action = SRC_BASE + '/' + s.id;
            document.getElementById('source-method').value = 'PUT';
            document.getElementById('src-name').value = s.name || '';
            document.getElementById('src-module').value = s.module;
            document.getElementById('src-feed').value = s.feed_type;
            document.getElementById('src-url').value = s.url || '';
            document.getElementById('src-max').value = s.max_items || 10;
            document.getElementById('src-subarea').value = s.sub_area || '';
            document.getElementById('src-selectors').value = s.selectors ? JSON.stringify(s.selectors) : '';
            syncSourceTypes(s.type_id);
        } else {
            document.getElementById('source-modal-title').textContent = 'Nueva fuente';
            form.action = SRC_STORE;
            document.getElementById('source-method').value = 'POST';
            syncSourceTypes();
        }
        toggleSelectors();
        document.getElementById('source-modal').classList.remove('hidden');
    }

    function openNewsModal(mode, n) {
        const form = document.getElementById('news-form');
        form.reset();
        if (mode === 'edit' && n) {
            document.getElementById('news-modal-title').textContent = 'Editar noticia';
            form.action = NEWS_BASE + '/' + n.id;
            document.getElementById('news-method').value = 'PUT';
            document.getElementById('news-title').value = n.title || '';
            document.getElementById('news-type').value = n.news_type_id || '';
            document.getElementById('news-desc').value = n.description || '';
            document.getElementById('news-content').value = n.content || '';
            document.getElementById('news-link').value = n.external_link || '';
            document.getElementById('news-imgurl').value = n.image_url || '';
        } else {
            document.getElementById('news-modal-title').textContent = 'Nueva noticia';
            form.action = NEWS_STORE;
            document.getElementById('news-method').value = 'POST';
        }
        document.getElementById('news-modal').classList.remove('hidden');
    }

    ['source-modal','news-modal'].forEach(id=>{
        const el=document.getElementById(id);
        if(el) el.addEventListener('click',e=>{ if(e.target===el) el.classList.add('hidden'); });
    });
</script>
@endpush
