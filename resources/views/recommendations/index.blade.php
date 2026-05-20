@extends('layouts.app')

@push('styles')
<style>
.recommendation-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateY(0);
}
.recommendation-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}
.recommendation-image {
    transition: transform 0.5s ease;
}
.recommendation-card:hover .recommendation-image {
    transform: scale(1.05);
}
.fade-in {
    animation: fadeIn 0.6s ease-out forwards;
    opacity: 0;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to   { opacity: 1; transform: translateY(0); }
}
.tab-button {
    transition: all 0.3s ease;
    position: relative;
}
.tab-button::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 50%;
    width: 0;
    height: 3px;
    background: linear-gradient(90deg, #DC2626, #FBBF24);
    transition: all 0.3s ease;
    transform: translateX(-50%);
    border-radius: 2px;
}
.tab-button.active::after { width: 100%; }
.glass-effect {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-yellow-50">

    <!-- Header -->
    <div class="bg-gradient-to-r from-red-600 via-orange-500 to-yellow-500 text-white">
        <div class="container mx-auto px-4 py-8">
            <div class="flex items-center space-x-4">
                <a href="/" class="p-2 rounded-full bg-white/20 hover:bg-white/30 transition-all duration-300 transform hover:scale-110">
                    <svg width="24px" height="24px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"/>
                        <path fill="currentColor" d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold">💡 Mis Recomendaciones</h1>
                    <p class="text-orange-100 text-sm mt-1">Descubre contenido personalizado para ti</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab de Marketing -->
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-1">
            <ul class="flex flex-wrap gap-2" role="tablist">
                <li role="presentation">
                    <button class="tab-button active px-6 py-3 rounded-xl font-medium text-sm text-red-600"
                        type="button" role="tab" aria-selected="true">
                        <span class="flex items-center space-x-2">
                            <span>📢</span>
                            <span>Marketing</span>
                        </span>
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Contenido Marketing -->
    <div class="container mx-auto px-4 pb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            <!-- Card: Congreso Mexicano del Petróleo -->
            <article class="recommendation-card bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 hover:border-red-200 fade-in">
                <div class="relative h-48 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                    <img class="recommendation-image w-full h-full object-cover"
                         src="{{ asset('storage/recommendations/congreso_petroleo.jpg') }}"
                         alt="Congreso Mexicano del Petróleo"
                         loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>
                    <div class="absolute top-3 left-3">
                        <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-xs font-semibold text-gray-700 rounded-full border border-white/20">
                            📢 Marketing
                        </span>
                    </div>
                </div>

                <div class="p-6 flex flex-col">
                    <h4 class="font-bold text-gray-900 text-lg mb-3">
                        Congreso Mexicano del Petróleo
                    </h4>
                    <p class="text-gray-600 text-sm">
                        Congreso Mexicano del Petróleo WTC, Boca del Río Veracruz 03 al 06 de junio de 2026
                    </p>
                </div>
            </article>

        </div>
    </div>
</div>
@endsection