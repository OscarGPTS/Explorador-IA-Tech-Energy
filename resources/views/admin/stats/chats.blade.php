@extends('layouts.app')

@section('content')
<div class="px-4 pt-10">
    <div class="flex justify-between items-center ml-4 mb-6">
        <p class="font-bold ml-2 text-2xl flex"> 
            <a href="{{ route('admin.stats.dashboard') }}" class="mr-2 flex items-center justify-center">
                <svg width="22px" height="22px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#000000" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"/>
                    <path fill="#000000" d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"/>
                </svg>
            </a>
            💬 Estadísticas de Chats
        </p>
        <div class="mr-4">
            <a href="{{ route('admin.stats.export', ['type' => 'chats', 'format' => 'csv']) }}" 
               class="bg-green-500 hover:bg-green-700 text-white font-medium rounded-lg text-sm px-5 py-2.5">
                📥 Exportar CSV
            </a>
        </div>
    </div>

    <!-- Navegación de estadísticas -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6">
                <a href="{{ route('admin.stats.dashboard') }}" 
                   class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.stats.users') }}" 
                   class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    👥 Usuarios
                </a>
                <a href="{{ route('admin.stats.chats') }}" 
                   class="border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600">
                    💬 Chats
                </a>
                <a href="{{ route('admin.stats.modules') }}" 
                   class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    📊 Módulos
                </a>
            </nav>
        </div>
    </div>

    <!-- Estadísticas de chat -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                    💬
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
                <div class="p-3 rounded-full bg-green-500 bg-opacity-75">
                    🗂️
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Conversaciones</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($totalGroups) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-500 bg-opacity-75">
                    📊
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Promedio/Conversación</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $avgMessagesPerGroup }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Actividad por hora del día -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">⏰ Actividad por Hora del Día</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-12 gap-2">
                @for($hour = 0; $hour < 24; $hour++)
                    @php
                        $count = $hourlyActivity[$hour] ?? 0;
                        $maxCount = max($hourlyActivity);
                        $height = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                    @endphp
                    <div class="text-center">
                        <div class="bg-blue-500 rounded-t mb-2 flex items-end justify-center" 
                             style="height: {{ max($height, 2) }}px; min-height: 20px;">
                            @if($count > 0)
                                <span class="text-xs text-white font-semibold mb-1">{{ $count }}</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-600">{{ sprintf('%02d:00', $hour) }}</div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Mensajes por tipo (Usuario vs IA) -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">📊 Distribución de Mensajes (Últimos 30 días)</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($messageTypeStats as $typeStats)
                <div class="text-center">
                    <div class="text-3xl mb-2">
                        {{ $typeStats->sender === 'user' ? '👤' : '🤖' }}
                    </div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($typeStats->count) }}</div>
                    <div class="text-sm text-gray-600">
                        {{ $typeStats->sender === 'user' ? 'Mensajes de Usuario' : 'Respuestas de IA' }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Conversaciones más largas -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">🏆 Conversaciones Más Largas</h3>
        </div>
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Conversación</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mensajes</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($longestConversations as $conversation)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    {{ strtoupper(substr($conversation->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $conversation->user->name ?? 'Usuario Desconocido' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $conversation->user->email ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                            {{ substr($conversation->chatgroup_id, 0, 8) }}...
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ number_format($conversation->message_count) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top usuarios de la semana -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">🔥 Top Usuarios de la Semana</h3>
        </div>
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mensajes Esta Semana</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($weeklyTopUsers as $index => $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
                                    @if($index < 3)
                                        {{ ['🥇', '🥈', '🥉'][$index] }}
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ number_format($user->weekly_messages) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection