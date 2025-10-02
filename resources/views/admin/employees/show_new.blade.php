@extends('layouts.app')

@section('title', 'Detalles del Empleado')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 to-teal-100 dark:from-gray-900 dark:to-gray-800">
    <div class="container-fluid px-6 py-8">
        <!-- Header moderno -->
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-4 lg:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-user text-white text-xl"></i>
                        </div>
                        Detalles del Empleado
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Información completa del empleado en el sistema</p>
                </div>
                <a href="{{ route('admin.employees.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg shadow-lg hover:bg-gray-700 transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Volver a Lista
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Información principal -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                    <!-- Avatar y nombre -->
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-20 h-20 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-full flex items-center justify-center">
                                    <span class="text-2xl font-bold text-white">
                                        {{ strtoupper(substr($employee->full_name ?? 'E', 0, 1)) }}{{ strtoupper(substr(explode(' ', $employee->full_name)[1] ?? '', 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-6">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $employee->full_name }}
                                </h2>
                                <p class="text-gray-600 dark:text-gray-400">{{ $employee->position ?? 'Posición no especificada' }}</p>
                                <div class="flex items-center mt-2">
                                    @if($employee->hasSystemAccess())
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <i class="fas fa-check-circle mr-1"></i>Acceso al Sistema
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            <i class="fas fa-clock mr-1"></i>Sin Acceso
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de contacto -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                            <i class="fas fa-address-card text-emerald-500 mr-3"></i>
                            Información de Contacto
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                    Correo Electrónico
                                </label>
                                <div class="flex items-center">
                                    <i class="fas fa-envelope text-emerald-500 mr-3"></i>
                                    <a href="mailto:{{ $employee->email }}" class="text-gray-900 dark:text-white font-medium hover:text-emerald-600 transition-colors">{{ $employee->email }}</a>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                    Teléfono
                                </label>
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-emerald-500 mr-3"></i>
                                    @if($employee->phone)
                                        <a href="tel:{{ $employee->phone }}" class="text-gray-900 dark:text-white font-medium hover:text-emerald-600 transition-colors">
                                            {{ $employee->phone }}
                                        </a>
                                        @if($employee->extension)
                                            <span class="text-sm text-gray-500 ml-2">Ext. {{ $employee->extension }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-900 dark:text-white font-medium">No especificado</span>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                    Departamento
                                </label>
                                <div class="flex items-center">
                                    <i class="fas fa-building text-emerald-500 mr-3"></i>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $employee->department ?? 'No especificado' }}</span>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                    Ubicación
                                </label>
                                <div class="flex items-center">
                                    <i class="fas fa-map-marker-alt text-emerald-500 mr-3"></i>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $employee->location ?? 'No especificada' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información laboral -->
                <div class="mt-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-briefcase text-emerald-500 mr-3"></i>
                            Información Laboral
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                    ID de Empleado
                                </label>
                                <div class="flex items-center">
                                    <i class="fas fa-hashtag text-emerald-500 mr-3"></i>
                                    <span class="text-gray-900 dark:text-white font-mono font-medium">{{ $employee->employee_id }}</span>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                    Estado
                                </label>
                                <div class="flex items-center">
                                    @switch($employee->status)
                                        @case('active')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <i class="fas fa-circle mr-1"></i>Activo
                                            </span>
                                            @break
                                        @case('inactive')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                <i class="fas fa-circle mr-1"></i>Inactivo
                                            </span>
                                            @break
                                        @case('on_leave')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                <i class="fas fa-circle mr-1"></i>En Licencia
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                <i class="fas fa-circle mr-1"></i>{{ $employee->status ?? 'Activo' }}
                                            </span>
                                    @endswitch
                                </div>
                            </div>

                            @if($employee->hire_date)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                    Fecha de Contratación
                                </label>
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-plus text-emerald-500 mr-3"></i>
                                    <span class="text-gray-900 dark:text-white font-medium">{{ $employee->hire_date->format('d/m/Y') }}</span>
                                    <span class="text-sm text-gray-500 ml-2">({{ $employee->hire_date->diffForHumans() }})</span>
                                </div>
                            </div>
                            @endif

                            @if($employee->manager_email)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                    Supervisor
                                </label>
                                <div class="flex items-center">
                                    <i class="fas fa-user-tie text-emerald-500 mr-3"></i>
                                    <a href="mailto:{{ $employee->manager_email }}" class="text-gray-900 dark:text-white font-medium hover:text-emerald-600 transition-colors">{{ $employee->manager_email }}</a>
                                </div>
                            </div>
                            @endif
                        </div>

                        @if($employee->notes)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">
                                Notas
                            </label>
                            <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                                <p class="text-gray-900 dark:text-white">{{ $employee->notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Panel lateral -->
            <div class="space-y-6">
                <!-- Estado del sistema -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-shield-alt text-emerald-500 mr-3"></i>
                            Acceso al Sistema
                        </h3>
                    </div>
                    <div class="p-6 text-center">
                        @if($employee->hasSystemAccess())
                            <div class="mb-4">
                                <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto">
                                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-2xl"></i>
                                </div>
                            </div>
                            <h4 class="text-lg font-semibold text-green-600 dark:text-green-400 mb-2">Tiene Acceso</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">Este empleado tiene acceso al sistema a través de Google Auth.</p>
                            @if($employee->user)
                                <div class="bg-green-50 dark:bg-green-900 rounded-lg p-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Usuario vinculado:</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $employee->user->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $employee->user->email }}</p>
                                </div>
                            @endif
                        @else
                            <div class="mb-4">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto">
                                    <i class="fas fa-times-circle text-gray-400 text-2xl"></i>
                                </div>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-600 dark:text-gray-400 mb-2">Sin Acceso</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Este empleado aún no ha accedido al sistema.</p>
                        @endif
                    </div>
                </div>

                <!-- Información de registro -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-database text-emerald-500 mr-3"></i>
                            Información de Registro
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($employee->data_imported_at)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Fecha de Importación</span>
                            <div class="text-right">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->data_imported_at->format('d/m/Y') }}</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $employee->data_imported_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Fuente</span>
                            <div>
                                @switch($employee->import_source)
                                    @case('manual_seed')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            Datos de prueba
                                        </span>
                                        @break
                                    @case('csv_upload')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            CSV
                                        </span>
                                        @break
                                    @case('excel_upload')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Excel
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $employee->import_source ?? 'Desconocida' }}
                                        </span>
                                @endswitch
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Registrado</span>
                            <div class="text-right">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $employee->created_at->format('d/m/Y') }}</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $employee->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-tools text-emerald-500 mr-3"></i>
                            Acciones Rápidas
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="mailto:{{ $employee->email }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg shadow-lg hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-envelope mr-2"></i>Enviar Email
                        </a>
                        
                        @if($employee->phone)
                        <a href="tel:{{ $employee->phone }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-lg shadow-lg hover:from-green-600 hover:to-green-700 transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-phone mr-2"></i>Llamar
                        </a>
                        @endif
                        
                        <a href="{{ route('admin.employees.export', ['search' => $employee->email]) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg shadow-lg hover:from-emerald-600 hover:to-teal-700 transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-download mr-2"></i>Exportar Datos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection