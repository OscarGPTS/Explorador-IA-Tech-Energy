@extends('layouts.app')

@push('styles')
<style>
/* Animaciones y transiciones suaves */
.profile-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateY(0);
}

.profile-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.gradient-text {
    background: linear-gradient(135deg, #DC2626 0%, #FBBF24 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.glass-effect {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.activity-bar {
    background: linear-gradient(135deg, #DC2626 0%, #FBBF24 100%);
    transition: all 0.3s ease;
}

.activity-bar:hover {
    transform: scaleY(1.1);
}
</style>
@endpush

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
                        <h1 class="text-3xl font-bold">👤 Mi Perfil</h1>
                        <p class="text-orange-100 text-sm mt-1">Información y estadísticas de tu actividad en la plataforma</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header del Perfil -->
        <div class="profile-card bg-white/90 backdrop-blur-sm shadow-lg rounded-2xl mb-8 border border-white/20">
            <div class="px-6 py-8">
                <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <div class="flex-shrink-0">
                        <img class="h-24 w-24 rounded-full ring-4 ring-gradient shadow-lg" 
                             style="ring-color: rgba(220, 38, 38, 0.8);"
                             src="{{ $user->google_image ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=ffffff&background=dc2626' }}" 
                             alt="{{ $user->name }}">
                    </div>
                    <div class="text-center sm:text-left">
                        <h1 class="text-3xl font-bold gradient-text">{{ $user->name }}</h1>
                        <p class="text-lg text-gray-600">{{ $user->email }}</p>
                        <div class="flex flex-col sm:flex-row gap-4 mt-3 text-sm text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                Miembro desde {{ $stats['member_since']->format('M Y') }}
                            </span>
                            @if($stats['last_activity'])
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Última actividad {{ $stats['last_activity']->diffForHumans() }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Generales -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stat-card bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-lg border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total de Actividades</p>
                        <p class="text-2xl font-bold gradient-text">{{ number_format($stats['total_activities']) }}</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-red-100 to-red-200 rounded-xl">
                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12a1 1 0 102 0 1 1 0 00-2 0z"></path>
                            <path fill-rule="evenodd" d="M10 2C5.582 2 2 5.582 2 10s3.582 8 8 8 8-3.582 8-8-3.582-8-8-8zm0 14a6 6 0 110-12 6 6 0 010 12z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-lg border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Sesiones</p>
                        <p class="text-2xl font-bold gradient-text">{{ number_format($stats['total_sessions']) }}</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl">
                        <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-lg border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tasa de Éxito</p>
                        <p class="text-2xl font-bold gradient-text">{{ $performanceStats['success_rate'] }}%</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stat-card bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-lg border-l-4 border-red-400">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Errores</p>
                        <p class="text-2xl font-bold gradient-text">{{ number_format($performanceStats['total_errors']) }}</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-red-50 to-red-100 rounded-xl">
                        <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Uso por Módulos -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold gradient-text">📊 Uso por Módulos</h3>
                </div>
                <div class="space-y-4">
                    @forelse($moduleStats as $module)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-gradient-to-r from-red-500 to-yellow-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $module->type) }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">{{ number_format($module->count) }}</span>
                                <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="activity-bar h-2 rounded-full" style="width: {{ ($module->count / $moduleStats->max('count')) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">No hay datos de actividad disponibles</p>
                    @endforelse
                </div>
            </div>

            <!-- Navegadores Utilizados -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold gradient-text">🌐 Navegadores Utilizados</h3>
                </div>
                <div class="space-y-4">
                    @forelse($browserStats as $browser)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-gradient-to-r from-orange-500 to-yellow-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-900">{{ $browser['browser'] }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">{{ number_format($browser['count']) }}</span>
                                <div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="activity-bar h-2 rounded-full" style="width: {{ ($browser['count'] / $browserStats->max('count')) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">No hay datos de navegador disponibles</p>
                    @endforelse
                </div>
            </div>

            <!-- Actividad por Días -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold gradient-text">📅 Actividad Reciente (30 días)</h3>
                </div>
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @forelse($activityByDay as $day)
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium gradient-text">{{ $day->count }}</span>
                                <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="activity-bar h-2 rounded-full" style="width: {{ ($day->count / $activityByDay->max('count')) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">No hay actividad reciente</p>
                    @endforelse
                </div>
            </div>

            <!-- Horarios de Actividad -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-white/20">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold gradient-text">🕒 Horarios de Mayor Actividad</h3>
                </div>
                <div class="grid grid-cols-6 gap-2">
                    @for($hour = 0; $hour < 24; $hour++)
                        @php
                            $activity = $hourlyActivity->where('hour', $hour)->first();
                            $count = $activity ? $activity->count : 0;
                            $maxActivity = $hourlyActivity->max('count') ?: 1;
                            $intensity = ($count / $maxActivity) * 100;
                        @endphp
                        <div class="text-center">
                            <div class="mb-1 text-xs text-gray-500">{{ sprintf('%02d:00', $hour) }}</div>
                            <div class="h-12 w-full bg-gray-200 rounded relative overflow-hidden">
                                <div class="absolute bottom-0 w-full activity-bar transition-all duration-300" 
                                     style="height: {{ $intensity }}%"
                                     title="{{ $count }} actividades"></div>
                            </div>
                            <div class="mt-1 text-xs text-gray-600">{{ $count }}</div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Información de Rendimiento -->
        @if($performanceStats['avg_response_time'])
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 mt-8 border border-white/20">
            <h3 class="text-lg font-semibold gradient-text mb-4">⚡ Información de Rendimiento</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-gradient-to-br from-red-50 to-orange-50 rounded-xl">
                    <div class="text-2xl font-bold text-red-600">{{ round($performanceStats['avg_response_time'], 2) }}ms</div>
                    <div class="text-sm text-gray-600">Tiempo Promedio de Respuesta</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl">
                    <div class="text-2xl font-bold text-yellow-600">{{ $performanceStats['success_rate'] }}%</div>
                    <div class="text-sm text-gray-600">Tasa de Éxito</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-red-50 to-red-100 rounded-xl">
                    <div class="text-2xl font-bold text-red-500">{{ $performanceStats['total_errors'] }}</div>
                    <div class="text-sm text-gray-600">Total de Errores</div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection