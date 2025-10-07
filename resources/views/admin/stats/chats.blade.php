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

.hour-bar {
    background: linear-gradient(135deg, #DC2626 0%, #FBBF24 100%);
    transition: all 0.3s ease;
}

.hour-bar:hover {
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
                    <a href="{{ route('admin.stats.dashboard') }}" class="p-2 rounded-full bg-white/20 hover:bg-white/30 transition-all duration-300 transform hover:scale-110">
                        <svg width="24px" height="24px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                            <path fill="currentColor" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"/>
                            <path fill="currentColor" d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold">💬 Estadísticas de Chats</h1>
                        <p class="text-orange-100 text-sm mt-1">Análisis detallado de conversaciones y mensajes</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.stats.export', ['type' => 'chats', 'format' => 'csv']) }}" 
                       class="flex items-center space-x-2 bg-white/20 hover:bg-white/30 backdrop-filter backdrop-blur-sm border border-white/30 font-medium rounded-full text-sm px-6 py-3 text-white transition-all duration-300 transform hover:scale-105">
                        <span>📥 Exportar CSV</span>
                    </a>
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
                       class="nav-link border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-600">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('admin.stats.users') }}" 
                       class="nav-link border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-600">
                        👥 Usuarios
                    </a>
                    <a href="{{ route('admin.stats.chats') }}" 
                       class="nav-link-active border-b-2 py-4 px-1 text-sm font-medium">
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

        <!-- Estadísticas de chat -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-red-100 to-red-200 text-red-600 text-2xl">
                        💬
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 truncate">Total Mensajes</dt>
                            <dd class="text-lg font-bold gradient-text">{{ number_format($totalChats) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-orange-100 to-orange-200 text-orange-600 text-2xl">
                        🗂️
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 truncate">Conversaciones</dt>
                            <dd class="text-lg font-bold gradient-text">{{ number_format($totalGroups) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-yellow-100 to-yellow-200 text-yellow-600 text-2xl">
                        📊
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 truncate">Promedio/Conversación</dt>
                            <dd class="text-lg font-bold gradient-text">{{ $avgMessagesPerGroup }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actividad por hora del día -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg mb-8 border border-white/20">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold gradient-text">⏰ Actividad por Hora del Día</h3>
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
                            <div class="hour-bar rounded-t mb-2 flex items-end justify-center transition-all duration-300 hover:scale-y-110" 
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
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg mb-8 border border-white/20">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold gradient-text">📊 Distribución de Mensajes (Últimos 30 días)</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($messageTypeStats as $typeStats)
                    <div class="text-center p-6 bg-gradient-to-br from-red-50 to-orange-50 rounded-xl hover:from-red-100 hover:to-orange-100 transition-all duration-300">
                        <div class="text-3xl mb-2">
                            {{ $typeStats->sender === 'user' ? '👤' : '🤖' }}
                        </div>
                        <div class="text-2xl font-bold gradient-text">{{ number_format($typeStats->count) }}</div>
                        <div class="text-sm text-gray-600">
                            {{ $typeStats->sender === 'user' ? 'Mensajes de Usuario' : 'Respuestas de IA' }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Conversaciones más largas -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg mb-8 border border-white/20">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold gradient-text">🏆 Conversaciones Más Largas</h3>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gradient-to-r from-red-50 to-yellow-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">ID Conversación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Mensajes</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($longestConversations as $conversation)
                        <tr class="hover:bg-red-50 transition-colors duration-300">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-red-100 to-orange-100 flex items-center justify-center text-red-600 font-semibold">
                                        {{ strtoupper(substr($conversation->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $conversation->user->name ?? 'Usuario Desconocido' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $conversation->user->email ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                                {{ substr($conversation->chatgroup_id, 0, 8) }}...
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-red-100 to-orange-100 text-red-800">
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
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold gradient-text">🔥 Top Usuarios de la Semana</h3>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gradient-to-r from-red-50 to-yellow-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Mensajes Esta Semana</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($weeklyTopUsers as $index => $user)
                        <tr class="hover:bg-red-50 transition-colors duration-300">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-red-400 to-yellow-500 flex items-center justify-center">
                                        @if($index < 3)
                                            <span class="text-lg">{{ ['🥇', '🥈', '🥉'][$index] }}</span>
                                        @else
                                            <span class="text-white font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-orange-100 to-yellow-100 text-orange-800">
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
</div>
@endsection