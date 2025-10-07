@extends('layouts.app')

@push('styles')
<style>
/* Animaciones y transiciones suaves */
.upload-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateY(0);
}

.upload-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.gradient-text {
    background: linear-gradient(135deg, #DC2626 0%, #FBBF24 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.upload-zone {
    border: 2px dashed #E5E7EB;
    transition: all 0.3s ease;
}

.upload-zone:hover {
    border-color: #DC2626;
    background-color: #FEF2F2;
}

.upload-zone.dragover {
    border-color: #FBBF24;
    background-color: #FFFBEB;
}
</style>
@endpush

@section('title', 'Importar Empleados')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-yellow-50">
    <!-- Header mejorado con gradiente rojo-amarillo -->
    <div class="bg-gradient-to-r from-red-600 via-orange-500 to-yellow-500 text-white">
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.employees.index') }}" class="p-2 rounded-full bg-white/20 hover:bg-white/30 transition-all duration-300 transform hover:scale-110">
                        <svg width="24px" height="24px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                            <path fill="currentColor" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"/>
                            <path fill="currentColor" d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold">📁 Importar Empleados</h1>
                        <p class="text-orange-100 text-sm mt-1">Carga archivos CSV o Excel para agregar empleados al sistema</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Mensajes de éxito/error con tema rojo-amarillo -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 mb-2">Errores encontrados:</h3>
                        <ul class="text-sm text-red-700 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulario de importación -->
            <div class="lg:col-span-2">
                <div class="upload-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-yellow-50 rounded-t-2xl">
                        <h3 class="text-lg font-semibold gradient-text flex items-center">
                            <i class="fas fa-cloud-upload-alt text-red-500 mr-3"></i>
                            Subir Archivo de Empleados
                        </h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.employees.process-import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-6">
                                <label for="file" class="block text-sm font-medium text-gray-700 mb-3">
                                    Seleccionar archivo
                                </label>
                                <div class="upload-zone mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-lg transition-colors duration-200">
                                    <div class="space-y-1 text-center">
                                        <div class="mx-auto h-12 w-12 text-gray-400">
                                            <i class="fas fa-file-upload text-4xl"></i>
                                        </div>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500">
                                                <span>Subir un archivo</span>
                                                <input id="file" name="file" type="file" class="sr-only" accept=".csv,.xlsx,.xls,.txt">
                                            </label>
                                            <p class="pl-1">o arrastra y suelta</p>
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            CSV, Excel, TXT hasta 2MB
                                        </p>
                                    </div>
                                </div>
                                @error('file')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">
                                            Importante
                                        </h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>• Los archivos Excel se procesarán como CSV. Para mejores resultados, guarda como CSV.</p>
                                            <p>• Los empleados existentes (mismo email) solo se actualizarán si hay cambios.</p>
                                            <p>• Se generará automáticamente un ID único para empleados nuevos.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" 
                                    class="w-full px-6 py-4 bg-gradient-to-r from-red-500 to-yellow-500 text-white font-semibold rounded-lg shadow-lg hover:from-red-600 hover:to-yellow-600 transform hover:scale-105 transition-all duration-200 text-lg">
                                <i class="fas fa-upload mr-2"></i>Importar Empleados
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Información lateral con tema rojo-amarillo -->
            <div class="space-y-6">
                <!-- Formato del archivo -->
                <div class="upload-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-t-2xl">
                        <h3 class="text-lg font-semibold gradient-text flex items-center">
                            <i class="fas fa-info-circle text-orange-500 mr-3"></i>
                            Formato Requerido
                        </h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">El archivo debe contener estas columnas:</p>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                <span class="font-medium text-gray-900">NOMBRE COMPLETO</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Requerida
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                <span class="font-medium text-gray-900">ÁREA</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Opcional
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                                <span class="font-medium text-gray-900">DEPARTAMENTO</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    Opcional
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                <span class="font-medium text-gray-900">CORREO</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Requerida
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-gradient-to-r from-red-50 to-yellow-50 rounded-lg border border-orange-200">
                            <h4 class="font-medium gradient-text mb-2">Ejemplo:</h4>
                            <div class="text-xs font-mono text-gray-800 bg-white p-3 rounded border overflow-x-auto">
                                NOMBRE COMPLETO | ÁREA | DEPARTAMENTO | CORREO<br>
                                Juan Pérez | Sistemas | TI | juan@empresa.com<br>
                                María García | RRHH | Admin | maria@empresa.com
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Plantilla -->
                <div class="upload-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-t-2xl">
                        <h3 class="text-lg font-semibold gradient-text flex items-center">
                            <i class="fas fa-download text-yellow-500 mr-3"></i>
                            Plantilla
                        </h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">Descarga una plantilla lista para usar:</p>
                        <a href="{{ route('admin.employees.template') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-orange-500 to-yellow-500 text-white font-semibold rounded-lg shadow-lg hover:from-orange-600 hover:to-yellow-600 transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-download mr-2"></i>Descargar Plantilla CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mejorar la experiencia de drag & drop
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.querySelector('[data-drop-zone]') || document.querySelector('label[for="file"]').closest('.border-dashed');
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
        dropZone.classList.add('border-emerald-400', 'bg-emerald-50');
    }
    
    function unhighlight(e) {
        dropZone.classList.remove('border-emerald-400', 'bg-emerald-50');
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
        const dropZoneText = dropZone.querySelector('.space-y-1');
        
        dropZoneText.innerHTML = `
            <div class="mx-auto h-12 w-12 text-emerald-500">
                <i class="fas fa-file-check text-4xl"></i>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <span class="font-medium text-emerald-600">${fileName}</span>
                <p class="text-xs text-gray-500">${fileSize} MB</p>
            </div>
        `;
    }
});
</script>
@endsection