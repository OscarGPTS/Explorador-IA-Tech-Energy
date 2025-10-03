@extends('layouts.app')

@section('content')
<div class="px-4 pt-10">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <div class="flex">
                <a href="/" class="mr-2 flex items-center justify-center">
                    <?xml version="1.0" encoding="utf-8"?>
                    <svg width="22px" height="22px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"><path fill="#000000" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"/><path fill="#000000" d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"/></svg>
                </a>
                <h1 class="text-2xl font-semibold leading-6 text-gray-900">🚨 Monitoreo de Errores</h1>
            </div>
            <p class="mt-2 text-sm text-gray-700">
                Análisis detallado de errores y fallos del sistema con información completa de request y response.
            </p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none space-x-2">
            <a href="{{ route('admin.stats.export', ['type' => 'modules', 'format' => 'csv', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
               class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                📥 Exportar Errores CSV
            </a>
            <a href="{{ route('admin.stats.export', ['type' => 'modules', 'format' => 'excel', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
               class="inline-flex items-center rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500">
                📊 Exportar Errores Excel
            </a>
        </div>
    </div>

    <!-- Navegación -->
    <div class="border-b border-gray-200 mt-6">
        <div class="sm:flex sm:items-baseline">
            <nav class="flex space-x-8">
                <a href="{{ route('admin.stats.dashboard') }}" 
                   class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    🏠 Dashboard
                </a>
                <a href="{{ route('admin.stats.users') }}" 
                   class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    👥 Usuarios
                </a>
                <a href="{{ route('admin.stats.chats') }}" 
                   class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    💬 Chats
                </a>
                <a href="{{ route('admin.stats.modules') }}" 
                   class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    📊 Módulos
                </a>
                <a href="{{ route('admin.stats.errors') }}" 
                   class="border-b-2 border-red-500 py-4 px-1 text-sm font-medium text-red-600">
                    🚨 Errores
                </a>
            </nav>
        </div>
    </div>

    <!-- Panel de Filtros -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">🔍 Filtros</h3>
        <form method="GET" action="{{ route('admin.stats.errors') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
            </div>
            <div>
                <label for="module_type" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Módulo</label>
                <select id="module_type" name="module_type" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option value="">Todos los módulos</option>
                    @foreach($availableModules as $module)
                        <option value="{{ $module }}" {{ $moduleType == $module ? 'selected' : '' }}>
                            {{ ucfirst($module) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status_code" class="block text-sm font-medium text-gray-700 mb-2">Código de Estado</label>
                <select id="status_code" name="status_code" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option value="">Todos los códigos</option>
                    @foreach($availableStatusCodes as $code)
                        <option value="{{ $code }}" {{ $statusCode == $code ? 'selected' : '' }}>
                            {{ $code }} - {{ $code >= 500 ? 'Error Servidor' : ($code >= 400 ? 'Error Cliente' : 'OK') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                    🔍 Filtrar Errores
                </button>
                <a href="{{ route('admin.stats.errors') }}" 
                   class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    🗑️ Limpiar Filtros
                </a>
            </div>
        </form>
    </div>

    <!-- Estadísticas de errores -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-500 bg-opacity-75">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.268 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Errores</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($errorStats['total_errors']) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-500 bg-opacity-75">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Errores Hoy</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($errorStats['errors_today']) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-500 bg-opacity-75">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Error Más Común</dt>
                        <dd class="text-lg font-medium text-gray-900">
                            {{ $errorStats['most_common_error']->status_code ?? 'N/A' }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-500 bg-opacity-75">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Módulos Afectados</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ count($errorStats['errors_by_module']) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de errores -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                📋 Lista de Errores 
                <span class="text-sm text-gray-500">({{ $errors->total() }} total)</span>
            </h3>
        </div>
        
        @if($errors->count() > 0)
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha/Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Módulo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Error</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($errors as $error)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $error->created_at->format('Y-m-d H:i:s') }}
                            <div class="text-xs text-gray-500">
                                {{ $error->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($error->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($error->user)
                                <div>{{ $error->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $error->user->email }}</div>
                            @else
                                <span class="text-gray-400">Usuario eliminado</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs">
                                {{ Str::limit($error->message, 100) }}
                                @if($error->error_details)
                                    <div class="text-xs text-red-600 mt-1">
                                        {{ json_decode($error->error_details, true)['exception_class'] ?? 'Error desconocido' }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $error->status_code >= 500 ? 'bg-red-100 text-red-800' : 
                                   ($error->status_code >= 400 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                {{ $error->status_code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="showErrorDetails({{ json_encode($error) }})" 
                                    class="text-red-600 hover:text-red-900">
                                🔍 Ver Detalles
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $errors->appends(request()->query())->links() }}
        </div>
        @else
        <div class="px-6 py-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No se encontraron errores</h3>
            <p class="mt-1 text-sm text-gray-500">¡Excelente! No hay errores en el rango de fechas seleccionado.</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal para detalles del error -->
<div id="errorModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            🚨 Detalles del Error
                        </h3>
                        <div id="errorDetails" class="space-y-4">
                            <!-- El contenido se llenará dinámicamente -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button onclick="closeErrorModal()" type="button" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showErrorDetails(error) {
    const modal = document.getElementById('errorModal');
    const detailsDiv = document.getElementById('errorDetails');
    
    let errorDetailsObj = null;
    try {
        errorDetailsObj = typeof error.error_details === 'string' ? JSON.parse(error.error_details) : error.error_details;
    } catch (e) {
        errorDetailsObj = null;
    }
    
    let requestData = null;
    try {
        requestData = typeof error.request_data === 'string' ? JSON.parse(error.request_data) : error.request_data;
    } catch (e) {
        requestData = null;
    }
    
    let responseData = null;
    try {
        responseData = typeof error.response_data === 'string' ? JSON.parse(error.response_data) : error.response_data;
    } catch (e) {
        responseData = null;
    }
    
    detailsDiv.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-900 mb-2">📋 Información General</h4>
                <div class="bg-gray-50 p-3 rounded text-sm space-y-1">
                    <div><strong>Fecha:</strong> ${error.created_at}</div>
                    <div><strong>Módulo:</strong> ${error.type}</div>
                    <div><strong>Usuario:</strong> ${error.user ? error.user.name + ' (' + error.user.email + ')' : 'Usuario eliminado'}</div>
                    <div><strong>Método:</strong> ${error.method || 'N/A'}</div>
                    <div><strong>URL:</strong> ${error.url || 'N/A'}</div>
                    <div><strong>IP:</strong> ${error.ip_address || 'N/A'}</div>
                    <div><strong>Tiempo de respuesta:</strong> ${error.response_time || 'N/A'}ms</div>
                </div>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900 mb-2">🚨 Detalles del Error</h4>
                <div class="bg-red-50 p-3 rounded text-sm space-y-1">
                    <div><strong>Código:</strong> ${error.status_code}</div>
                    <div><strong>Mensaje:</strong> ${error.message}</div>
                    ${errorDetailsObj ? `
                        <div><strong>Excepción:</strong> ${errorDetailsObj.exception_class || 'N/A'}</div>
                        <div><strong>Archivo:</strong> ${errorDetailsObj.file || 'N/A'}</div>
                        <div><strong>Línea:</strong> ${errorDetailsObj.line || 'N/A'}</div>
                    ` : ''}
                </div>
            </div>
        </div>
        
        ${requestData ? `
            <div class="mt-4">
                <h4 class="font-medium text-gray-900 mb-2">📤 Datos del Request</h4>
                <div class="bg-blue-50 p-3 rounded">
                    <pre class="text-xs overflow-x-auto whitespace-pre-wrap">${JSON.stringify(requestData, null, 2)}</pre>
                </div>
            </div>
        ` : ''}
        
        ${responseData ? `
            <div class="mt-4">
                <h4 class="font-medium text-gray-900 mb-2">📥 Datos del Response</h4>
                <div class="bg-yellow-50 p-3 rounded">
                    <pre class="text-xs overflow-x-auto whitespace-pre-wrap">${JSON.stringify(responseData, null, 2)}</pre>
                </div>
            </div>
        ` : ''}
        
        ${error.stack_trace ? `
            <div class="mt-4">
                <h4 class="font-medium text-gray-900 mb-2">🔧 Stack Trace</h4>
                <div class="bg-gray-100 p-3 rounded">
                    <pre class="text-xs overflow-x-auto whitespace-pre-wrap">${error.stack_trace}</pre>
                </div>
            </div>
        ` : ''}
    `;
    
    modal.classList.remove('hidden');
}

function closeErrorModal() {
    document.getElementById('errorModal').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera
document.getElementById('errorModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeErrorModal();
    }
});
</script>
@endsection