@extends('layouts.app')

@push('styles')
@include('admin._admin-styles')
<style>
    .upload-zone {
        border: 1.5px dashed #CBD5E1;
        background: #FAFAFB;
        border-radius: 12px;
        transition: all .25s ease;
    }
    .upload-zone:hover {
        border-color: var(--eia-black);
        background: #F8FAFC;
    }
    .upload-zone.dragover {
        border-color: var(--eia-gold);
        background: #FFFBEB;
    }
    .upload-zone-success {
        border-color: #047857 !important;
        background: #ECFDF5 !important;
    }
</style>
@endpush

@section('title', 'Importar Empleados')

@section('content')
<div class="min-h-screen eia-bg" style="font-family: 'Figtree', system-ui, -apple-system, Segoe UI, sans-serif;">
    {{-- HERO --}}
    <section class="admin-hero px-4 sm:px-8 lg:px-12 py-10">
        <div class="max-w-7xl mx-auto flex items-start justify-between gap-6 flex-wrap">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.employees.index') }}" class="admin-back" aria-label="Volver al listado">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
                    </svg>
                </a>
                <div>
                    <span class="admin-eyebrow">Administración · Empleados</span>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-semibold tracking-tight">Importación Masiva</h1>
                    <p class="mt-1 text-sm text-slate-300 max-w-2xl">Carga archivos CSV o Excel para incorporar empleados al directorio institucional.</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.employees.template') }}" class="admin-action">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Descargar plantilla</span>
                </a>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-8 lg:px-12 py-10">
        {{-- Notificaciones --}}
        @if(session('success'))
            <div class="admin-fade mb-5" style="background:#ECFDF5; border:1px solid #A7F3D0; border-left:3px solid #047857; border-radius:12px; padding:14px 18px; display:flex; gap:12px; align-items:flex-start;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="2" style="flex-shrink:0; margin-top:1px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p style="font-size:13.5px; color:#065F46; font-weight:500;">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="admin-fade mb-5" style="background:#FEF2F2; border:1px solid #FECACA; border-left:3px solid var(--eia-red); border-radius:12px; padding:14px 18px; display:flex; gap:12px; align-items:flex-start;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#B91C1C" stroke-width="2" style="flex-shrink:0; margin-top:1px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M4.93 4.93l14.14 14.14M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p style="font-size:13.5px; color:#7F1D1D; font-weight:500;">{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="admin-fade mb-5" style="background:#FEF2F2; border:1px solid #FECACA; border-left:3px solid var(--eia-red); border-radius:12px; padding:14px 18px; display:flex; gap:12px; align-items:flex-start;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#B91C1C" stroke-width="2" style="flex-shrink:0; margin-top:1px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-3L13.74 4a2 2 0 00-3.48 0L3.34 16a2 2 0 001.73 3z"/>
                </svg>
                <div>
                    <h3 style="font-size:13px; color:#7F1D1D; font-weight:700; margin-bottom:6px; letter-spacing:0.04em; text-transform:uppercase;">Errores encontrados</h3>
                    <ul style="font-size:13px; color:#991B1B; padding-left:18px; list-style:disc;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Formulario --}}
            <div class="lg:col-span-2">
                <div class="admin-panel admin-fade admin-d1">
                    <div class="admin-panel-head">
                        <div>
                            <div class="admin-panel-title">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                Subir archivo de empleados
                            </div>
                            <div class="admin-panel-sub">Formatos admitidos: CSV, Excel (XLSX/XLS), TXT — hasta 2 MB.</div>
                        </div>
                    </div>
                    <div class="admin-panel-body">
                        <form action="{{ route('admin.employees.process-import') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-5">
                                <label for="file" class="admin-label">Seleccionar archivo</label>
                                <div class="upload-zone flex justify-center px-6 py-10">
                                    <div style="text-align:center;">
                                        <div style="width:52px; height:52px; border-radius:12px; background:#FFFFFF; border:1px solid var(--eia-border); display:inline-flex; align-items:center; justify-content:center; color: var(--eia-black);">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                        </div>
                                        <div style="margin-top:12px; font-size:13.5px; color: var(--eia-slate);">
                                            <label for="file" style="cursor:pointer; color: var(--eia-black); font-weight:600; border-bottom:1px solid var(--eia-gold); padding-bottom:1px;">
                                                Seleccionar un archivo
                                                <input id="file" name="file" type="file" class="sr-only" accept=".csv,.xlsx,.xls,.txt">
                                            </label>
                                            <span style="margin-left:4px;">o arrastra y suelta</span>
                                        </div>
                                        <p style="margin-top:8px; font-size:11px; color: var(--eia-mute); letter-spacing:0.06em; text-transform:uppercase;">
                                            CSV · Excel · TXT · máx 2 MB
                                        </p>
                                    </div>
                                </div>
                                @error('file')
                                    <p style="margin-top:8px; font-size:12.5px; color: var(--eia-red);">{{ $message }}</p>
                                @enderror
                            </div>

                            <div style="background:#FFFBEB; border:1px solid #FDE68A; border-left:3px solid var(--eia-gold); border-radius:10px; padding:14px 16px; margin-bottom:22px;">
                                <div style="display:flex; gap:10px; align-items:flex-start;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#92400E" stroke-width="2" style="flex-shrink:0; margin-top:1px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <h4 style="font-size:11px; color:#92400E; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:6px;">Importante</h4>
                                        <ul style="font-size:13px; color:#78350F; list-style:disc; padding-left:18px; line-height:1.6;">
                                            <li>Los archivos Excel se procesarán como CSV. Para mejores resultados, guarda como CSV.</li>
                                            <li>Los empleados existentes (mismo email) solo se actualizarán si hay cambios.</li>
                                            <li>Se generará automáticamente un ID único para empleados nuevos.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="admin-btn-primary w-full justify-center" style="padding:13px 20px; font-size:13.5px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5-5 5 5M12 5v12"/>
                                </svg>
                                Importar empleados
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Información lateral --}}
            <div class="space-y-6">
                {{-- Formato del archivo --}}
                <div class="admin-panel admin-fade admin-d2">
                    <div class="admin-panel-head">
                        <div>
                            <div class="admin-panel-title">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/>
                                </svg>
                                Formato requerido
                            </div>
                            <div class="admin-panel-sub">Columnas esperadas en el archivo.</div>
                        </div>
                    </div>
                    <div class="admin-panel-body">
                        <div class="space-y-2">
                            <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 14px; background:#FAFAFB; border:1px solid var(--eia-border); border-radius:10px;">
                                <span style="font-size:12.5px; font-weight:600; color: var(--eia-black); letter-spacing:0.02em;">NOMBRE COMPLETO</span>
                                <span class="admin-badge red">Requerida</span>
                            </div>
                            <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 14px; background:#FAFAFB; border:1px solid var(--eia-border); border-radius:10px;">
                                <span style="font-size:12.5px; font-weight:600; color: var(--eia-black); letter-spacing:0.02em;">ÁREA</span>
                                <span class="admin-badge">Opcional</span>
                            </div>
                            <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 14px; background:#FAFAFB; border:1px solid var(--eia-border); border-radius:10px;">
                                <span style="font-size:12.5px; font-weight:600; color: var(--eia-black); letter-spacing:0.02em;">DEPARTAMENTO</span>
                                <span class="admin-badge">Opcional</span>
                            </div>
                            <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 14px; background:#FAFAFB; border:1px solid var(--eia-border); border-radius:10px;">
                                <span style="font-size:12.5px; font-weight:600; color: var(--eia-black); letter-spacing:0.02em;">CORREO</span>
                                <span class="admin-badge red">Requerida</span>
                            </div>
                        </div>

                        <div style="margin-top:18px;">
                            <span class="admin-label">Ejemplo</span>
                            <div style="font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size:11.5px; color: var(--eia-black); background:#FAFAFB; border:1px solid var(--eia-border); border-radius:10px; padding:12px 14px; overflow-x:auto; line-height:1.7;">
                                NOMBRE COMPLETO | ÁREA | DEPARTAMENTO | CORREO<br>
                                Juan Pérez | Sistemas | TI | juan@empresa.com<br>
                                María García | RRHH | Admin | maria@empresa.com
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Plantilla --}}
                <div class="admin-panel admin-fade admin-d3">
                    <div class="admin-panel-head">
                        <div>
                            <div class="admin-panel-title">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 14l5 5 5-5M12 19V7"/>
                                </svg>
                                Plantilla CSV
                            </div>
                            <div class="admin-panel-sub">Descarga una plantilla lista para usar.</div>
                        </div>
                    </div>
                    <div class="admin-panel-body">
                        <a href="{{ route('admin.employees.template') }}" class="admin-btn-primary w-full justify-center">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 14l5 5 5-5M12 19V7"/>
                            </svg>
                            Descargar plantilla
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .space-y-2 > * + * { margin-top: 8px; }
    .space-y-6 > * + * { margin-top: 24px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.querySelector('.upload-zone');
    const fileInput = document.getElementById('file');

    if (dropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        dropZone.addEventListener('drop', handleDrop, false);
    }

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        dropZone.classList.add('dragover');
    }

    function unhighlight(e) {
        dropZone.classList.remove('dragover');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            fileInput.files = files;
            updateFileDisplay(files[0]);
        }
    }

    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            updateFileDisplay(this.files[0]);
        }
    });

    function updateFileDisplay(file) {
        const fileName = file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        dropZone.classList.add('upload-zone-success');
        dropZone.innerHTML = `
            <div style="text-align:center;">
                <div style="width:52px; height:52px; border-radius:12px; background:#FFFFFF; border:1px solid #A7F3D0; display:inline-flex; align-items:center; justify-content:center; color:#047857;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div style="margin-top:12px; font-size:13.5px; color:#065F46; font-weight:600;">${fileName}</div>
                <p style="margin-top:4px; font-size:11px; color:#047857; letter-spacing:0.06em; text-transform:uppercase;">${fileSize} MB · listo para subir</p>
            </div>
        `;
    }
});
</script>
@endsection
