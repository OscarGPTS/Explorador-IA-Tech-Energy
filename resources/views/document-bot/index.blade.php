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
                <!-- Opciones de búsqueda -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-brain text-indigo-500 mr-2"></i>
                                Consulta de Documentos
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" id="query-mode-description">
                                Modelo local (Ollama)
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="use-external-api" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            </label>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                <i class="fas fa-cloud text-blue-500 mr-1"></i>
                                Usar modelo externo (API)
                            </span>
                        </div>
                    </div>
                    
                    <!-- Checkbox secundario para razonamiento profundo -->
                    <div id="deep-reasoning-container" class="hidden pt-3 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="deep-reasoning" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                            </label>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                <i class="fas fa-microscope text-purple-500 mr-1"></i>
                                Usar razonamiento profundo
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
                                (análisis más detallado con hasta 20 chunks)
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Formulario de consulta -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <form id="query-form">
                        <!-- Indicador de documento seleccionado -->
                        <div id="selected-doc-banner" class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg hidden">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                                    <span class="text-sm font-medium text-blue-800 dark:text-blue-300">
                                        Documento: <span id="selected-doc-name"></span>
                                    </span>
                                </div>
                                <button type="button" id="clear-doc" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tu pregunta
                            </label>
                            <textarea id="question-input" rows="4" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="¿Qué quieres saber de los documentos?"></textarea>
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
    let selectedDocumentId = null;
    let selectedDocumentName = '';
    let useExternalAPI = false;
    let useDeepReasoning = false;

    // Cargar documentos al inicio
    document.addEventListener('DOMContentLoaded', function() {
        loadDocuments('all');
        setupExternalAPIToggle();
        setupDeepReasoningToggle();
        setupClearDocumentButton();
    });

    // Setup external API toggle
    function setupExternalAPIToggle() {
        const checkbox = document.getElementById('use-external-api');
        const deepReasoningContainer = document.getElementById('deep-reasoning-container');
        
        checkbox.addEventListener('change', function() {
            useExternalAPI = this.checked;
            
            // Mostrar/ocultar el checkbox de razonamiento profundo
            if (useExternalAPI) {
                deepReasoningContainer.classList.remove('hidden');
            } else {
                deepReasoningContainer.classList.add('hidden');
                // Resetear razonamiento profundo cuando se desactiva API externa
                document.getElementById('deep-reasoning').checked = false;
                useDeepReasoning = false;
            }
            
            updateQueryModeDescription();
        });
    }

    // Setup deep reasoning toggle
    function setupDeepReasoningToggle() {
        const checkbox = document.getElementById('deep-reasoning');
        checkbox.addEventListener('change', function() {
            useDeepReasoning = this.checked;
            updateQueryModeDescription();
        });
    }

    // Setup clear document button
    function setupClearDocumentButton() {
        document.getElementById('clear-doc').addEventListener('click', function() {
            selectedDocumentId = null;
            selectedDocumentName = '';
            document.getElementById('selected-doc-banner').classList.add('hidden');
            
            // Limpiar selección visual en documentos
            document.querySelectorAll('.document-item').forEach(item => {
                item.classList.remove('bg-blue-100', 'dark:bg-blue-900', 'border-2', 'border-blue-500');
            });
            
            updateQueryModeDescription();
        });
    }

    function updateQueryModeDescription() {
        const description = document.getElementById('query-mode-description');
        
        if (!useExternalAPI) {
            // Modelo local (Ollama)
            if (selectedDocumentId) {
                description.textContent = '📄 Análisis de documento (Ollama - Local)';
            } else {
                description.textContent = '💬 Consulta general (Ollama - Local)';
            }
        } else {
            // API externa (OpenAI)
            if (useDeepReasoning) {
                description.textContent = '🔍 Razonamiento profundo (OpenAI - hasta 20 chunks)';
            } else {
                description.textContent = '🚀 Consulta rápida (OpenAI - hasta 3 chunks)';
            }
        }
    }

    function showSelectedDocument(id, name) {
        selectedDocumentId = id;
        selectedDocumentName = name;
        document.getElementById('selected-doc-name').textContent = name;
        document.getElementById('selected-doc-banner').classList.remove('hidden');
        updateQueryModeDescription();
    }

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
                ? '{{ route("document-bot.simple.recent-documents") }}'
                : '{{ route("document-bot.simple.documents") }}';
            
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

        container.innerHTML = documents.map(doc => {
            const previewUrl = doc.preview_url || null;
            const downloadUrl = doc.download_url || null;
            
            return `
                <div class="document-item p-3 rounded-lg border border-gray-200 dark:border-gray-700" 
                     data-doc-id="${doc.id}" data-doc-name="${doc.title.replace(/'/g, "\\'")}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start flex-1 min-w-0 cursor-pointer" 
                             onclick="selectDocument(${doc.id}, '${doc.title.replace(/'/g, "\\'")}')"> 
                            <i class="fas fa-file-pdf text-red-500 mt-1 mr-2"></i>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">${doc.title}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-1 ml-2">
                            ${previewUrl ? `
                                <button onclick="previewDocument('${previewUrl}', '${doc.title.replace(/'/g, "\\'")}, event')" 
                                        class="p-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors" 
                                        title="Previsualizar">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            ` : ''}
                            ${downloadUrl ? `
                                <button onclick="downloadDocument('${downloadUrl}', '${doc.title.replace(/'/g, "\\'")}, event')" 
                                        class="p-1.5 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded transition-colors" 
                                        title="Descargar">
                                    <i class="fas fa-download text-sm"></i>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Previsualizar documento en nueva pestaña
    function previewDocument(url, title, event) {
        if (event) event.stopPropagation(); // Prevenir selección del documento
        
        if (!url) {
            alert('⚠️ URL de previsualización no disponible');
            return;
        }
        
        // Crear enlace temporal y abrirlo
        const link = document.createElement('a');
        link.href = url;
        link.target = '_blank';
        link.rel = 'noopener noreferrer';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Descargar documento
    function downloadDocument(url, title, event) {
        if (event) event.stopPropagation(); // Prevenir selección del documento
        
        if (!url) {
            alert('⚠️ URL de descarga no disponible');
            return;
        }
        
        // Crear enlace temporal para forzar descarga
        const link = document.createElement('a');
        link.href = url;
        link.target = '_blank';
        link.rel = 'noopener noreferrer';
        link.download = title;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function selectDocument(id, title) {
        // Limpiar selección anterior
        document.querySelectorAll('.document-item').forEach(item => {
            item.classList.remove('bg-blue-100', 'dark:bg-blue-900', 'border-2', 'border-blue-500');
        });
        
        // Marcar el documento seleccionado
        const selectedItem = document.querySelector(`[data-doc-id="${id}"]`);
        if (selectedItem) {
            selectedItem.classList.add('bg-blue-100', 'dark:bg-blue-900', 'border-2', 'border-blue-500');
        }
        
        // Mostrar banner y actualizar descripción
        showSelectedDocument(id, title);
    }

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
            
            // Lógica simplificada basada en API externa y razonamiento profundo
            if (!useExternalAPI) {
                // Modelo LOCAL (Ollama)
                if (selectedDocumentId) {
                    // Análisis de documento específico
                    endpoint = '{{ route("document-bot.simple.analyze-document") }}';
                    payload = { 
                        documento_id: selectedDocumentId,
                        pregunta: question 
                    };
                } else {
                    // Consulta general
                    endpoint = '{{ route("document-bot.simple.query") }}';
                    payload = { pregunta: question };
                }
            } else {
                // API EXTERNA (OpenAI)
                if (useDeepReasoning) {
                    // Razonamiento profundo (hasta 20 chunks)
                    endpoint = '{{ route("document-bot.advanced.deep-reasoning") }}';
                    payload = { pregunta: question, k: 20 };
                } else {
                    // Consulta rápida (hasta 3 chunks)
                    endpoint = '{{ route("document-bot.advanced.quick-query") }}';
                    payload = { pregunta: question };
                }
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
                showError(result.error || result.message || 'Error en la consulta');
            }
        } catch (error) {
            hideLoading();
            showError('Error de conexión: ' + error.message);
            console.error('Error:', error);
        }
    });

    // Búsqueda semántica (solo disponible en bot avanzado)
    document.getElementById('semantic-search-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const query = document.getElementById('semantic-query').value;
        if (!query.trim()) return;

        const resultsContainer = document.getElementById('search-results');
        resultsContainer.innerHTML = '<div class="flex items-center justify-center py-4"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-purple-500"></div></div>';

        try {
            // La búsqueda semántica solo está disponible en bot avanzado
            const response = await fetch('{{ route("document-bot.advanced.semantic-search") }}', {
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

        container.innerHTML = results.map(r => {
            const previewUrl = r.preview_url || null;
            const downloadUrl = r.download_url || null;
            
            return `
                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg mb-2">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-900 dark:text-white">${r.title}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">${r.preview ? r.preview.substring(0, 100) + '...' : ''}</p>
                        </div>
                        <div class="flex items-center space-x-2 ml-2">
                            ${r.score ? `<span class="text-xs bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 px-2 py-1 rounded whitespace-nowrap">${(r.score * 100).toFixed(0)}%</span>` : ''}
                            ${previewUrl ? `
                                <button onclick="previewDocument('${previewUrl}', '${r.title.replace(/'/g, "\\\\'")}, event')" 
                                        class="p-1 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded transition-colors" 
                                        title="Previsualizar">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            ` : ''}
                            ${downloadUrl ? `
                                <button onclick="downloadDocument('${downloadUrl}', '${r.title.replace(/'/g, "\\\\'")}, event')" 
                                        class="p-1 text-green-600 hover:bg-green-100 dark:hover:bg-green-900/30 rounded transition-colors" 
                                        title="Descargar">
                                    <i class="fas fa-download text-xs"></i>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function displayResults(data) {
        const container = document.getElementById('results-container');
        const content = document.getElementById('response-content');
        const timeSpan = document.getElementById('response-time');
        
        content.innerHTML = `<p class="text-gray-900 dark:text-white whitespace-pre-wrap">${data.respuesta}</p>`;
        timeSpan.textContent = data.tiempo_respuesta ? `${data.tiempo_respuesta.toFixed(2)}s` : '';
        
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

    // Health check según bot seleccionado
    document.getElementById('btn-health').addEventListener('click', async function() {
        try {
            const endpoint = useExternalAPI 
                ? '{{ route("document-bot.advanced.health") }}'
                : '{{ route("document-bot.simple.health") }}';
                
            const response = await fetch(endpoint);
            const result = await response.json();
            
            if (result.success && result.data) {
                const data = result.data;
                let message = useExternalAPI 
                    ? '🔵 API Externa (OpenAI)\n\n'
                    : '🟢 Modelo Local (Ollama)\n\n';
                    
                message += `Estado: ${data.status}\n`;
                message += `IA Disponible: ${data.ia_disponible ? 'Sí' : 'No'}\n`;
                message += `ChromaDB: ${data.chromadb_disponible ? 'Sí' : 'No'}\n`;
                message += `Paperless: ${data.paperless_conectado ? 'Sí' : 'No'}\n`;
                message += `Total Documentos: ${data.total_documentos}\n`;
                
                if (useExternalAPI && data.total_vectores) {
                    message += `Total Vectores: ${data.total_vectores}\n`;
                }
                
                alert(message);
            } else {
                alert('❌ Error al verificar estado del sistema\n\n' + (result.error || 'Error desconocido'));
            }
        } catch (error) {
            alert('❌ Error de conexión\n\n' + error.message);
        }
    });

    // Stats (solo API externa)
    document.getElementById('btn-stats').addEventListener('click', async function() {
        if (!useExternalAPI) {
            alert('ℹ️ Estadísticas\n\nLas estadísticas detalladas solo están disponibles cuando se usa la API externa (OpenAI).\n\nActiva el checkbox "Usar modelo externo" para ver más información.');
            return;
        }
        
        try {
            const response = await fetch('{{ route("document-bot.advanced.stats") }}');
            const result = await response.json();
            
            if (result.success && result.data) {
                let message = '📊 Estadísticas del Sistema\n\n';
                message += `Documentos: ${result.data.total_documentos}\n`;
                message += `Vectores: ${result.data.total_vectores}\n`;
                message += `Modo: ${result.data.modo}\n`;
                message += `Modelo Rápido: ${result.data.modelo_rapido}\n`;
                message += `Modelo Razonamiento: ${result.data.modelo_razonamiento}\n`;
                
                alert(message);
            } else {
                alert('Error al obtener estadísticas');
            }
        } catch (error) {
            alert('Error de conexión: ' + error.message);
        }
    });
</script>
@endpush
@endsection
