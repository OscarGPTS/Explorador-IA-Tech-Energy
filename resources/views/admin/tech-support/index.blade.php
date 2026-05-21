@extends('layouts.app')

@push('styles')
@include('admin._admin-styles')
<style>
    /* Acordeón institucional */
    .accordion-header {
        transition: background .2s ease;
        cursor: pointer;
    }
    .accordion-header:hover {
        background: #F8FAFC;
    }
    .accordion-content {
        transition: max-height .3s ease;
        max-height: 0;
        overflow: hidden;
    }
    .accordion-content.show {
        max-height: 4000px;
    }
    .rotate-180 { transform: rotate(180deg); }
    .accordion-arrow { transition: transform .25s ease; }

    /* Chip de mini-acción */
    .ts-icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px; height: 30px;
        border-radius: 8px;
        border: 1px solid var(--eia-border);
        background: #FFFFFF;
        color: var(--eia-slate);
        cursor: pointer;
        transition: all .2s ease;
    }
    .ts-icon-btn:hover {
        background: var(--eia-black);
        color: #FFFFFF;
        border-color: var(--eia-black);
    }
    .ts-icon-btn.danger { color: var(--eia-red); border-color: #FECACA; }
    .ts-icon-btn.danger:hover { background: var(--eia-red); color: #FFFFFF; border-color: var(--eia-red); }
    .ts-icon-btn.gold { color: #92400E; border-color: #FDE68A; background: #FFFBEB; }
    .ts-icon-btn.gold:hover { background: #D97706; color: #FFFFFF; border-color: #D97706; }

    /* Modal */
    .ts-modal-backdrop {
        background: rgba(15, 20, 25, 0.55);
    }
    .ts-modal-card {
        background: var(--eia-surface);
        border-radius: 14px;
        border: 1px solid var(--eia-border);
        box-shadow: 0 30px 60px -20px rgba(15,20,25,0.4);
        overflow: hidden;
    }
    .ts-modal-head {
        padding: 18px 22px;
        border-bottom: 1px solid var(--eia-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .ts-modal-body { padding: 20px 22px; }
    .ts-modal-foot {
        padding: 14px 22px;
        border-top: 1px solid var(--eia-border);
        background: #FAFAFB;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }
    /* Ensure modals show as flex when visible */
    #categoryModal:not(.hidden),
    #problemModal:not(.hidden) { display: flex !important; }

    .space-y-3 > * + * { margin-top: 12px; }
    .space-y-4 > * + * { margin-top: 16px; }
</style>
@endpush

@section('content')
<div class="min-h-screen eia-bg" style="font-family: 'Figtree', system-ui, -apple-system, Segoe UI, sans-serif;">
    {{-- HERO --}}
    <section class="admin-hero px-4 sm:px-8 lg:px-12 py-10">
        <div class="max-w-7xl mx-auto flex items-start justify-between gap-6 flex-wrap">
            <div class="flex items-center gap-4">
                <a href="/" class="admin-back" aria-label="Volver al inicio">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                    </svg>
                </a>
                <div>
                    <span class="admin-eyebrow">Administración · Soporte Técnico</span>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Gestión de Casos y Categorías</h1>
                    <p class="mt-1 text-sm text-slate-300 max-w-2xl">Administra el catálogo de categorías y casos de soporte técnico disponibles para los usuarios.</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button onclick="showCategoryModal()" class="admin-action">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Nueva categoría</span>
                </button>
                <button onclick="showProblemModal()" class="admin-action">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Nuevo caso</span>
                </button>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-8 lg:px-12 py-10">
        {{-- Controles del acordeón --}}
        <div class="flex justify-between items-center mb-5 flex-wrap gap-3">
            <div style="font-size:11px; letter-spacing:0.18em; text-transform:uppercase; font-weight:700; color: var(--eia-mute);">
                {{ $categories->count() }} categorías registradas
            </div>
            <div class="flex gap-2">
                <button onclick="expandAll()" class="admin-btn-secondary" style="padding:8px 14px; font-size:12px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                    </svg>
                    Expandir todo
                </button>
                <button onclick="collapseAll()" class="admin-btn-secondary" style="padding:8px 14px; font-size:12px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4m0 5H4m5 0L4 4m11 5h5m-5 0V4m0 5l5-5M9 15v5m0-5H4m5 0l-5 5m11-5h5m-5 0v5m0-5l5 5"/>
                    </svg>
                    Contraer todo
                </button>
            </div>
        </div>

        {{-- Lista de categorías --}}
        <div class="space-y-3">
            @foreach($categories as $category)
                <div class="admin-panel admin-fade" style="overflow:hidden;">
                    {{-- Header de categoría --}}
                    <div class="accordion-header" style="padding:18px 22px; border-bottom: 1px solid var(--eia-border); background:#FAFAFB;"
                         onclick="toggleCategory({{ $category->id }})">
                        <div class="flex justify-between items-center gap-3 flex-wrap">
                            <div class="flex items-center gap-3" style="min-width:0; flex:1;">
                                @if($category->icon)
                                    <div style="width:42px; height:42px; border-radius:10px; background:#FFFFFF; border:1px solid var(--eia-border); display:inline-flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0;">
                                        {{ $category->icon }}
                                    </div>
                                @else
                                    <div style="width:42px; height:42px; border-radius:10px; background:#0F1419; color:#F8FAFC; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div style="min-width:0;">
                                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                        <h3 style="font-size:15px; font-weight:700; color: var(--eia-black); margin:0;">{{ $category->display_name }}</h3>
                                        @if($category->is_active)
                                            <span class="admin-badge green">Activa</span>
                                        @else
                                            <span class="admin-badge red">Inactiva</span>
                                        @endif
                                        <span class="admin-badge black">{{ $category->allProblems->count() }} casos</span>
                                    </div>
                                    @if($category->description)
                                        <p style="margin-top:4px; font-size:12.5px; color: var(--eia-mute);">{{ $category->description }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                {{-- Acciones --}}
                                <button onclick="event.stopPropagation(); editCategory({{ $category->id }})"
                                        class="ts-icon-btn" title="Editar categoría">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="event.stopPropagation(); toggleActive('category', {{ $category->id }}, {{ $category->is_active ? 'false' : 'true' }})"
                                        class="ts-icon-btn"
                                        title="{{ $category->is_active ? 'Desactivar' : 'Activar' }}">
                                    @if($category->is_active)
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    @else
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    @endif
                                </button>
                                @if($category->allProblems->count() == 0)
                                    <button onclick="event.stopPropagation(); deleteCategory({{ $category->id }})"
                                            class="ts-icon-btn danger" title="Eliminar categoría">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                                        </svg>
                                    </button>
                                @endif
                                <button onclick="event.stopPropagation(); showProblemModal({{ $category->id }})"
                                        class="ts-icon-btn gold" title="Agregar caso">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                                {{-- Flecha --}}
                                <span style="display:inline-flex; align-items:center; justify-content:center; margin-left:4px;">
                                    <svg id="arrow-{{ $category->id }}" class="accordion-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--eia-slate);">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Contenido desplegable --}}
                    <div id="content-{{ $category->id }}" class="hidden">
                        <div style="padding:0;">
                            @forelse($category->allProblems as $problem)
                                <div style="padding:16px 22px; border-bottom: 1px solid var(--eia-border); transition: background .15s ease;"
                                     onmouseover="this.style.background='#F8FAFC';"
                                     onmouseout="this.style.background='transparent';">
                                    <div class="flex justify-between items-start gap-3 flex-wrap">
                                        <div style="flex:1; min-width:0;">
                                            <div class="flex items-center gap-2 flex-wrap" style="margin-bottom:6px;">
                                                <h4 style="font-size:14px; font-weight:600; color: var(--eia-black); margin:0;">{{ $problem->title }}</h4>
                                                @switch($problem->priority)
                                                    @case('high')
                                                        <span class="admin-badge red">Alta</span>
                                                        @break
                                                    @case('medium')
                                                        <span class="admin-badge gold">Media</span>
                                                        @break
                                                    @default
                                                        <span class="admin-badge">Baja</span>
                                                @endswitch
                                                @if($problem->is_active)
                                                    <span class="admin-badge green">Activo</span>
                                                @else
                                                    <span class="admin-badge red">Inactivo</span>
                                                @endif
                                            </div>
                                            @if($problem->description)
                                                <p style="font-size:13px; color: var(--eia-slate); margin-bottom:6px; line-height:1.55;">{{ $problem->description }}</p>
                                            @endif
                                            <div class="flex items-center gap-4 flex-wrap" style="font-size:11.5px; color: var(--eia-mute);">
                                                <span><strong style="color: var(--eia-slate); font-weight:600;">Clave:</strong> <span style="font-family: ui-monospace, SFMono-Regular, Menlo, monospace;">{{ $problem->problem_key }}</span></span>
                                                @if($problem->estimated_time)
                                                    <span><strong style="color: var(--eia-slate); font-weight:600;">Tiempo:</strong> {{ $problem->estimated_time }}</span>
                                                @endif
                                                <span><strong style="color: var(--eia-slate); font-weight:600;">Orden:</strong> {{ $problem->sort_order }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button onclick="editProblem({{ $problem->id }})" class="ts-icon-btn" title="Editar caso">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button onclick="toggleActive('problem', {{ $problem->id }}, {{ $problem->is_active ? 'false' : 'true' }})"
                                                    class="ts-icon-btn"
                                                    title="{{ $problem->is_active ? 'Desactivar' : 'Activar' }}">
                                                @if($problem->is_active)
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                                    </svg>
                                                @else
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                @endif
                                            </button>
                                            <button onclick="deleteProblem({{ $problem->id }})" class="ts-icon-btn danger" title="Eliminar caso">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div style="padding:36px 22px; text-align:center;">
                                    <div style="width:48px; height:48px; border-radius:12px; background:#F1F5F9; border:1px solid var(--eia-border); display:inline-flex; align-items:center; justify-content:center; color: var(--eia-mute); margin:0 auto;">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                    </div>
                                    <p style="margin-top:12px; font-size:13px; color: var(--eia-mute);">No hay casos de soporte en esta categoría.</p>
                                    <button onclick="showProblemModal({{ $category->id }})" class="admin-btn-secondary" style="margin-top:12px;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Agregar el primer caso
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Modal: Categoría --}}
<div id="categoryModal" class="ts-modal-backdrop hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto">
    <div class="relative p-4 w-full max-w-md mx-auto" style="margin-top: 8vh;">
        <div class="ts-modal-card">
            <div class="ts-modal-head">
                <div>
                    <div class="admin-panel-title" id="categoryModalTitle">Nueva categoría</div>
                    <div class="admin-panel-sub">Define un grupo lógico de casos de soporte.</div>
                </div>
                <button type="button" onclick="hideCategoryModal()" style="color: var(--eia-mute); background: transparent; border:none; cursor:pointer;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="categoryForm">
                <div class="ts-modal-body space-y-4">
                    <input type="hidden" id="categoryId" name="id">
                    <div>
                        <label class="admin-label" for="categoryName">Nombre de la categoría</label>
                        <input type="text" id="categoryName" name="name" class="admin-input" required>
                    </div>
                    <div>
                        <label class="admin-label" for="categoryDisplayName">Nombre para mostrar</label>
                        <input type="text" id="categoryDisplayName" name="display_name" class="admin-input" required>
                    </div>
                    <div>
                        <label class="admin-label" for="categoryIcon">Ícono (emoji)</label>
                        <input type="text" id="categoryIcon" name="icon" class="admin-input" maxlength="10">
                    </div>
                    <div>
                        <label class="admin-label" for="categoryDescription">Descripción</label>
                        <textarea id="categoryDescription" name="description" rows="3" class="admin-textarea"></textarea>
                    </div>
                    <div>
                        <label class="admin-label" for="categorySortOrder">Orden</label>
                        <input type="number" id="categorySortOrder" name="sort_order" value="0" min="0" class="admin-input">
                    </div>
                </div>
                <div class="ts-modal-foot">
                    <button type="button" onclick="hideCategoryModal()" class="admin-btn-secondary">Cancelar</button>
                    <button type="submit" class="admin-btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: Caso --}}
<div id="problemModal" class="ts-modal-backdrop hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto">
    <div class="relative p-4 w-full" style="max-width: 880px; margin: 6vh auto;">
        <div class="ts-modal-card">
            <div class="ts-modal-head">
                <div>
                    <div class="admin-panel-title" id="problemModalTitle">Nuevo caso de soporte</div>
                    <div class="admin-panel-sub">Define el caso, prioridad y la solución a entregar al usuario.</div>
                </div>
                <button type="button" onclick="hideProblemModal()" style="color: var(--eia-mute); background: transparent; border:none; cursor:pointer;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="problemForm">
                <div class="ts-modal-body space-y-4">
                    <input type="hidden" id="problemId" name="id">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="admin-label" for="problemCategory">Categoría</label>
                            <select id="problemCategory" name="tech_support_category_id" class="admin-select" required>
                                <option value="">Seleccionar categoría</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="admin-label" for="problemKey">Clave del problema</label>
                            <input type="text" id="problemKey" name="problem_key" class="admin-input" required>
                            <p style="font-size:11px; color: var(--eia-mute); margin-top:6px;">Debe ser única (ej: computadora_lenta)</p>
                        </div>
                    </div>
                    <div>
                        <label class="admin-label" for="problemTitle">Título del problema</label>
                        <input type="text" id="problemTitle" name="title" class="admin-input" required>
                    </div>
                    <div>
                        <label class="admin-label" for="problemDescription">Descripción breve</label>
                        <textarea id="problemDescription" name="description" rows="2" class="admin-textarea"></textarea>
                    </div>
                    <div>
                        <label class="admin-label" for="solutionTitle">Título de la solución</label>
                        <input type="text" id="solutionTitle" name="solution_title" class="admin-input" required>
                    </div>
                    <div>
                        <label class="admin-label" for="solutionContent">Contenido de la solución (HTML)</label>
                        <textarea id="solutionContent" name="solution_content" rows="8" class="admin-textarea" required style="font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size:12.5px;"></textarea>
                        <p style="font-size:11px; color: var(--eia-mute); margin-top:6px;">Puedes usar HTML con clases de Tailwind CSS.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="admin-label" for="problemPriority">Prioridad</label>
                            <select id="problemPriority" name="priority" class="admin-select" required>
                                <option value="low">Baja</option>
                                <option value="medium" selected>Media</option>
                                <option value="high">Alta</option>
                            </select>
                        </div>
                        <div>
                            <label class="admin-label" for="estimatedTime">Tiempo estimado</label>
                            <input type="text" id="estimatedTime" name="estimated_time" class="admin-input" placeholder="ej: 5-10 minutos">
                        </div>
                        <div>
                            <label class="admin-label" for="problemSortOrder">Orden</label>
                            <input type="number" id="problemSortOrder" name="sort_order" value="0" min="0" class="admin-input">
                        </div>
                    </div>
                    <div>
                        <label class="admin-label" for="problemKeywords">Palabras clave (una por línea)</label>
                        <textarea id="problemKeywords" name="keywords" rows="3" class="admin-textarea" placeholder="lenta&#10;despacio&#10;demora"></textarea>
                    </div>
                </div>
                <div class="ts-modal-foot">
                    <button type="button" onclick="hideProblemModal()" class="admin-btn-secondary">Cancelar</button>
                    <button type="submit" class="admin-btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Variables globales
let isEditingCategory = false;
let isEditingProblem = false;

// Función para toggle del acordeón con animaciones
function toggleCategory(categoryId) {
    const content = document.getElementById(`content-${categoryId}`);
    const arrow = document.getElementById(`arrow-${categoryId}`);

    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        content.offsetHeight;
        content.classList.add('show');
        arrow.classList.add('rotate-180');
    } else {
        content.classList.remove('show');
        arrow.classList.remove('rotate-180');
        setTimeout(() => {
            if (!content.classList.contains('show')) {
                content.classList.add('hidden');
            }
        }, 300);
    }
}

function expandAll() {
    const allContents = document.querySelectorAll('[id^="content-"]');
    const allArrows = document.querySelectorAll('[id^="arrow-"]');

    allContents.forEach(content => {
        content.classList.remove('hidden');
        content.offsetHeight;
        content.classList.add('show');
    });
    allArrows.forEach(arrow => arrow.classList.add('rotate-180'));
}

function collapseAll() {
    const allContents = document.querySelectorAll('[id^="content-"]');
    const allArrows = document.querySelectorAll('[id^="arrow-"]');

    allContents.forEach(content => {
        content.classList.remove('show');
        setTimeout(() => {
            if (!content.classList.contains('show')) {
                content.classList.add('hidden');
            }
        }, 300);
    });
    allArrows.forEach(arrow => arrow.classList.remove('rotate-180'));
}

// Modal de categorías
function showCategoryModal(categoryData) {
    categoryData = categoryData || null;
    if (categoryData) {
        isEditingCategory = true;
        document.getElementById('categoryModalTitle').textContent = 'Editar categoría';
        document.getElementById('categoryId').value = categoryData.id;
        document.getElementById('categoryName').value = categoryData.name;
        document.getElementById('categoryDisplayName').value = categoryData.display_name;
        document.getElementById('categoryIcon').value = categoryData.icon || '';
        document.getElementById('categoryDescription').value = categoryData.description || '';
        document.getElementById('categorySortOrder').value = categoryData.sort_order;
    } else {
        isEditingCategory = false;
        document.getElementById('categoryModalTitle').textContent = 'Nueva categoría';
        document.getElementById('categoryForm').reset();
    }
    document.getElementById('categoryModal').classList.remove('hidden');
}

function hideCategoryModal() {
    document.getElementById('categoryModal').classList.add('hidden');
    document.getElementById('categoryForm').reset();
    isEditingCategory = false;
}

// Modal de problemas
function showProblemModal(categoryId, problemData) {
    categoryId = categoryId || null;
    problemData = problemData || null;
    if (problemData) {
        isEditingProblem = true;
        document.getElementById('problemModalTitle').textContent = 'Editar caso';
        document.getElementById('problemId').value = problemData.id;
        document.getElementById('problemCategory').value = problemData.tech_support_category_id;
        document.getElementById('problemKey').value = problemData.problem_key;
        document.getElementById('problemTitle').value = problemData.title;
        document.getElementById('problemDescription').value = problemData.description || '';
        document.getElementById('solutionTitle').value = problemData.solution_title;
        document.getElementById('solutionContent').value = problemData.solution_content;
        document.getElementById('problemPriority').value = problemData.priority;
        document.getElementById('estimatedTime').value = problemData.estimated_time || '';
        document.getElementById('problemSortOrder').value = problemData.sort_order;
        document.getElementById('problemKeywords').value = problemData.keywords ? problemData.keywords.join('\n') : '';
    } else {
        isEditingProblem = false;
        document.getElementById('problemModalTitle').textContent = 'Nuevo caso de soporte';
        document.getElementById('problemForm').reset();
        if (categoryId) {
            document.getElementById('problemCategory').value = categoryId;
        }
    }
    document.getElementById('problemModal').classList.remove('hidden');
}

function hideProblemModal() {
    document.getElementById('problemModal').classList.add('hidden');
    document.getElementById('problemForm').reset();
    isEditingProblem = false;
}

// Envío de formularios
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    const url = isEditingCategory
        ? `{{ url('admin/tech-support/categories') }}/${data.id}`
        : '{{ route("admin.tech-support.categories.store") }}';

    const method = isEditingCategory ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideCategoryModal();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar la categoría');
    });
});

document.getElementById('problemForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    if (data.keywords) {
        data.keywords = data.keywords.split('\n').filter(k => k.trim()).map(k => k.trim());
    }

    const url = isEditingProblem
        ? `{{ url('admin/tech-support/problems') }}/${data.id}`
        : '{{ route("admin.tech-support.problems.store") }}';

    const method = isEditingProblem ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideProblemModal();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar el caso');
    });
});

// Funciones adicionales
function editCategory(categoryId) {
    const categories = @json($categories);
    const category = categories.find(c => c.id === categoryId);
    if (category) {
        showCategoryModal(category);
    }
}

function editProblem(problemId) {
    const categories = @json($categories);
    let problem = null;

    for (const category of categories) {
        problem = category.all_problems.find(p => p.id === problemId);
        if (problem) break;
    }

    if (problem) {
        showProblemModal(null, problem);
    }
}

function toggleActive(type, id, isActive) {
    fetch('{{ route("admin.tech-support.toggle-active", [], false) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            type: type,
            id: id,
            is_active: isActive
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al cambiar el estado');
    });
}

function deleteCategory(categoryId) {
    if (confirm('¿Estás seguro de que quieres eliminar esta categoría?')) {
        fetch(`{{ url('admin/tech-support/categories') }}/${categoryId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar la categoría');
        });
    }
}

function deleteProblem(problemId) {
    if (confirm('¿Estás seguro de que quieres eliminar este caso?')) {
        fetch(`{{ url('admin/tech-support/problems') }}/${problemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el caso');
        });
    }
}
</script>
@endsection
