@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                    Buscador de Documentos Corporativos
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Consulta inteligente en tus documentos con IA avanzada
                </p>
            </div>
            <div class="flex space-x-2">
                <button id="btn-stats" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>Estadísticas
                </button>
                <button id="btn-health" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-heartbeat mr-2"></i>Health Check
                </button>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Panel izquierdo - Documentos -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-folder-open text-yellow-500 mr-2"></i>
                        Documentos Disponibles
                    </h2>
                    
                    <!-- Tabs -->
                    <div class="flex space-x-2 mb-4">
                        <button id="tab-all" class="tab-button active px-3 py-2 text-sm font-medium rounded-lg">
                            Todos
                        </button>
                        <button id="tab-recent" class="tab-button px-3 py-2 text-sm font-medium rounded-lg">
                            Recientes
                        </button>
                    </div>

                    <!-- Lista de documentos -->
                    <div id="documents-list" class="space-y-2 max-h-96 overflow-y-auto">
                        <div class="flex items-center justify-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                        </div>
                    </div>
                </div>

                <!-- Panel de búsqueda semántica -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mt-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-search text-purple-500 mr-2"></i>
                        Búsqueda Semántica
                    </h2>
                    <form id="semantic-search-form">
                        <input type="text" id="semantic-query" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Buscar en documentos...">
                        <button type="submit" class="w-full mt-2 px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-search mr-2"></i>Buscar
                        </button>
                    </form>
                    <div id="search-results" class="mt-4"></div>
                </div>
            </div>

            <!-- Panel central - Consultas -->
            <div class="lg:col-span-2">
                <!-- Tipo de consulta -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-brain text-indigo-500 mr-2"></i>
                        Tipo de Consulta
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button class="query-type-btn active" data-type="simple">
                            <div class="p-4 border-2 border-blue-500 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-center cursor-pointer hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                                <i class="fas fa-bolt text-2xl text-blue-500 mb-2"></i>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Simple</h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Consulta rápida básica</p>
                            </div>
                        </button>
                        <button class="query-type-btn" data-type="quick">
                            <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <i class="fas fa-rocket text-2xl text-green-500 mb-2"></i>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Rápida</h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Con IA - 3 chunks</p>
                            </div>
                        </button>
                        <button class="query-type-btn" data-type="deep">
                            <div class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <i class="fas fa-microscope text-2xl text-purple-500 mb-2"></i>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Profunda</h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Análisis detallado - hasta 20 chunks</p>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Formulario de consulta -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <form id="query-form">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tu pregunta
                            </label>
                            <textarea id="question-input" rows="4" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="¿Qué quieres saber de los documentos?"></textarea>
                        </div>

                        <!-- Opciones avanzadas (solo para consultas avanzadas) -->
                        <div id="advanced-options" class="mb-4 hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Número de chunks (k)
                            </label>
                            <input type="number" id="chunks-input" min="1" max="20" value="10"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>

                        <button type="submit" id="submit-btn" 
                            class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i>
                            <span id="submit-text">Enviar Consulta</span>
                        </button>
                    </form>
                </div>

                <!-- Resultados -->
                <div id="results-container" class="mt-6 hidden">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                Respuesta
                            </h2>
                            <span id="response-time" class="text-sm text-gray-500"></span>
                        </div>
                        <div id="response-content" class="prose dark:prose-invert max-w-none">
                            <!-- Respuesta aquí -->
                        </div>
                        
                        <!-- Estadísticas (opcional) -->
                        <div id="response-stats" class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hidden">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Estadísticas</h3>
                            <div class="grid grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Tokens entrada:</span>
                                    <span id="tokens-in" class="font-semibold text-gray-900 dark:text-white ml-1"></span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Tokens salida:</span>
                                    <span id="tokens-out" class="font-semibold text-gray-900 dark:text-white ml-1"></span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Costo:</span>
                                    <span id="cost" class="font-semibold text-gray-900 dark:text-white ml-1"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading indicator -->
                <div id="loading-indicator" class="mt-6 hidden">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                        <div class="flex items-center justify-center space-x-3">
                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
                            <span class="text-gray-700 dark:text-gray-300">Procesando consulta...</span>
                        </div>
                    </div>
                </div>

                <!-- Error message -->
                <div id="error-message" class="mt-6 hidden">
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-500 mt-1 mr-3"></i>
                            <div>
                                <h3 class="text-sm font-semibold text-red-800 dark:text-red-300">Error</h3>
                                <p id="error-text" class="text-sm text-red-700 dark:text-red-400 mt-1"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .tab-button {
        transition: all 0.3s ease;
    }
    .tab-button.active {
        background-color: #3B82F6;
        color: white;
    }
    .tab-button:not(.active) {
        background-color: #E5E7EB;
        color: #4B5563;
    }
    .query-type-btn.active > div {
        border-width: 2px;
    }
    .query-type-btn:not(.active) > div {
        border-width: 2px;
    }
    .document-item {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .document-item:hover {
        background-color: #F3F4F6;
        transform: translateX(4px);
    }
    .dark .document-item:hover {
        background-color: #374151;
    }
</style>
@endpush

@push('scripts')
<script>
    let currentQueryType = 'simple';
    let selectedDocumentId = null;

    // Cargar documentos al inicio
    document.addEventListener('DOMContentLoaded', function() {
        loadDocuments('all');
    });

    // Tabs de documentos
    document.getElementById('tab-all').addEventListener('click', function() {
        setActiveTab('all');
        loadDocuments('all');
    });

    document.getElementById('tab-recent').addEventListener('click', function() {
        setActiveTab('recent');
        loadDocuments('recent');
    });

    function setActiveTab(tab) {
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
    }

    // Cargar documentos
    async function loadDocuments(type) {
        const container = document.getElementById('documents-list');
        container.innerHTML = '<div class="flex items-center justify-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div></div>';

        try {
            const endpoint = type === 'recent' 
                ? '{{ route("document-bot.recent-documents") }}'
                : '{{ route("document-bot.documents") }}';
            
            const response = await fetch(endpoint);
            const result = await response.json();

            if (result.success && result.data && result.data.documentos) {
                displayDocuments(result.data.documentos);
            } else {
                container.innerHTML = '<p class="text-sm text-gray-500 text-center">No se encontraron documentos</p>';
            }
        } catch (error) {
            container.innerHTML = '<p class="text-sm text-red-500 text-center">Error al cargar documentos</p>';
            console.error('Error:', error);
        }
    }

    function displayDocuments(documents) {
        const container = document.getElementById('documents-list');
        if (documents.length === 0) {
            container.innerHTML = '<p class="text-sm text-gray-500 text-center">No hay documentos disponibles</p>';
            return;
        }

        container.innerHTML = documents.map(doc => `
            <div class="document-item p-3 rounded-lg border border-gray-200 dark:border-gray-700" 
                 onclick="selectDocument(${doc.id}, '${doc.title}')">
                <div class="flex items-start">
                    <i class="fas fa-file-pdf text-red-500 mt-1 mr-2"></i>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">${doc.title}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ID: ${doc.id} • ${doc.created}</p>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function selectDocument(id, title) {
        selectedDocumentId = id;
        document.getElementById('question-input').value = `Analiza el documento: ${title}`;
    }

    // Selección de tipo de consulta
    document.querySelectorAll('.query-type-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.query-type-btn').forEach(b => {
                b.classList.remove('active');
                b.querySelector('div').classList.remove('border-blue-500', 'border-green-500', 'border-purple-500', 'bg-blue-50', 'bg-green-50', 'bg-purple-50');
                b.querySelector('div').classList.add('border-gray-300');
            });
            
            this.classList.add('active');
            const type = this.dataset.type;
            currentQueryType = type;
            
            const div = this.querySelector('div');
            div.classList.remove('border-gray-300');
            
            if (type === 'simple') {
                div.classList.add('border-blue-500', 'bg-blue-50');
                document.getElementById('advanced-options').classList.add('hidden');
            } else if (type === 'quick') {
                div.classList.add('border-green-500', 'bg-green-50');
                document.getElementById('advanced-options').classList.add('hidden');
            } else {
                div.classList.add('border-purple-500', 'bg-purple-50');
                document.getElementById('advanced-options').classList.remove('hidden');
            }
        });
    });

    // Form submission
    document.getElementById('query-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const question = document.getElementById('question-input').value;
        if (!question.trim()) return;

        showLoading();
        hideResults();
        hideError();

        try {
            let endpoint, payload;
            
            if (currentQueryType === 'simple') {
                endpoint = '{{ route("document-bot.query") }}';
                payload = { pregunta: question };
            } else if (currentQueryType === 'quick') {
                endpoint = '{{ route("document-bot.quick-query") }}';
                payload = { pregunta: question };
            } else {
                endpoint = '{{ route("document-bot.deep-reasoning") }}';
                const k = document.getElementById('chunks-input').value;
                payload = { pregunta: question, k: parseInt(k) };
            }

            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();
            
            hideLoading();
            
            if (result.success && result.data) {
                displayResults(result.data);
            } else {
                showError(result.error || 'Error en la consulta');
            }
        } catch (error) {
            hideLoading();
            showError('Error de conexión: ' + error.message);
            console.error('Error:', error);
        }
    });

    // Búsqueda semántica
    document.getElementById('semantic-search-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const query = document.getElementById('semantic-query').value;
        if (!query.trim()) return;

        const resultsContainer = document.getElementById('search-results');
        resultsContainer.innerHTML = '<div class="flex items-center justify-center py-4"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-purple-500"></div></div>';

        try {
            const response = await fetch('{{ route("document-bot.semantic-search") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ query: query, k: 5 })
            });

            const result = await response.json();
            
            if (result.success && result.data && result.data.resultados) {
                displaySearchResults(result.data.resultados);
            } else {
                resultsContainer.innerHTML = '<p class="text-sm text-red-500 text-center">No se encontraron resultados</p>';
            }
        } catch (error) {
            resultsContainer.innerHTML = '<p class="text-sm text-red-500 text-center">Error en la búsqueda</p>';
            console.error('Error:', error);
        }
    });

    function displaySearchResults(results) {
        const container = document.getElementById('search-results');
        if (results.length === 0) {
            container.innerHTML = '<p class="text-sm text-gray-500 text-center">No se encontraron resultados</p>';
            return;
        }

        container.innerHTML = results.map(r => `
            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg mb-2">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-xs font-semibold text-gray-900 dark:text-white">${r.title}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">${r.preview.substring(0, 100)}...</p>
                    </div>
                    ${r.score ? `<span class="text-xs bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 px-2 py-1 rounded">${(r.score * 100).toFixed(0)}%</span>` : ''}
                </div>
            </div>
        `).join('');
    }

    function displayResults(data) {
        const container = document.getElementById('results-container');
        const content = document.getElementById('response-content');
        const timeSpan = document.getElementById('response-time');
        
        content.innerHTML = `<p class="text-gray-900 dark:text-white whitespace-pre-wrap">${data.respuesta}</p>`;
        timeSpan.textContent = data.tiempo_respuesta ? `${data.tiempo_respuesta.toFixed(2)}s` : '';
        
        // Mostrar estadísticas si existen
        if (data.estadisticas) {
            const statsDiv = document.getElementById('response-stats');
            document.getElementById('tokens-in').textContent = data.estadisticas.tokens_entrada || '-';
            document.getElementById('tokens-out').textContent = data.estadisticas.tokens_salida || '-';
            document.getElementById('cost').textContent = data.estadisticas.costo_usd ? `$${data.estadisticas.costo_usd.toFixed(6)}` : '-';
            statsDiv.classList.remove('hidden');
        }
        
        container.classList.remove('hidden');
        container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function showLoading() {
        document.getElementById('loading-indicator').classList.remove('hidden');
    }

    function hideLoading() {
        document.getElementById('loading-indicator').classList.add('hidden');
    }

    function showResults() {
        document.getElementById('results-container').classList.remove('hidden');
    }

    function hideResults() {
        document.getElementById('results-container').classList.add('hidden');
    }

    function showError(message) {
        document.getElementById('error-text').textContent = message;
        document.getElementById('error-message').classList.remove('hidden');
    }

    function hideError() {
        document.getElementById('error-message').classList.add('hidden');
    }

    // Health check
    document.getElementById('btn-health').addEventListener('click', async function() {
        try {
            const response = await fetch('{{ route("document-bot.health") }}');
            const result = await response.json();
            
            let message = '🟢 Sistema Operativo\n\n';
            if (result.simple_bot && result.simple_bot.data) {
                message += `Bot Simple: ${result.simple_bot.data.status}\n`;
                message += `Documentos: ${result.simple_bot.data.total_documentos}\n`;
            }
            if (result.advanced_bot && result.advanced_bot.data) {
                message += `\nBot Avanzado: ${result.advanced_bot.data.status}\n`;
                message += `Vectores: ${result.advanced_bot.data.total_vectores}\n`;
            }
            
            alert(message);
        } catch (error) {
            alert('Error al verificar estado del sistema');
        }
    });

    // Stats
    document.getElementById('btn-stats').addEventListener('click', async function() {
        try {
            const response = await fetch('{{ route("document-bot.stats") }}');
            const result = await response.json();
            
            if (result.success && result.data) {
                let message = '📊 Estadísticas del Sistema\n\n';
                message += `Documentos: ${result.data.total_documentos}\n`;
                message += `Vectores: ${result.data.total_vectores}\n`;
                message += `Modo: ${result.data.modo}\n`;
                message += `Modelo Rápido: ${result.data.modelo_rapido}\n`;
                message += `Modelo Razonamiento: ${result.data.modelo_razonamiento}\n`;
                
                alert(message);
            }
        } catch (error) {
            alert('Error al obtener estadísticas');
        }
    });
</script>
@endpush
@endsection
