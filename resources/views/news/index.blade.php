@extends('layouts.app')

@push('styles')
<style>
/* Animaciones y transiciones suaves */
.news-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateY(0);
}

.news-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.news-image {
    transition: transform 0.5s ease;
}

.news-card:hover .news-image {
    transform: scale(1.05);
}

.fade-in {
    animation: fadeIn 0.6s ease-out forwards;
    opacity: 0;
}

.fade-in-delay-1 { animation-delay: 0.1s; }
.fade-in-delay-2 { animation-delay: 0.2s; }
.fade-in-delay-3 { animation-delay: 0.3s; }
.fade-in-delay-4 { animation-delay: 0.4s; }

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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

.tab-button.active::after,
.tab-button:hover::after {
    width: 100%;
}

.news-badge {
    background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.glass-effect {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.gradient-text {
    background: linear-gradient(135deg, #DC2626 0%, #FBBF24 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.loading-shimmer {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
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
                        <h1 class="text-3xl font-bold">📰 Novedades y Noticias</h1>
                        <p class="text-orange-100 text-sm mt-1">Mantente informado con las últimas noticias del sector</p>
                    </div>
                </div>

                <button data-modal-target="top-right-modal" data-modal-toggle="top-right-modal" 
                    class="flex items-center space-x-3 bg-white/20 hover:bg-white/30 
                           backdrop-filter backdrop-blur-sm border border-white/30
                           font-medium rounded-full text-sm px-6 py-3 text-white
                           transition-all duration-300 transform hover:scale-105" 
                    type="button">
                    <span class="text-base">Personalizar</span>
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.2788 2.15224C13.9085 2 13.439 2 12.5 2C11.561 2 11.0915 2 10.7212 2.15224C10.2274 2.35523 9.83509 2.74458 9.63056 3.23463C9.53719 3.45834 9.50065 3.7185 9.48635 4.09799C9.46534 4.65568 9.17716 5.17189 8.69017 5.45093C8.20318 5.72996 7.60864 5.71954 7.11149 5.45876C6.77318 5.2813 6.52789 5.18262 6.28599 5.15102C5.75609 5.08178 5.22018 5.22429 4.79616 5.5472C4.47814 5.78938 4.24339 6.1929 3.7739 6.99993C3.30441 7.80697 3.06967 8.21048 3.01735 8.60491C2.94758 9.1308 3.09118 9.66266 3.41655 10.0835C3.56506 10.2756 3.77377 10.437 4.0977 10.639C4.57391 10.936 4.88032 11.4419 4.88029 12C4.88026 12.5581 4.57386 13.0639 4.0977 13.3608C3.77372 13.5629 3.56497 13.7244 3.41645 13.9165C3.09108 14.3373 2.94749 14.8691 3.01725 15.395C3.06957 15.7894 3.30432 16.193 3.7738 17C4.24329 17.807 4.47804 18.2106 4.79606 18.4527C5.22008 18.7756 5.75599 18.9181 6.28589 18.8489C6.52778 18.8173 6.77305 18.7186 7.11133 18.5412C7.60852 18.2804 8.2031 18.27 8.69012 18.549C9.17714 18.8281 9.46533 19.3443 9.48635 19.9021C9.50065 20.2815 9.53719 20.5417 9.63056 20.7654C9.83509 21.2554 10.2274 21.6448 10.7212 21.8478C11.0915 22 11.561 22 12.5 22C13.439 22 13.9085 22 14.2788 21.8478C14.7726 21.6448 15.1649 21.2554 15.3694 20.7654C15.4628 20.5417 15.4994 20.2815 15.5137 19.902C15.5347 19.3443 15.8228 18.8281 16.3098 18.549C16.7968 18.2699 17.3914 18.2804 17.8886 18.5412C18.2269 18.7186 18.4721 18.8172 18.714 18.8488C19.2439 18.9181 19.7798 18.7756 20.2038 18.4527C20.5219 18.2105 20.7566 17.807 21.2261 16.9999C21.6956 16.1929 21.9303 15.7894 21.9827 15.395C22.0524 14.8691 21.9088 14.3372 21.5835 13.9164C21.4349 13.7243 21.2262 13.5628 20.9022 13.3608C20.4261 13.0639 20.1197 12.558 20.1197 11.9999C20.1197 11.4418 20.4261 10.9361 20.9022 10.6392C21.2263 10.4371 21.435 10.2757 21.5836 10.0835C21.9089 9.66273 22.0525 9.13087 21.9828 8.60497C21.9304 8.21055 21.6957 7.80703 21.2262 7C20.7567 6.19297 20.522 5.78945 20.2039 5.54727C19.7799 5.22436 19.244 5.08185 18.7141 5.15109C18.4722 5.18269 18.2269 5.28136 17.8887 5.4588C17.3915 5.71959 16.7969 5.73002 16.3099 5.45096C15.8229 5.17191 15.5347 4.65566 15.5136 4.09794C15.4993 3.71848 15.4628 3.45833 15.3694 3.23463C15.1649 2.74458 14.7726 2.35523 14.2788 2.15224ZM12.5 15C14.1695 15 15.5228 13.6569 15.5228 12C15.5228 10.3431 14.1695 9 12.5 9C10.8305 9 9.47716 10.3431 9.47716 12C9.47716 13.6569 10.8305 15 12.5 15Z" fill="currentColor"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de personalización mejorado -->
    <div id="top-right-modal" data-modal-placement="center" tabindex="-1" 
        class="fixed inset-0 z-50 hidden w-full h-full bg-black/50 backdrop-blur-sm">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl glass-effect transform transition-all duration-300">
                <!-- Header del modal -->
                <div class="flex items-center justify-between p-6 border-b border-gray-100">
                    <div>
                        <h3 class="text-2xl font-bold gradient-text">🎨 Personaliza tus noticias</h3>
                        <p class="text-gray-500 text-sm mt-1">Selecciona las categorías de tu interés</p>
                    </div>
                    <button type="button" class="p-2 text-gray-400 bg-gray-100 hover:bg-gray-200 
                                               rounded-full transition-all duration-300 transform hover:scale-110" 
                            data-modal-hide="top-right-modal">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>

                <!-- Cuerpo del modal -->
                <div class="p-6">
                    <form action="{{ route('news.updatePreferences') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($news as $id => $name)
                                <label class="flex items-center p-4 rounded-xl border-2 border-gray-100 
                                    hover:border-red-200 hover:bg-red-50 cursor-pointer
                                             transition-all duration-300 group">
                                    <input type="checkbox" name="news[]" value="{{ $id }}" 
                                        class="w-5 h-5 text-red-600 bg-gray-100 border-gray-300 rounded 
                                               focus:ring-red-500 focus:ring-2 transition-all duration-300"
                                        @if(in_array($id, $userNewsIds)) checked @endif>
                                    <span class="ml-3 text-sm font-medium text-gray-900 group-hover:text-red-700 
                                                transition-colors duration-300">{{ $name }}</span>
                                    <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <span class="text-red-500">✨</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
                            <button data-modal-hide="top-right-modal" type="button" 
                                class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 
                                       rounded-full hover:bg-gray-200 transition-all duration-300
                                       transform hover:scale-105">
                                Cancelar
                            </button>
                            <button type="submit" 
                                class="px-8 py-3 text-sm font-medium text-white bg-gradient-to-r 
                                       from-red-500 to-yellow-500 rounded-full hover:from-red-600 
                                       hover:to-yellow-600 transition-all duration-300 transform hover:scale-105 
                                       shadow-lg hover:shadow-xl">
                                💾 Guardar Preferencias
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de pestañas modernizada -->
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-1">
            <ul class="flex flex-wrap gap-2" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
                @foreach ($newsData as $index => $category) 
                    <li role="presentation">
                        <button class="tab-button px-6 py-3 rounded-xl font-medium text-sm transition-all duration-300
                                     text-gray-600 hover:text-red-600 hover:bg-red-50
                                     {{ $index === 0 ? 'active text-red-600 border-red-500' : 'border-transparent' }}" 
                            id="tab-{{ $category->id }}" 
                            data-tab="content-{{ $category->id }}"
                            data-tabs-target="#content-{{ $category->id }}" 
                            type="button" role="tab" aria-controls="content-{{ $category->id }}" 
                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                            <span class="flex items-center space-x-2">
                                <span>{{ $category->category }}</span>
                                @if(count($category->news) > 0)
                                    <span class="news-badge px-2 py-1 text-xs rounded-full text-white font-semibold">
                                        {{ count($category->news) }}
                                    </span>
                                @endif
                            </span>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Contenido de las pestañas con grid mejorado -->
    <div class="container mx-auto px-4 pb-12">
        <div id="default-tab-content">
            @foreach ($newsData as $index => $category) 
                <div class="{{ $index === 0 ? '' : 'hidden' }} transition-all duration-500" 
                     id="content-{{ $category->id }}" role="tabpanel" aria-labelledby="tab-{{ $category->id }}">
                    
                    @if(count($category->news) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach ($category->news as $itemIndex => $item)
                                <article class="news-card bg-white rounded-2xl shadow-lg overflow-hidden 
                                              border border-gray-100 hover:border-red-200 
                                              fade-in fade-in-delay-{{ ($itemIndex % 4) + 1 }}">
                                    <!-- Imagen con overlay -->
                                    <div class="relative h-48 overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                                        @if($item->image_url)
                                            <img class="news-image w-full h-full object-cover" 
                                                 src="{{ $item->image_url }}" 
                                                 alt="{{ $item->title }}"
                                                 loading="lazy">
                                        @else
                                            <div class="flex items-center justify-center h-full bg-gradient-to-br from-red-100 to-yellow-100">
                                                <svg class="w-16 h-16 text-red-300" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <!-- Overlay con gradiente -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>
                                        
                                        <!-- Badge de fuente -->
                                        <div class="absolute top-3 left-3">
                                            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-xs font-semibold 
                                                       text-gray-700 rounded-full border border-white/20">
                                                📰 {{ ucfirst($item->source) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Contenido de la card -->
                                    <div class="p-6 flex flex-col h-40">
                                        <h4 class="font-bold text-gray-900 text-lg mb-3 truncate">
                                            {{ $item->title }}
                                        </h4>

                                        <p class="text-gray-600 text-sm flex-grow mb-4 truncate">
                                            {{ $item->description }}
                                        </p>
                                        
                                        <!-- Botón mejorado -->
                                        <div class="mt-auto">
                                            <button data-modal-target="modal-{{ $item->id }}" data-modal-toggle="modal-{{ $item->id }}" 
                                                class="w-full bg-gradient-to-r from-red-500 to-yellow-500 
                                                       hover:from-red-600 hover:to-yellow-600 text-white 
                                                       font-semibold py-3 px-4 rounded-xl transition-all duration-300 
                                                       transform hover:scale-105 shadow-md hover:shadow-lg
                                                       flex items-center justify-center space-x-2">
                                                <span>Ver Detalles</span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </article>

                                <!-- Modal de detalles mejorado -->
                                <div id="modal-{{ $item->id }}" tabindex="-1" aria-hidden="true" 
                                    class="hidden fixed inset-0 z-50 w-full h-full bg-black/50 backdrop-blur-sm">
                                    <div class="flex items-center justify-center min-h-screen px-4 py-8">
                                        <div class="relative w-full max-w-4xl max-h-[90vh] bg-white rounded-3xl shadow-2xl 
                                                   glass-effect transform transition-all duration-300 overflow-hidden">
                                            
                                            <!-- Header del modal -->
                                            <div class="sticky top-0 bg-white/95 backdrop-blur-sm border-b border-gray-100 p-6 z-10">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1 pr-4">
                                                        <h3 class="text-2xl font-bold text-gray-900 leading-tight">
                                                            {{ $item->title }}
                                                        </h3>
                                                        <div class="flex items-center space-x-4 mt-3">
                                                            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                                                📰 {{ ucfirst($item->source) }}
                                                            </span>
                                                            <span class="text-gray-500 text-sm">
                                                                🕒 {{ $item->created_at->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <button type="button" 
                                                        class="p-2 text-gray-400 hover:text-gray-600 bg-gray-100 hover:bg-gray-200 
                                                               rounded-full transition-all duration-300 transform hover:scale-110 flex-shrink-0" 
                                                        data-modal-hide="modal-{{ $item->id }}">
                                                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <!-- Contenido del modal con scroll -->
                                            <div class="overflow-y-auto max-h-[calc(90vh-140px)] p-6 space-y-6">
                                                <!-- Imagen principal -->
                                                @if ($item->image_url)
                                                    <div class="relative rounded-2xl overflow-hidden shadow-lg">
                                                        <img class="w-full max-h-96 object-cover" 
                                                             src="{{ $item->image_url }}" 
                                                             alt="{{ $item->title }}">
                                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>
                                                    </div>
                                                @endif
                                                
                                                <!-- Descripción -->
                                                <div class="prose prose-lg max-w-none">
                                                    <p class="text-gray-700 leading-relaxed text-lg">
                                                        {{ $item->description }}
                                                    </p>
                                                </div>
                                                
                                                <!-- Contenido completo -->
                                                <div class="prose prose-lg max-w-none">
                                                    <div class="p-6 bg-gray-50 rounded-2xl border-l-4 border-indigo-500">
                                                        <p class="text-gray-800 leading-relaxed">
                                                            {{ $item->content }}
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                                <!-- Enlace externo -->
                                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-yellow-50 
                                                           rounded-2xl border border-red-200">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="p-2 bg-red-100 rounded-full">
                                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900">Leer artículo completo</p>
                                                            <p class="text-xs text-gray-500">Fuente: {{ ucfirst($item->source) }}</p>
                                                        </div>
                                                    </div>
                                                    <a href="{{ $item->external_link }}" target="_blank" 
                                                       class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold 
                                                              rounded-full transition-all duration-300 transform hover:scale-105 
                                                              shadow-md hover:shadow-lg flex items-center space-x-2">
                                                        <span>Ir al sitio</span>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Estado vacío mejorado -->
                        <div class="flex flex-col items-center justify-center py-20 text-center">
                            <div class="p-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mb-6">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" 
                                          d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay noticias disponibles</h3>
                            <p class="text-gray-500 max-w-md">
                                Aún no tenemos noticias en esta categoría. Revisa más tarde o explora otras secciones.
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar todos los botones de tab
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('[role="tabpanel"]');
    
    // Función para cambiar de tab
    function switchTab(targetTab) {
        // Remover clase active de todos los botones
        tabButtons.forEach(button => {
            button.classList.remove('active');
            button.classList.remove('text-red-600', 'border-red-500');
            button.classList.add('text-gray-600', 'border-transparent');
            button.setAttribute('aria-selected', 'false');
        });
        
        // Ocultar todos los contenidos
        tabContents.forEach(content => {
            content.classList.add('hidden');
        });
        
        // Activar el botón clickeado
        const activeButton = document.querySelector(`[data-tab="${targetTab}"]`);
        if (activeButton) {
            activeButton.classList.add('active');
            activeButton.classList.remove('text-gray-600', 'border-transparent');
            activeButton.classList.add('text-red-600', 'border-red-500');
            activeButton.setAttribute('aria-selected', 'true');
        }
        
        // Mostrar el contenido correspondiente
        const activeContent = document.getElementById(targetTab);
        if (activeContent) {
            activeContent.classList.remove('hidden');
        }
    }
    
    // Agregar event listeners a todos los botones
    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const targetTab = this.getAttribute('data-tab');
            switchTab(targetTab);
        });
    });
    
    // Inicializar el primer tab como activo
    if (tabButtons.length > 0) {
        const firstTab = tabButtons[0].getAttribute('data-tab');
        switchTab(firstTab);
    }
});
</script>
@endsection