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

.activity-bar {
    background: linear-gradient(135deg, #DC2626 0%, #FBBF24 100%);
    transition: all 0.3s ease;
}

.activity-bar:hover {
    transform: scaleY(1.1);
}

.nav-link-active {
    border-color: #DC2626;
    color: #DC2626;
}

.nav-link {
    transition: all 0.3s ease;
}

.nav-link:hover {
    color: #DC2626;
    border-color: #FBBF24;
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
                        <h1 class="text-3xl font-bold">📊 Panel de Estadísticas</h1>
                        <p class="text-orange-100 text-sm mt-1">Dashboard y análisis de uso de la plataforma</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Navegación de estadísticas -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg mb-8 border border-white/20">
            <div class="border-b border-gray-100">
                <nav class="-mb-px flex space-x-8 px-6">
                    <a href="{{ route('admin.stats.dashboard') }}" 
                       class="nav-link-active border-b-2 py-4 px-1 text-sm font-medium">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('admin.stats.users') }}" 
                       class="nav-link border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-600">
                        👥 Usuarios
                    </a>
                    <a href="{{ route('admin.stats.chats') }}" 
                       class="nav-link border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-600">
                        💬 Chats
                    </a>
                    <a href="{{ route('admin.stats.modules') }}" 
                       class="nav-link border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-600">
                        📊 Módulos
                    </a>
                    <a href="{{ route('admin.stats.errors') }}" 
                       class="nav-link border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-600">
                        🚨 Errores
                    </a>
                </nav>
            </div>
        </div>

        <!-- Estadísticas principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-red-100 to-red-200">
                        <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 truncate">Total Usuarios</dt>
                            <dd class="text-lg font-bold gradient-text">{{ number_format($totalUsers) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-orange-100 to-orange-200">
                        <svg class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 truncate">Activos Hoy</dt>
                            <dd class="text-lg font-bold gradient-text">{{ number_format($activeUsersToday) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-yellow-100 to-yellow-200">
                        <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 truncate">Total Mensajes</dt>
                            <dd class="text-lg font-bold gradient-text">{{ number_format($totalChats) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-red-400">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-red-50 to-red-100">
                        <svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 truncate">Mensajes Hoy</dt>
                            <dd class="text-lg font-bold gradient-text">{{ number_format($chatsToday) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>        <!-- Resumen del último mes -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 mb-8 border border-white/20">
            <h3 class="text-lg font-semibold gradient-text mb-4">📈 Resumen del Último Mes</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gradient-to-br from-red-50 to-orange-50 rounded-xl">
                    <div class="text-2xl font-bold text-red-600">{{ number_format($lastMonthStats['new_users']) }}</div>
                    <div class="text-sm text-gray-600">Nuevos Usuarios</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-orange-50 to-yellow-50 rounded-xl">
                    <div class="text-2xl font-bold text-orange-600">{{ number_format($lastMonthStats['total_messages']) }}</div>
                    <div class="text-sm text-gray-600">Total Mensajes</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl">
                    <div class="text-2xl font-bold text-yellow-600">{{ number_format($lastMonthStats['active_configurations']) }}</div>
                    <div class="text-sm text-gray-600">Configuraciones Activas</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-red-50 to-red-100 rounded-xl">
                    <div class="text-2xl font-bold text-red-500">{{ number_format($lastMonthStats['avg_messages_per_user'] ?? 0, 1) }}</div>
                    <div class="text-sm text-gray-600">Promedio Mensajes/Usuario</div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Distribución de mensajes -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-white/20">
                <h3 class="text-lg font-semibold gradient-text mb-4">📊 Distribución de Mensajes</h3>
                <div class="space-y-4">
                    @foreach($messageDistribution as $type)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">
                                {{ $type->sender === 'user' ? '👤 Usuario' : '🤖 IA' }}
                            </span>
                            <div class="flex items-center">
                                <div class="w-32 bg-gray-200 rounded-full h-2 mr-3 overflow-hidden">
                                    <div class="activity-bar h-2 rounded-full" 
                                         style="width: {{ $totalChats > 0 ? ($type->count / $totalChats) * 100 : 0 }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600">{{ number_format($type->count) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Actividad por hora -->
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-white/20">
                <h3 class="text-lg font-semibold gradient-text mb-4">⏰ Actividad por Hora</h3>
                <div class="grid grid-cols-12 gap-1">
                    @foreach($activityByHour as $hourData)
                        <div class="text-center">
                            <div class="activity-bar rounded mb-1" style="height: {{ $hourData->count > 0 ? min(($hourData->count / $activityByHour->max('count')) * 60, 60) : 2 }}px"></div>
                            <div class="text-xs text-gray-600">{{ $hourData->hour }}h</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Usuarios más activos -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg mb-8 border border-white/20">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold gradient-text">🏆 Usuarios Más Activos</h3>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gradient-to-r from-red-50 to-yellow-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Mensajes</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($mostActiveUsers->take(10) as $user)
                        <tr class="hover:bg-red-50 transition-colors duration-300">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-red-100 to-orange-100 text-red-800">
                                    {{ number_format($user->chat_count) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Agentes más populares -->
        @if($popularAgentConfigs->count() > 0)
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg mb-8 border border-white/20">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold gradient-text">🤖 Configuraciones de Agentes Más Populares</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($popularAgentConfigs as $agent)
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-orange-50 rounded-xl hover:from-red-100 hover:to-orange-100 transition-all duration-300">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $agent->name }}</div>
                            <div class="text-xs text-gray-600">{{ strlen($agent->description) > 50 ? substr($agent->description, 0, 50) . '...' : $agent->description }}</div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-red-500 to-orange-500 text-white">
                            {{ number_format($agent->usage_count) }} usos
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Estadísticas de Módulos -->
        @if($moduleUsage->count() > 0)
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg mb-8 border border-white/20">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold gradient-text">📊 Uso de Módulos/Apps</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($moduleUsage->take(6) as $module)
                    <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-xl p-4 hover:from-red-100 hover:to-orange-100 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ ucfirst($module->type) }}</h4>
                                <p class="text-xs text-gray-600">{{ number_format($module->unique_users) }} usuarios únicos</p>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold gradient-text">{{ number_format($module->usage_count) }}</span>
                                <p class="text-xs text-gray-600">usos totales</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-6 text-center">
                    <a href="{{ route('admin.stats.modules') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-full text-white bg-gradient-to-r from-red-500 to-yellow-500 hover:from-red-600 hover:to-yellow-600 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        Ver análisis completo de módulos
                        <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection