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
                    <a href="{{ route('admin.stats.dashboard') }}" class="p-2 rounded-full bg-white/20 hover:bg-white/30 transition-all duration-300 transform hover:scale-110">
                        <svg width="24px" height="24px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                            <path fill="currentColor" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"/>
                            <path fill="currentColor" d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold">👥 Estadísticas de Usuarios</h1>
                        <p class="text-orange-100 text-sm mt-1">Análisis detallado de actividad y comportamiento de usuarios</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.stats.export', ['type' => 'users', 'format' => 'csv']) }}" 
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
                       class="nav-link-active border-b-2 py-4 px-1 text-sm font-medium">
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

        <!-- Estadísticas de usuarios -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-red-100 to-red-200 text-red-600 text-2xl">
                        👥
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
                    <div class="p-3 rounded-xl bg-gradient-to-br from-orange-100 to-orange-200 text-orange-600 text-2xl">
                        ✅
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 truncate">Verificados</dt>
                            <dd class="text-lg font-bold gradient-text">{{ number_format($verifiedUsers) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-yellow-100 to-yellow-200 text-yellow-600 text-2xl">
                        🔴
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 truncate">Google OAuth</dt>
                            <dd class="text-lg font-bold gradient-text">{{ number_format($googleUsers) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="stats-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-red-400">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-red-50 to-red-100 text-red-500 text-2xl">
                        📧
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-600 truncate">Registro Normal</dt>
                            <dd class="text-lg font-bold gradient-text">{{ number_format($regularUsers) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usuarios más activos detallados -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg mb-8 border border-white/20">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold gradient-text">🏆 Usuarios Más Activos (Detallado)</h3>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gradient-to-r from-red-50 to-yellow-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Usuario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Mensajes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Días Activos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Último Mensaje</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Registro</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($activeUsersDetailed as $user)
                        <tr class="hover:bg-red-50 transition-colors duration-300">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-red-100 to-orange-100 flex items-center justify-center text-red-600 font-semibold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
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
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-red-100 to-orange-100 text-red-800">
                                    {{ number_format($user->total_messages) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gradient-to-r from-orange-100 to-yellow-100 text-orange-800">
                                    {{ number_format($user->active_days) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @if($user->last_message_at)
                                    {{ \Carbon\Carbon::parse($user->last_message_at)->diffForHumans() }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Actividad por día de la semana -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg mb-8 border border-white/20">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold gradient-text">📅 Actividad por Día de la Semana</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($activityByDayOfWeek as $dayActivity)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-700 w-20">{{ $dayActivity->day_name }}</span>
                        <div class="flex items-center flex-1 ml-4">
                            <div class="w-full bg-gray-200 rounded-full h-4 mr-3 overflow-hidden">
                                @php
                                    $maxActivity = $activityByDayOfWeek->max('count');
                                    $percentage = $maxActivity > 0 ? ($dayActivity->count / $maxActivity) * 100 : 0;
                                @endphp
                                <div class="activity-bar h-4 rounded-full transition-all duration-300 hover:scale-y-110" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600 w-16 text-right">{{ number_format($dayActivity->count) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Registros por mes -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold gradient-text">📈 Registros por Mes (Últimos 12 meses)</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($usersByMonth as $monthData)
                    <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-xl p-4 text-center hover:from-red-100 hover:to-orange-100 transition-all duration-300">
                        <div class="text-lg font-bold gradient-text">
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
</div>
@endsection