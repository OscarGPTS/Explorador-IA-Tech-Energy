@extends('layouts.app')

@section('title', 'Dashboard - Soporte Técnico')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-chart-line mr-3 text-blue-600"></i>
                        Dashboard de Soporte Técnico
                    </h1>
                    <p class="text-gray-600 mt-2 text-lg">
                        Análisis y estadísticas de conversaciones de soporte
                    </p>
                </div>
                <a href="{{ route('tech-support.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al Soporte
                </a>
            </div>
        </div>

        <!-- Métricas principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Conversaciones</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_conversations'] ?? 0 }}</p>
                        <p class="text-blue-600 text-sm mt-1">
                            <i class="fas fa-arrow-up"></i> +12% este mes
                        </p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-comments text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Tasa de Resolución</p>
                        <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['effectiveness_rate'] ?? 75, 1) }}%</p>
                        <p class="text-green-600 text-sm mt-1">
                            <i class="fas fa-arrow-up"></i> +5% vs mes anterior
                        </p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Escalaciones</p>
                        <p class="text-3xl font-bold text-gray-800">{{ number_format($stats['escalation_rate'] ?? 25, 1) }}%</p>
                        <p class="text-yellow-600 text-sm mt-1">
                            <i class="fas fa-arrow-down"></i> -3% vs mes anterior
                        </p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-level-up-alt text-yellow-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Tiempo Promedio</p>
                        <p class="text-3xl font-bold text-gray-800">1.8<span class="text-lg">min</span></p>
                        <p class="text-purple-600 text-sm mt-1">
                            <i class="fas fa-arrow-down"></i> -0.3min vs anterior
                        </p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i class="fas fa-clock text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos y análisis -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Gráfico de conversaciones por día -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-line mr-2 text-blue-600"></i>
                    Conversaciones por Día (Última Semana)
                </h3>
                <div class="h-64">
                    <canvas id="dailyConversationsChart"></canvas>
                </div>
            </div>

            <!-- Distribución por categorías -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-chart-pie mr-2 text-green-600"></i>
                    Problemas por Categoría
                </h3>
                <div class="h-64">
                    <canvas id="categoryDistributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribución horaria y problemas populares -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Distribución horaria -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-clock mr-2 text-purple-600"></i>
                    Distribución Horaria
                </h3>
                <div class="h-64">
                    <canvas id="hourlyDistributionChart"></canvas>
                </div>
            </div>

            <!-- Problemas más populares -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-fire mr-2 text-red-600"></i>
                    Problemas Más Comunes
                </h3>
                <div class="space-y-3">
                    @if(isset($stats['popular_problems']) && count($stats['popular_problems']) > 0)
                        @foreach($stats['popular_problems'] as $index => $problem)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <span class="w-8 h-8 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-sm font-bold mr-3">
                                        {{ $index + 1 }}
                                    </span>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $problem->problem_type ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-600">{{ ucfirst($problem->problem_category ?? 'other') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-800">{{ $problem->count ?? 0 }}</p>
                                    <p class="text-xs text-gray-600">casos</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-chart-bar text-gray-400 text-4xl mb-3"></i>
                            <p class="text-gray-600">No hay datos suficientes aún</p>
                            <p class="text-sm text-gray-500">Los datos aparecerán cuando haya más conversaciones</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Estadísticas detalladas por categoría -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-table mr-2 text-indigo-600"></i>
                Estadísticas Detalladas por Categoría
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resueltos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Escalados</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tasa Éxito</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tendencia</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($stats['category_stats']) && count($stats['category_stats']) > 0)
                            @foreach($stats['category_stats'] as $category)
                                @php
                                    $total = $category->total ?? 0;
                                    $resolved = $category->resolved ?? 0;
                                    $escalated = $category->escalated ?? 0;
                                    $successRate = $total > 0 ? ($resolved / $total) * 100 : 0;
                                    $escalationRate = $total > 0 ? ($escalated / $total) * 100 : 0;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-{{ $category->problem_category === 'computer' ? 'laptop' : ($category->problem_category === 'internet' ? 'wifi' : ($category->problem_category === 'email' ? 'envelope' : ($category->problem_category === 'printer' ? 'print' : ($category->problem_category === 'software' ? 'cogs' : ($category->problem_category === 'access' ? 'key' : 'question'))))) }} text-blue-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 capitalize">
                                                    {{ ucfirst($category->problem_category ?? 'Unknown') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $total }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $resolved }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $escalated }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $successRate }}%"></div>
                                            </div>
                                            <span class="text-sm text-gray-900">{{ number_format($successRate, 1) }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="text-green-600">
                                            <i class="fas fa-arrow-up"></i> +5%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-600">
                                    No hay datos disponibles para mostrar estadísticas detalladas
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-tools mr-2 text-yellow-600"></i>
                Acciones Rápidas
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button class="p-4 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg text-left transition duration-200">
                    <i class="fas fa-download text-blue-600 text-xl mb-2"></i>
                    <div class="font-semibold text-gray-800">Exportar Datos</div>
                    <div class="text-sm text-gray-600">Descargar reporte completo</div>
                </button>
                
                <button class="p-4 bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg text-left transition duration-200">
                    <i class="fas fa-chart-bar text-green-600 text-xl mb-2"></i>
                    <div class="font-semibold text-gray-800">Generar Reporte</div>
                    <div class="text-sm text-gray-600">Crear reporte personalizado</div>
                </button>
                
                <button class="p-4 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg text-left transition duration-200">
                    <i class="fas fa-cog text-purple-600 text-xl mb-2"></i>
                    <div class="font-semibold text-gray-800">Configurar</div>
                    <div class="text-sm text-gray-600">Ajustar parámetros del sistema</div>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos para los gráficos
    const dailyData = @json($stats['daily_stats'] ?? []);
    const categoryData = @json($stats['category_stats'] ?? []);
    const hourlyData = @json($stats['hourly_distribution'] ?? []);
    
    // Gráfico de conversaciones diarias
    const dailyLabels = dailyData.length > 0 ? dailyData.map(item => new Date(item.date).toLocaleDateString()) : ['Sin datos'];
    const dailyCounts = dailyData.length > 0 ? dailyData.map(item => item.count) : [0];
    
    new Chart(document.getElementById('dailyConversationsChart'), {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Conversaciones',
                data: dailyCounts,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Gráfico de distribución por categorías
    const categoryLabels = categoryData.length > 0 ? categoryData.map(item => item.problem_category || 'Sin categoría') : ['Sin datos'];
    const categoryCounts = categoryData.length > 0 ? categoryData.map(item => item.total || 0) : [1];
    
    new Chart(document.getElementById('categoryDistributionChart'), {
        type: 'doughnut',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryCounts,
                backgroundColor: [
                    '#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#EF4444', '#6B7280'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Gráfico de distribución horaria
    const hourlyLabels = Array.from({length: 24}, (_, i) => `${i}:00`);
    const hourlyCounts = Array.from({length: 24}, (_, i) => {
        const hourData = hourlyData.find(item => item.hour === i);
        return hourData ? hourData.count : 0;
    });
    
    new Chart(document.getElementById('hourlyDistributionChart'), {
        type: 'bar',
        data: {
            labels: hourlyLabels,
            datasets: [{
                label: 'Conversaciones',
                data: hourlyCounts,
                backgroundColor: 'rgba(139, 92, 246, 0.8)',
                borderColor: 'rgb(139, 92, 246)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
@endsection