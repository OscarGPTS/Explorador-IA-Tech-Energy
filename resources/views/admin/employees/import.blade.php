@extends('layouts.app')

@section('title', 'Importar Empleados')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-teal-100 dark:from-gray-900 dark:to-gray-800">
    <div class="container-fluid px-6 py-8">
        <!-- Header moderno -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-4 lg:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-upload text-white text-xl"></i>
                        </div>
                        Importar Empleados
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Carga archivos CSV o Excel para agregar empleados al sistema</p>
                </div>
                <a href="{{ route('admin.employees.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg shadow-lg hover:bg-gray-700 transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Volver a Lista
                </a>
            </div>
        </div>

        <!-- Mensajes de éxito/error -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900 border-l-4 border-green-400 p-4 rounded-lg shadow-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900 border-l-4 border-red-400 p-4 rounded-lg shadow-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-900 border-l-4 border-red-400 p-4 rounded-lg shadow-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">Errores encontrados:</h3>
                        <ul class="text-sm text-red-700 dark:text-red-300 list-disc list-inside">
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
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-cloud-upload-alt text-emerald-500 mr-3"></i>
                            Subir Archivo de Empleados
                        </h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.employees.process-import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-6">
                                <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    Seleccionar archivo
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-emerald-400 dark:hover:border-emerald-500 transition-colors duration-200">
                                    <div class="space-y-1 text-center">
                                        <div class="mx-auto h-12 w-12 text-gray-400">
                                            <i class="fas fa-file-upload text-4xl"></i>
                                        </div>
                                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                            <label for="file" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-emerald-500">
                                                <span>Subir un archivo</span>
                                                <input id="file" name="file" type="file" class="sr-only" accept=".csv,.xlsx,.xls,.txt">
                                            </label>
                                            <p class="pl-1">o arrastra y suelta</p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            CSV, Excel, TXT hasta 2MB
                                        </p>
                                    </div>
                                </div>
                                @error('file')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="bg-amber-50 dark:bg-amber-900 border border-amber-200 dark:border-amber-700 rounded-lg p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-amber-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-amber-800 dark:text-amber-200">
                                            Importante
                                        </h3>
                                        <div class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                                            <p>• Los archivos Excel se procesarán como CSV. Para mejores resultados, guarda como CSV.</p>
                                            <p>• Los empleados existentes (mismo email) solo se actualizarán si hay cambios.</p>
                                            <p>• Se generará automáticamente un ID único para empleados nuevos.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" 
                                    class="w-full px-6 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg shadow-lg hover:from-emerald-600 hover:to-teal-700 transform hover:scale-105 transition-all duration-200 text-lg">
                                <i class="fas fa-upload mr-2"></i>Importar Empleados
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Información lateral -->
            <div class="space-y-6">
                <!-- Formato del archivo -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                            Formato Requerido
                        </h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 dark:text-gray-400 mb-4">El archivo debe contener estas columnas:</p>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="font-medium text-gray-900 dark:text-white">NOMBRE COMPLETO</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Requerida
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="font-medium text-gray-900 dark:text-white">ÁREA</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Opcional
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="font-medium text-gray-900 dark:text-white">DEPARTAMENTO</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Opcional
                                </span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <span class="font-medium text-gray-900 dark:text-white">CORREO</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Requerida
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                            <h4 class="font-medium text-blue-900 dark:text-blue-200 mb-2">Ejemplo:</h4>
                            <div class="text-xs font-mono text-blue-800 dark:text-blue-300 bg-white dark:bg-gray-800 p-3 rounded border overflow-x-auto">
                                NOMBRE COMPLETO | ÁREA | DEPARTAMENTO | CORREO<br>
                                Juan Pérez | Sistemas | TI | juan@empresa.com<br>
                                María García | RRHH | Admin | maria@empresa.com
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Plantilla -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-download text-emerald-500 mr-3"></i>
                            Plantilla
                        </h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Descarga una plantilla lista para usar:</p>
                        <a href="{{ route('admin.employees.template') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg shadow-lg hover:from-emerald-600 hover:to-teal-700 transform hover:scale-105 transition-all duration-200">
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