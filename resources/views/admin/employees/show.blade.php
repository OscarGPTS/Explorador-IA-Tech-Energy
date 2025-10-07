@extends('layouts.app')

@push('styles')
<style>
/* Animaciones y transiciones suaves */
.info-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateY(0);
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.gradient-text {
    background: linear-gradient(135deg, #DC2626 0%, #FBBF24 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.info-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.025em;
    margin-bottom: 0.5rem;
    display: block;
}

.info-value {
    font-size: 1rem;
    font-weight: 600;
    color: #1F2937;
    line-height: 1.5;
}
</style>
@endpush

@section('title', 'Detalles del Empleado')

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
                        <h1 class="text-3xl font-bold">👤 Detalles del Empleado</h1>
                        <p class="text-orange-100 text-sm mt-1">Información completa de {{ $employee->full_name }}</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="mailto:{{ $employee->email }}" 
                       class="flex items-center space-x-2 bg-white/20 hover:bg-white/30 backdrop-filter backdrop-blur-sm border border-white/30 font-medium rounded-full text-sm px-6 py-3 text-white transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-envelope"></i>
                        <span>Enviar Email</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Información principal -->
            <div class="xl:col-span-2 space-y-8">
                <!-- Información personal -->
                <div class="info-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-yellow-50 rounded-t-2xl">
                        <h3 class="text-lg font-semibold gradient-text flex items-center">
                            <i class="fas fa-user text-red-500 mr-3"></i>
                            Información Personal
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="info-label">ID de Empleado</label>
                                <div class="info-value bg-gray-100 px-3 py-2 rounded-lg font-mono">{{ $employee->employee_id }}</div>
                            </div>
                            <div>
                                <label class="info-label">Nombre Completo</label>
                                <div class="info-value">{{ $employee->full_name }}</div>
                            </div>
                            <div>
                                <label class="info-label">Email</label>
                                <div class="info-value">
                                    <a href="mailto:{{ $employee->email }}" class="text-red-600 hover:text-red-800 transition-colors duration-200 flex items-center">
                                        <i class="fas fa-envelope mr-2"></i>
                                        {{ $employee->email }}
                                    </a>
                                </div>
                            </div>
                            <div>
                                <label class="info-label">Teléfono</label>
                                <div class="info-value">
                                    @if($employee->phone)
                                        <a href="tel:{{ $employee->phone }}" class="text-orange-600 hover:text-orange-800 transition-colors duration-200 flex items-center">
                                            <i class="fas fa-phone mr-2"></i>
                                            {{ $employee->phone }}
                                        </a>
                                        @if($employee->extension)
                                            <span class="text-sm text-gray-500 ml-2">Ext. {{ $employee->extension }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">No especificado</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información laboral -->
                <div class="info-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-t-2xl">
                        <h3 class="text-lg font-semibold gradient-text flex items-center">
                            <i class="fas fa-briefcase text-orange-500 mr-3"></i>
                            Información Laboral
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="info-label">Posición</label>
                                <div class="info-value">{{ $employee->position }}</div>
                            </div>
                            <div>
                                <label class="info-label">Departamento</label>
                                <div class="info-value">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-building mr-1"></i>
                                        {{ $employee->department }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="info-label">Ubicación</label>
                                <div class="info-value">
                                    @if($employee->location)
                                        <span class="flex items-center">
                                            <i class="fas fa-map-marker-alt text-yellow-500 mr-2"></i>
                                            {{ $employee->location }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">No especificada</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="info-label">Supervisor</label>
                                <div class="info-value">
                                    @if($employee->manager_email)
                                        <a href="mailto:{{ $employee->manager_email }}" class="text-red-600 hover:text-red-800 transition-colors duration-200 flex items-center">
                                            <i class="fas fa-user-tie mr-2"></i>
                                            {{ $employee->manager_email }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">No asignado</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="info-label">Fecha de Contratación</label>
                                <div class="info-value">
                                    @if($employee->hire_date)
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar text-orange-500 mr-2"></i>
                                            {{ $employee->hire_date->format('d/m/Y') }}
                                        </span>
                                        <span class="text-sm text-gray-500 mt-1 block">
                                            ({{ $employee->hire_date->diffForHumans() }})
                                        </span>
                                    @else
                                        <span class="text-gray-400">No especificada</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="info-label">Estado</label>
                                <div class="info-value">
                                    @switch($employee->status)
                                        @case('active')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                Activo
                                            </span>
                                            @break
                                        @case('inactive')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                                Inactivo
                                            </span>
                                            @break
                                        @case('on_leave')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                                En Licencia
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                                {{ $employee->status }}
                                            </span>
                                    @endswitch
                                </div>
                            </div>
                        </div>

                        @if($employee->notes)
                            <div class="mt-6">
                                <label class="info-label">Notas</label>
                                <div class="bg-gradient-to-r from-red-50 to-yellow-50 p-4 rounded-lg border border-gray-200">
                                    <p class="text-gray-700 leading-relaxed">{{ $employee->notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Panel lateral con resumen -->
            <div class="space-y-6">
                <!-- Avatar y resumen rápido -->
                <div class="info-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100">
                    <div class="p-6">
                        <div class="text-center">
                            <div class="w-24 h-24 bg-gradient-to-r from-red-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-white font-bold text-2xl">
                                    {{ strtoupper(substr($employee->first_name, 0, 1)) }}{{ strtoupper(substr($employee->last_name, 0, 1)) }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold gradient-text">{{ $employee->full_name }}</h3>
                            <p class="text-gray-600 text-sm">{{ $employee->position }}</p>
                            <p class="text-gray-500 text-xs">{{ $employee->department }}</p>
                        </div>
                    </div>
                </div>

                <!-- Estado de acceso al sistema -->
                <div class="info-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-t-2xl">
                        <h3 class="text-lg font-semibold gradient-text flex items-center">
                            <i class="fas fa-key text-yellow-500 mr-3"></i>
                            Acceso al Sistema
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($employee->hasSystemAccess())
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-check text-white text-2xl"></i>
                                </div>
                                <p class="text-green-800 font-semibold">Acceso Habilitado</p>
                                <p class="text-green-600 text-sm">El empleado tiene acceso al sistema</p>
                            </div>
                        @else
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-r from-gray-400 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-times text-white text-2xl"></i>
                                </div>
                                <p class="text-gray-800 font-semibold">Sin Acceso</p>
                                <p class="text-gray-600 text-sm">El empleado no tiene acceso al sistema</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Acciones rápidas -->
                <div class="info-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-orange-50 to-red-50 rounded-t-2xl">
                        <h3 class="text-lg font-semibold gradient-text flex items-center">
                            <i class="fas fa-bolt text-orange-500 mr-3"></i>
                            Acciones Rápidas
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <a href="mailto:{{ $employee->email }}" 
                               class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-medium rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-envelope mr-2"></i>
                                Enviar Email
                            </a>
                            @if($employee->phone)
                                <a href="tel:{{ $employee->phone }}" 
                                   class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-medium rounded-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-200 transform hover:scale-105">
                                    <i class="fas fa-phone mr-2"></i>
                                    Llamar
                                </a>
                            @endif
                            <a href="{{ route('admin.employees.index') }}" 
                               class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white font-medium rounded-lg hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200 transform hover:scale-105">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Volver a Lista
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
                        <h5 class="text-success">Tiene Acceso</h5>
                        <p class="text-muted">Este empleado tiene acceso al sistema a través de Google Auth.</p>
                        @if($employee->user)
                            <div class="mt-3">
                                <small class="text-muted">Usuario vinculado:</small><br>
                                <strong>{{ $employee->user->name }}</strong><br>
                                <small class="text-muted">{{ $employee->user->email }}</small>
                            </div>
                        @endif
                    @else
                        <div class="mb-3">
                            <i class="fas fa-times-circle fa-3x text-muted"></i>
                        </div>
                        <h5 class="text-muted">Sin Acceso</h5>
                        <p class="text-muted">Este empleado aún no ha accedido al sistema.</p>
                    @endif
                </div>
            </div>

            <!-- Información de importación -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información de Registro</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Fecha de Importación</label>
                        <div>
                            @if($employee->data_imported_at)
                                {{ $employee->data_imported_at->format('d/m/Y H:i') }}
                                <small class="text-muted d-block">
                                    {{ $employee->data_imported_at->diffForHumans() }}
                                </small>
                            @else
                                <span class="text-muted">No especificada</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted">Fuente de Importación</label>
                        <div>
                            @switch($employee->import_source)
                                @case('manual_seed')
                                    <span class="badge badge-info">Datos de prueba</span>
                                    @break
                                @case('csv_upload')
                                    <span class="badge badge-success">Importación CSV</span>
                                    @break
                                @case('excel_upload')
                                    <span class="badge badge-success">Importación Excel</span>
                                    @break
                                @default
                                    <span class="badge badge-secondary">{{ $employee->import_source ?? 'Desconocida' }}</span>
                            @endswitch
                        </div>
                    </div>

                    @if($employee->last_sync_at)
                        <div class="mb-3">
                            <label class="form-label text-muted">Última Sincronización</label>
                            <div>
                                {{ $employee->last_sync_at->format('d/m/Y H:i') }}
                                <small class="text-muted d-block">
                                    {{ $employee->last_sync_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    @endif

                    <div class="mb-0">
                        <label class="form-label text-muted">Fecha de Creación</label>
                        <div>
                            {{ $employee->created_at->format('d/m/Y H:i') }}
                            <small class="text-muted d-block">
                                {{ $employee->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Acciones</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $employee->email }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-envelope me-1"></i>Enviar Email
                        </a>
                        
                        @if($employee->phone)
                            <a href="tel:{{ $employee->phone }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-phone me-1"></i>Llamar
                            </a>
                        @endif
                        
                        <a href="{{ route('admin.employees.export', ['search' => $employee->email]) }}" 
                           class="btn btn-outline-info btn-sm">
                            <i class="fas fa-download me-1"></i>Exportar Datos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection