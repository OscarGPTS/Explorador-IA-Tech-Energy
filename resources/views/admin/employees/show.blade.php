@extends('layouts.app')

@section('title', 'Detalles del Empleado')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user me-2"></i>Detalles del Empleado
        </h1>
        <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Volver a Lista
        </a>
    </div>

    <div class="row">
        <!-- Información personal -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información Personal</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">ID de Empleado</label>
                            <div class="fw-bold">{{ $employee->employee_id }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Nombre Completo</label>
                            <div class="fw-bold">{{ $employee->full_name }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Email</label>
                            <div>
                                <a href="mailto:{{ $employee->email }}" class="text-decoration-none">
                                    {{ $employee->email }}
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Teléfono</label>
                            <div>
                                @if($employee->phone)
                                    <a href="tel:{{ $employee->phone }}" class="text-decoration-none">
                                        {{ $employee->phone }}
                                    </a>
                                    @if($employee->extension)
                                        <small class="text-muted">Ext. {{ $employee->extension }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">No especificado</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información laboral -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información Laboral</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Posición</label>
                            <div class="fw-bold">{{ $employee->position }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Departamento</label>
                            <div>
                                <span class="badge badge-info fs-6">{{ $employee->department }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Ubicación</label>
                            <div>{{ $employee->location ?? 'No especificada' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Supervisor</label>
                            <div>
                                @if($employee->manager_email)
                                    <a href="mailto:{{ $employee->manager_email }}" class="text-decoration-none">
                                        {{ $employee->manager_email }}
                                    </a>
                                @else
                                    <span class="text-muted">No asignado</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Fecha de Contratación</label>
                            <div>
                                @if($employee->hire_date)
                                    {{ $employee->hire_date->format('d/m/Y') }}
                                    <small class="text-muted">
                                        ({{ $employee->hire_date->diffForHumans() }})
                                    </small>
                                @else
                                    <span class="text-muted">No especificada</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Estado</label>
                            <div>
                                @switch($employee->status)
                                    @case('active')
                                        <span class="badge badge-success fs-6">Activo</span>
                                        @break
                                    @case('inactive')
                                        <span class="badge badge-danger fs-6">Inactivo</span>
                                        @break
                                    @case('on_leave')
                                        <span class="badge badge-warning fs-6">En Licencia</span>
                                        @break
                                    @default
                                        <span class="badge badge-secondary fs-6">{{ $employee->status }}</span>
                                @endswitch
                            </div>
                        </div>
                    </div>

                    @if($employee->notes)
                        <div class="mt-3">
                            <label class="form-label text-muted">Notas</label>
                            <div class="bg-light p-3 rounded">
                                {{ $employee->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Panel lateral -->
        <div class="col-lg-4">
            <!-- Estado del sistema -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Acceso al Sistema</h6>
                </div>
                <div class="card-body text-center">
                    @if($employee->hasSystemAccess())
                        <div class="mb-3">
                            <i class="fas fa-check-circle fa-3x text-success"></i>
                        </div>
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