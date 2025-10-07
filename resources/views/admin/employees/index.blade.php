@extends('layouts.app')

@push('styles')
<style>
/* Animaciones y transiciones suaves */
.stats-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateY(0);
}

.stats-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.gradient-text {
    background: linear-gradient(135deg, #DC2626 0%, #FBBF24 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>
@endpush

@section('title', 'Administración de Empleados')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-yellow-50">
    <!-- Header mejorado con gradiente rojo-amarillo -->
    <div class="bg-gradient-to-r from-red-600 via-orange-500 to-yellow-500 text-white">
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="/" class="p-2 rounded-full bg-white/20 hover:bg-white/30 transition-all duration-300 transform hover:scale-110">
                        <svg width="24px" height="24px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                            <path fill="currentColor" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"/>
                            <path fill="currentColor" d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold">👥 Administración de Empleados</h1>
                        <p class="text-orange-100 text-sm mt-1">Gestiona y organiza la información de todos los empleados</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.employees.import') }}" 
                       class="flex items-center space-x-2 bg-white/20 hover:bg-white/30 backdrop-filter backdrop-blur-sm border border-white/30 font-medium rounded-full text-sm px-6 py-3 text-white transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-upload"></i>
                        <span>Importar</span>
                    </a>
                    <a href="{{ route('admin.employees.export', request()->query()) }}" 
                       class="flex items-center space-x-2 bg-white/20 hover:bg-white/30 backdrop-filter backdrop-blur-sm border border-white/30 font-medium rounded-full text-sm px-6 py-3 text-white transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-download"></i>
                        <span>Exportar</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Estadísticas mejoradas -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Total Empleados</p>
                        <p class="text-3xl font-bold gradient-text mt-2">{{ number_format($totalEmployees) }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Con Acceso</p>
                        <p class="text-3xl font-bold gradient-text mt-2">{{ number_format($withAccess) }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-user-check text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Sin Acceso</p>
                        <p class="text-3xl font-bold gradient-text mt-2">{{ number_format($withoutAccess) }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-user-times text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-red-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Departamentos</p>
                        <p class="text-3xl font-bold gradient-text mt-2">{{ $departments->count() }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-r from-red-400 to-red-500 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-building text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros modernos con tema rojo-amarillo -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg mb-8 border border-gray-100">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-yellow-50 rounded-t-2xl">
                <h3 class="text-lg font-semibold gradient-text flex items-center">
                    <i class="fas fa-filter text-red-500 mr-3"></i>
                    Filtros de Búsqueda
                </h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('admin.employees.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-search mr-1 text-red-500"></i>Búsqueda
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" 
                                   id="search" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Nombre, email, ID...">
                        </div>
                        
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-building mr-1 text-orange-500"></i>Departamento
                            </label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200" 
                                    id="department" name="department">
                                <option value="">Todos los departamentos</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>
                                        {{ $dept }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-toggle-on mr-1 text-yellow-500"></i>Estado
                            </label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200" 
                                    id="status" name="status">
                                <option value="">Todos</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>En Licencia</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="access_filter" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-key mr-1 text-red-400"></i>Acceso
                            </label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-900 focus:ring-2 focus:ring-red-400 focus:border-red-400 transition-all duration-200" 
                                    id="access_filter" name="access_filter">
                                <option value="">Todos</option>
                                <option value="with_access" {{ request('access_filter') === 'with_access' ? 'selected' : '' }}>Con acceso</option>
                                <option value="without_access" {{ request('access_filter') === 'without_access' ? 'selected' : '' }}>Sin acceso</option>
                            </select>
                        </div>
                        
                        <div class="flex flex-col justify-end">
                            <button type="submit" 
                                    class="w-full px-6 py-3 bg-gradient-to-r from-red-500 to-yellow-500 text-white font-semibold rounded-lg shadow-lg hover:from-red-600 hover:to-yellow-600 transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-search mr-2"></i>Filtrar
                            </button>
                        </div>
                    </div>
                    
                    @if(request()->hasAny(['search', 'department', 'status', 'access_filter']))
                        <div class="flex justify-center pt-4">
                            <a href="{{ route('admin.employees.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200">
                                <i class="fas fa-times mr-2"></i>Limpiar filtros
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Tabla de empleados moderna con tema rojo-amarillo -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-yellow-50 rounded-t-2xl">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-semibold gradient-text flex items-center mb-4 sm:mb-0">
                        <i class="fas fa-table text-red-500 mr-3"></i>
                        Lista de Empleados 
                        <span class="ml-2 px-3 py-1 bg-red-100 text-red-800 text-sm font-medium rounded-full">
                            {{ $employees->total() }} resultados
                        </span>
                    </h3>
                    
                    <!-- Controles de selección múltiple -->
                    <div id="bulk-actions" class="hidden flex items-center space-x-3">
                        <span id="selected-count" class="text-sm text-gray-600"></span>
                        <button type="button" 
                                id="bulk-delete-btn"
                                class="inline-flex items-center px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition-colors duration-200">
                            <i class="fas fa-trash mr-2"></i>Eliminar Seleccionados
                        </button>
                        <button type="button" 
                                id="clear-selection-btn"
                                class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>Cancelar
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                @if($employees->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 uppercase tracking-wider w-12">
                                        <input type="checkbox" 
                                               id="select-all" 
                                               class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500">
                                    </th>
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Empleado</th>
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Contacto</th>
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Posición</th>
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Departamento</th>
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Acceso</th>
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($employees as $employee)
                                    <tr class="hover:bg-red-50/50 transition-colors duration-200">
                                        <td class="py-4 px-4">
                                            <input type="checkbox" 
                                                   class="employee-checkbox w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500" 
                                                   value="{{ $employee->id }}"
                                                   data-name="{{ $employee->full_name }}">
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="text-sm font-mono text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                                {{ $employee->employee_id }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-orange-500 rounded-full flex items-center justify-center mr-4 shadow-lg">
                                                    <span class="text-white font-bold text-sm">
                                                        {{ strtoupper(substr($employee->first_name, 0, 1)) }}{{ strtoupper(substr($employee->last_name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-900">{{ $employee->full_name }}</div>
                                                    @if($employee->phone)
                                                        <div class="text-xs text-gray-500 flex items-center mt-1">
                                                            <i class="fas fa-phone mr-1 text-red-400"></i>{{ $employee->phone }}
                                                            @if($employee->extension)
                                                                <span class="ml-1">Ext. {{ $employee->extension }}</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="text-sm text-gray-900">{{ $employee->email }}</div>
                                            @if($employee->location)
                                                <div class="text-xs text-gray-500 flex items-center mt-1">
                                                    <i class="fas fa-map-marker-alt mr-1 text-orange-400"></i>{{ $employee->location }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $employee->position }}</div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-building mr-1"></i>
                                                {{ $employee->department }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            @switch($employee->status)
                                                @case('active')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                        Activo
                                                    </span>
                                                    @break
                                                @case('inactive')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                                        Inactivo
                                                    </span>
                                                    @break
                                                @case('on_leave')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                                        En Licencia
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ $employee->status }}
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td class="py-4 px-4">
                                            @if($employee->hasSystemAccess())
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Sí
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    No
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.employees.show', $employee) }}" 
                                                   class="inline-flex items-center px-3 py-1 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-colors duration-200"
                                                   title="Ver detalles">
                                                    <i class="fas fa-eye mr-1"></i>Ver
                                                </a>
                                                <a href="mailto:{{ $employee->email }}" 
                                                   class="inline-flex items-center px-3 py-1 bg-orange-500 text-white text-xs font-medium rounded-lg hover:bg-orange-600 transition-colors duration-200"
                                                   title="Enviar email">
                                                    <i class="fas fa-envelope mr-1"></i>Email
                                                </a>
                                                <button type="button"
                                                        class="delete-employee-btn inline-flex items-center px-3 py-1 bg-yellow-500 text-white text-xs font-medium rounded-lg hover:bg-yellow-600 transition-colors duration-200"
                                                        title="Eliminar empleado"
                                                        data-employee-id="{{ $employee->id }}"
                                                        data-employee-name="{{ $employee->full_name }}">
                                                    <i class="fas fa-trash mr-1"></i>Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación moderna con tema rojo-amarillo -->
                    <div class="flex flex-col sm:flex-row items-center justify-between mt-6 pt-6 border-t border-gray-200">
                        <div class="text-sm text-gray-500 mb-4 sm:mb-0">
                            Mostrando {{ $employees->firstItem() }} a {{ $employees->lastItem() }} 
                            de {{ $employees->total() }} resultados
                        </div>
                        <div>
                            {{ $employees->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gradient-to-r from-red-100 to-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-red-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold gradient-text mb-2">No se encontraron empleados</h3>
                        <p class="text-gray-600 mb-6">
                            @if(request()->hasAny(['search', 'department', 'status', 'access_filter']))
                                Intenta ajustar los filtros de búsqueda o elimina algunos criterios.
                            @else
                                Comienza importando empleados desde un archivo CSV o Excel.
                            @endif
                        </p>
                        <a href="{{ route('admin.employees.import') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-yellow-500 text-white font-semibold rounded-lg shadow-lg hover:from-red-600 hover:to-yellow-600 transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-upload mr-2"></i>Importar Empleados
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modales de confirmación -->
<!-- Modal de confirmación para eliminación individual -->
<div id="delete-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="delete-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Cerrar modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">¿Estás seguro de que quieres eliminar a <span id="delete-employee-name" class="font-semibold"></span>?</h3>
                <button id="confirm-delete-btn" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                    Sí, eliminar
                </button>
                <button data-modal-hide="delete-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminación múltiple -->
<div id="bulk-delete-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="bulk-delete-modal">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Cerrar modal</span>
            </button>
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">¿Estás seguro de que quieres eliminar <span id="bulk-delete-count" class="font-semibold"></span> empleado(s) seleccionado(s)?</h3>
                <p class="mb-5 text-sm text-gray-400 dark:text-gray-500">Esta acción no se puede deshacer.</p>
                <button id="confirm-bulk-delete-btn" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                    Sí, eliminar todos
                </button>
                <button data-modal-hide="bulk-delete-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const clearSelectionBtn = document.getElementById('clear-selection-btn');
    
    let currentEmployeeId = null;
    let selectedEmployees = [];

    // Función para actualizar el estado de selección
    function updateSelectionState() {
        const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count > 0) {
            bulkActions.classList.remove('hidden');
            selectedCount.textContent = `${count} empleado(s) seleccionado(s)`;
            selectedEmployees = Array.from(checkedBoxes).map(cb => cb.value);
        } else {
            bulkActions.classList.add('hidden');
            selectedEmployees = [];
        }
        
        // Actualizar el estado del checkbox "Seleccionar todo"
        selectAllCheckbox.indeterminate = count > 0 && count < employeeCheckboxes.length;
        selectAllCheckbox.checked = count === employeeCheckboxes.length;
    }

    // Checkbox "Seleccionar todo"
    selectAllCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        employeeCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
        updateSelectionState();
    });

    // Checkboxes individuales
    employeeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectionState);
    });

    // Botón limpiar selección
    clearSelectionBtn.addEventListener('click', function() {
        employeeCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectAllCheckbox.checked = false;
        updateSelectionState();
    });

    // Botones de eliminar individual
    document.querySelectorAll('.delete-employee-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            currentEmployeeId = this.dataset.employeeId;
            const employeeName = this.dataset.employeeName;
            
            document.getElementById('delete-employee-name').textContent = employeeName;
            
            // Mostrar modal
            const modal = document.getElementById('delete-modal');
            modal.classList.remove('hidden');
        });
    });

    // Confirmar eliminación individual
    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
        if (!currentEmployeeId) return;
        
        // Mostrar loading
        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Eliminando...';
        this.disabled = true;
        
        fetch(`/admin/employees/${currentEmployeeId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito y recargar página
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message || 'Error al eliminar el empleado');
                this.innerHTML = 'Sí, eliminar';
                this.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el empleado');
            this.innerHTML = 'Sí, eliminar';
            this.disabled = false;
        });
    });

    // Botón de eliminación múltiple
    bulkDeleteBtn.addEventListener('click', function() {
        if (selectedEmployees.length === 0) return;
        
        document.getElementById('bulk-delete-count').textContent = selectedEmployees.length;
        
        // Mostrar modal
        const modal = document.getElementById('bulk-delete-modal');
        modal.classList.remove('hidden');
    });

    // Confirmar eliminación múltiple
    document.getElementById('confirm-bulk-delete-btn').addEventListener('click', function() {
        if (selectedEmployees.length === 0) return;
        
        // Mostrar loading
        this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Eliminando...';
        this.disabled = true;
        
        fetch('/admin/employees/bulk/delete', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                employee_ids: selectedEmployees
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message || 'Error al eliminar los empleados');
                this.innerHTML = 'Sí, eliminar todos';
                this.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar los empleados');
            this.innerHTML = 'Sí, eliminar todos';
            this.disabled = false;
        });
    });

    // Cerrar modales
    document.querySelectorAll('[data-modal-hide]').forEach(btn => {
        btn.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-hide');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
            }
        });
    });
});
</script>
@endsection