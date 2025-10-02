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
            🤖 Estadísticas de Agentes IA
        </p>
        <div class="mr-4">
            <a href="{{ route('admin.stats.export', ['type' => 'agents', 'format' => 'csv']) }}" 
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
                   class="border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    💬 Chats
                </a>
                <a href="{{ route('admin.stats.agents') }}" 
                   class="border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600">
                    🤖 Agentes IA
                </a>
            </nav>
        </div>
    </div>

    <!-- Estadísticas generales de agentes -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                    🤖
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Agentes</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($totalAgentRoles) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500 bg-opacity-75">
                    ✅
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Agentes Activos</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($activeAgentRoles) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-500 bg-opacity-75">
                    ⚙️
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Configuraciones</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($totalUserSettings) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-500 bg-opacity-75">
                    💬
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Chats Configurados</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($activeChatConfigs) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Agentes más populares -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">🏆 Agentes Más Populares</h3>
        </div>
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Config. Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Config. Chat</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($popularAgents as $agent)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    🤖
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $agent->display_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $agent->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <div class="max-w-xs truncate">
                                {{ Str::limit($agent->description, 100) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $agent->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $agent->is_active ? '✅ Activo' : '❌ Inactivo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ number_format($agent->user_settings_count) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ number_format($agent->chat_configs_count) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Usuarios con más configuraciones -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">👑 Usuarios Más Configurados</h3>
            <p class="text-sm text-gray-600">Usuarios que han creado más configuraciones personalizadas</p>
        </div>
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Config. Agentes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Config. Chat</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($userConfigStats as $index => $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-600 flex items-center justify-center">
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ number_format($user->settings_count) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ number_format($user->chat_configs_count) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Uso de configuraciones en el tiempo -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">📈 Creación de Configuraciones (Últimos 30 días)</h3>
        </div>
        <div class="p-6">
            @if($configUsageOverTime->count() > 0)
                <div class="space-y-4">
                    @foreach($configUsageOverTime as $usage)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700">
                            {{ \Carbon\Carbon::parse($usage->date)->format('d/m/Y') }}
                        </span>
                        <div class="flex items-center">
                            <div class="w-48 bg-gray-200 rounded-full h-2 mr-3">
                                @php
                                    $maxUsage = $configUsageOverTime->max('count');
                                    $percentage = $maxUsage > 0 ? ($usage->count / $maxUsage) * 100 : 0;
                                @endphp
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600 w-8">{{ $usage->count }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 text-4xl mb-4">📊</div>
                    <p class="text-gray-600">No hay datos de configuraciones en los últimos 30 días</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection