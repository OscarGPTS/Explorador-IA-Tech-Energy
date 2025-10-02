@extends('layouts.app')

@section('content')
<div class="px-4 pt-10">
    <div class="flex justify-between items-center ml-4 mb-6">
        <p class="font-bold ml-2 text-2xl flex"> 
            <a href="/" class="mr-2 flex items-center justify-center">
                <svg width="22px" height="22px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#000000" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"/>
                    <path fill="#000000" d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"/>
                </svg>
            </a>
            📊 Panel de Estadísticas - Dashboard
        </p>
    </div>

    <!-- Navegación de estadísticas -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6">
                <a href="{{ route('admin.stats.dashboard') }}" 
                   class="border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.stats.users') }}" 
                   class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    👥 Usuarios
                </a>
                <a href="{{ route('admin.stats.chats') }}" 
                   class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    💬 Chats
                </a>
                <a href="{{ route('admin.stats.agents') }}" 
                   class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    🤖 Agentes IA
                </a>
            </nav>
        </div>
    </div>

    <!-- Estadísticas principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Usuarios</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($totalUsers) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500 bg-opacity-75">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Activos Hoy</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($activeUsersToday) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-500 bg-opacity-75">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Mensajes</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($totalChats) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-500 bg-opacity-75">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Mensajes Hoy</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($chatsToday) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen del último mes -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">📈 Resumen del Último Mes</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ number_format($lastMonthStats['new_users']) }}</div>
                <div class="text-sm text-gray-600">Nuevos Usuarios</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ number_format($lastMonthStats['total_messages']) }}</div>
                <div class="text-sm text-gray-600">Total Mensajes</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ number_format($lastMonthStats['active_configurations']) }}</div>
                <div class="text-sm text-gray-600">Configuraciones Activas</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">{{ number_format($lastMonthStats['avg_messages_per_user'] ?? 0, 1) }}</div>
                <div class="text-sm text-gray-600">Promedio Mensajes/Usuario</div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Distribución de mensajes -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">📊 Distribución de Mensajes</h3>
            <div class="space-y-4">
                @foreach($messageDistribution as $type)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">
                            {{ $type->sender === 'user' ? '👤 Usuario' : '🤖 IA' }}
                        </span>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                <div class="bg-{{ $type->sender === 'user' ? 'blue' : 'green' }}-600 h-2 rounded-full" 
                                     style="width: {{ $totalChats > 0 ? ($type->count / $totalChats) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600">{{ number_format($type->count) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Actividad por hora -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">⏰ Actividad por Hora</h3>
            <div class="grid grid-cols-12 gap-1">
                @foreach($activityByHour as $hourData)
                    <div class="text-center">
                        <div class="bg-blue-100 rounded mb-1" style="height: {{ $hourData->count > 0 ? min(($hourData->count / $activityByHour->max('count')) * 60, 60) : 2 }}px"></div>
                        <div class="text-xs text-gray-600">{{ $hourData->hour }}h</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Usuarios más activos -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">🏆 Usuarios Más Activos</h3>
        </div>
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mensajes</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($mostActiveUsers->take(10) as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
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
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">🤖 Configuraciones de Agentes Más Populares</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($popularAgentConfigs as $agent)
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-gray-900">{{ $agent->name }}</div>
                        <div class="text-xs text-gray-500">{{ strlen($agent->description) > 50 ? substr($agent->description, 0, 50) . '...' : $agent->description }}</div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ number_format($agent->usage_count) }} usos
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection