@extends('layouts.app')

@section('title', 'Administración de Empleados')

@section('content')
<div class="">
    <div class="container-fluid px-6 py-8">
        <!-- Header moderno -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-4 lg:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        Administración de Empleados
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Gestiona y organiza la información de todos los empleados</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('admin.employees.import') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-lg shadow-lg hover:from-green-600 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-upload mr-2"></i>Importar Empleados
                    </a>
                    <a href="{{ route('admin.employees.export', request()->query()) }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:from-blue-600 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-download mr-2"></i>Exportar CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas mejoradas -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Total Empleados</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($totalEmployees) }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Con Acceso</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($withAccess) }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-user-check text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Sin Acceso</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($withoutAccess) }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-r from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-user-times text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">Departamentos</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $departments->count() }}</p>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-building text-white text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros modernos -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl mb-8 border border-gray-100 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-filter text-blue-500 mr-3"></i>
                    Filtros de Búsqueda
                </h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('admin.employees.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-search mr-1"></i>Búsqueda
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                                   id="search" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Nombre, email, ID...">
                        </div>
                        
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-building mr-1"></i>Departamento
                            </label>
                            <select class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
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
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-toggle-on mr-1"></i>Estado
                            </label>
                            <select class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                                    id="status" name="status">
                                <option value="">Todos</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                <option value="on_leave" {{ request('status') === 'on_leave' ? 'selected' : '' }}>En Licencia</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="access_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-key mr-1"></i>Acceso
                            </label>
                            <select class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                                    id="access_filter" name="access_filter">
                                <option value="">Todos</option>
                                <option value="with_access" {{ request('access_filter') === 'with_access' ? 'selected' : '' }}>Con acceso</option>
                                <option value="without_access" {{ request('access_filter') === 'without_access' ? 'selected' : '' }}>Sin acceso</option>
                            </select>
                        </div>
                        
                        <div class="flex flex-col justify-end">
                            <button type="submit" 
                                    class="w-full px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:from-blue-600 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200">
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

        <!-- Tabla de empleados moderna -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-table text-blue-500 mr-3"></i>
                    Lista de Empleados 
                    <span class="ml-2 px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm font-medium rounded-full">
                        {{ $employees->total() }} resultados
                    </span>
                </h3>
            </div>
            
            <div class="p-6">
                @if($employees->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Empleado</th>
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Contacto</th>
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Posición</th>
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Departamento</th>
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Acceso</th>
                                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($employees as $employee)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                        <td class="py-4 px-4">
                                            <span class="text-sm font-mono text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                                {{ $employee->employee_id }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4 shadow-lg">
                                                    <span class="text-white font-bold text-sm">
                                                        {{ strtoupper(substr($employee->first_name, 0, 1)) }}{{ strtoupper(substr($employee->last_name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->full_name }}</div>
                                                    @if($employee->phone)
                                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-1">
                                                            <i class="fas fa-phone mr-1"></i>{{ $employee->phone }}
                                                            @if($employee->extension)
                                                                <span class="ml-1">Ext. {{ $employee->extension }}</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="text-sm text-gray-900 dark:text-white">{{ $employee->email }}</div>
                                            @if($employee->location)
                                                <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-1">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $employee->location }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $employee->position }}</div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                <i class="fas fa-building mr-1"></i>
                                                {{ $employee->department }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            @switch($employee->status)
                                                @case('active')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                        Activo
                                                    </span>
                                                    @break
                                                @case('inactive')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                        <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                                        Inactivo
                                                    </span>
                                                    @break
                                                @case('on_leave')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                                        En Licencia
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                        {{ $employee->status }}
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td class="py-4 px-4">
                                            @if($employee->hasSystemAccess())
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Sí
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    No
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.employees.show', $employee) }}" 
                                                   class="inline-flex items-center px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded-lg hover:bg-blue-600 transition-colors duration-200"
                                                   title="Ver detalles">
                                                    <i class="fas fa-eye mr-1"></i>Ver
                                                </a>
                                                <a href="mailto:{{ $employee->email }}" 
                                                   class="inline-flex items-center px-3 py-1 bg-green-500 text-white text-xs font-medium rounded-lg hover:bg-green-600 transition-colors duration-200"
                                                   title="Enviar email">
                                                    <i class="fas fa-envelope mr-1"></i>Email
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación moderna -->
                    <div class="flex flex-col sm:flex-row items-center justify-between mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-4 sm:mb-0">
                            Mostrando {{ $employees->firstItem() }} a {{ $employees->lastItem() }} 
                            de {{ $employees->total() }} resultados
                        </div>
                        <div>
                            {{ $employees->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No se encontraron empleados</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            @if(request()->hasAny(['search', 'department', 'status', 'access_filter']))
                                Intenta ajustar los filtros de búsqueda o elimina algunos criterios.
                            @else
                                Comienza importando empleados desde un archivo CSV o Excel.
                            @endif
                        </p>
                        <a href="{{ route('admin.employees.import') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:from-blue-600 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-upload mr-2"></i>Importar Empleados
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection