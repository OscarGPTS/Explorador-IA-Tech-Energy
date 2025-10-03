@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header del Perfil -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-8">
            <div class="px-6 py-8">
                <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <div class="flex-shrink-0">
                        <img class="h-24 w-24 rounded-full ring-4 ring-blue-500 ring-offset-2 ring-offset-white dark:ring-offset-gray-800" 
                             src="{{ $user->google_image ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7c3aed&background=ede9fe' }}" 
                             alt="{{ $user->name }}">
                    </div>
                    <div class="text-center sm:text-left">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                        <p class="text-lg text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                        <div class="flex flex-col sm:flex-row gap-4 mt-3 text-sm text-gray-600 dark:text-gray-300">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                Miembro desde {{ $stats['member_since']->format('M Y') }}
                            </span>
                            @if($stats['last_activity'])
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
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
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Actividades</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_activities']) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12a1 1 0 102 0 1 1 0 00-2 0z"></path>
                            <path fill-rule="evenodd" d="M10 2C5.582 2 2 5.582 2 10s3.582 8 8 8 8-3.582 8-8-3.582-8-8-8zm0 14a6 6 0 110-12 6 6 0 010 12z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sesiones</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_sessions']) }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tasa de Éxito</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $performanceStats['success_rate'] }}%</p>
                    </div>
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Errores</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($performanceStats['total_errors']) }}</p>
                    </div>
                    <div class="p-3 bg-red-100 dark:bg-red-900 rounded-full">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Uso por Módulos -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Uso por Módulos</h3>
                </div>
                <div class="space-y-4">
                    @forelse($moduleStats as $module)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white capitalize">{{ str_replace('_', ' ', $module->type) }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($module->count) }}</span>
                                <div class="w-20 h-2 bg-gray-200 dark:bg-gray-700 rounded-full">
                                    <div class="h-2 bg-blue-500 rounded-full" style="width: {{ ($module->count / $moduleStats->max('count')) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">No hay datos de actividad disponibles</p>
                    @endforelse
                </div>
            </div>

            <!-- Navegadores Utilizados -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Navegadores Utilizados</h3>
                </div>
                <div class="space-y-4">
                    @forelse($browserStats as $browser)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $browser['browser'] }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($browser['count']) }}</span>
                                <div class="w-20 h-2 bg-gray-200 dark:bg-gray-700 rounded-full">
                                    <div class="h-2 bg-green-500 rounded-full" style="width: {{ ($browser['count'] / $browserStats->max('count')) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">No hay datos de navegador disponibles</p>
                    @endforelse
                </div>
            </div>

            <!-- Actividad por Días -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Actividad Reciente (30 días)</h3>
                </div>
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @forelse($activityByDay as $day)
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $day->count }}</span>
                                <div class="w-16 h-2 bg-gray-200 dark:bg-gray-700 rounded-full">
                                    <div class="h-2 bg-purple-500 rounded-full" style="width: {{ ($day->count / $activityByDay->max('count')) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">No hay actividad reciente</p>
                    @endforelse
                </div>
            </div>

            <!-- Horarios de Actividad -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Horarios de Mayor Actividad</h3>
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
                            <div class="mb-1 text-xs text-gray-500 dark:text-gray-400">{{ sprintf('%02d:00', $hour) }}</div>
                            <div class="h-12 w-full bg-gray-200 dark:bg-gray-700 rounded relative overflow-hidden">
                                <div class="absolute bottom-0 w-full bg-blue-500 transition-all duration-300" 
                                     style="height: {{ $intensity }}%"
                                     title="{{ $count }} actividades"></div>
                            </div>
                            <div class="mt-1 text-xs text-gray-600 dark:text-gray-300">{{ $count }}</div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Información de Rendimiento -->
        @if($performanceStats['avg_response_time'])
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mt-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Información de Rendimiento</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ round($performanceStats['avg_response_time'], 2) }}ms</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Tiempo Promedio de Respuesta</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $performanceStats['success_rate'] }}%</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Tasa de Éxito</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $performanceStats['total_errors'] }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total de Errores</div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection