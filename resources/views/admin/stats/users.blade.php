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
            👥 Estadísticas de Usuarios
        </p>
        <div class="mr-4">
            <a href="{{ route('admin.stats.export', ['type' => 'users', 'format' => 'csv']) }}" 
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
                   class="border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600">
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

    <!-- Estadísticas de usuarios -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                    👥
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
                    ✅
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Verificados</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($verifiedUsers) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-500 bg-opacity-75">
                    🔴
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Google OAuth</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($googleUsers) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-500 bg-opacity-75">
                    📧
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Registro Normal</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($regularUsers) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Usuarios más activos detallados -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">🏆 Usuarios Más Activos (Detallado)</h3>
        </div>
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mensajes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Días Activos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Último Mensaje</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registro</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($activeUsersDetailed as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
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
                                {{ number_format($user->total_messages) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ number_format($user->active_days) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($user->last_message_at)
                                {{ \Carbon\Carbon::parse($user->last_message_at)->diffForHumans() }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actividad por día de la semana -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">📅 Actividad por Día de la Semana</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($activityByDayOfWeek as $dayActivity)
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 w-20">{{ $dayActivity->day_name }}</span>
                    <div class="flex items-center flex-1 ml-4">
                        <div class="w-full bg-gray-200 rounded-full h-4 mr-3">
                            @php
                                $maxActivity = $activityByDayOfWeek->max('count');
                                $percentage = $maxActivity > 0 ? ($dayActivity->count / $maxActivity) * 100 : 0;
                            @endphp
                            <div class="bg-blue-600 h-4 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm text-gray-600 w-16 text-right">{{ number_format($dayActivity->count) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Registros por mes -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">📈 Registros por Mes (Últimos 12 meses)</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($usersByMonth as $monthData)
                <div class="bg-gray-50 rounded-lg p-4 text-center">
                    <div class="text-lg font-semibold text-gray-900">
                        {{ number_format($monthData->count) }}
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ \Carbon\Carbon::create($monthData->year, $monthData->month)->format('M Y') }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection