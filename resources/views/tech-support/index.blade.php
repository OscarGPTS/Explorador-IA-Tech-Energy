@extends('layouts.app')

@section('title', 'Soporte Técnico')

@push('styles')
<style>
    .solution-content ul {
        margin: 0.5rem 0;
    }
    .solution-content li {
        margin: 0.25rem 0;
    }
    .solution-content h4 {
        margin-bottom: 0.5rem !important;
    }
    .solution-content .space-y-4 > * + * {
        margin-top: 1rem;
    }
    .solution-content .space-y-1 > * + * {
        margin-top: 0.25rem;
    }

    /* Animaciones y transiciones suaves */
    .support-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateY(0);
    }

    .support-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .gradient-text {
        background: linear-gradient(135deg, #DC2626 0%, #FBBF24 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
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
                        <h1 class="text-4xl font-bold">🎧 Soporte Técnico</h1>
                        <p class="text-orange-100 text-lg mt-2">
                            Resuelve tus problemas tecnológicos de forma rápida y sencilla
                        </p>
                    </div>
                </div>
                <a href="{{ route('tech-support.dashboard') }}" 
                   class="flex items-center space-x-2 bg-white/20 hover:bg-white/30 backdrop-filter backdrop-blur-sm border border-white/30 font-medium rounded-full text-sm px-6 py-3 text-white transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-chart-line"></i>
                    <span>Ver Dashboard</span>
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto p-6">
        <!-- Estadísticas rápidas con tema rojo-amarillo -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="support-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-red-100 to-red-200 rounded-xl">
                        <i class="fas fa-comments text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm font-medium">Total Conversaciones</p>
                        <p class="text-2xl font-bold gradient-text">{{ $stats['total_conversations'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="support-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-green-100 to-green-200 rounded-xl">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm font-medium">Resueltos Hoy</p>
                        <p class="text-2xl font-bold gradient-text">{{ $stats['resolved_today'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="support-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl">
                        <i class="fas fa-level-up-alt text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm font-medium">Escalados Hoy</p>
                        <p class="text-2xl font-bold gradient-text">{{ $stats['escalated_today'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="support-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-6 border-l-4 border-red-400">
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-red-100 to-red-200 rounded-xl">
                        <i class="fas fa-clock text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm font-medium">Promedio Respuesta</p>
                        <p class="text-2xl font-bold gradient-text">< 2 min</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secciones adicionales: Empleados y Documentos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Búsqueda de Empleados -->
            <div class="support-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden border border-white/20">
                <div class="bg-gradient-to-r from-blue-600 to-cyan-500 p-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-users mr-3"></i>
                        Buscar Empleados
                    </h2>
                    <p class="text-blue-100 mt-2">
                        Encuentra información de contacto de empleados
                    </p>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label for="employee-search" class="block text-sm font-medium text-gray-700 mb-2">
                                Buscar por nombre, departamento o cargo
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="employee-search" 
                                    placeholder="Ej: Juan Pérez, IT, Administración, Dirección General..."
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2" id="employee-tags">
                            <!-- Los tags se cargarán dinámicamente -->
                            <div class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-sm">
                                <i class="fas fa-spinner fa-spin mr-1"></i>Cargando...
                            </div>
                        </div>
                        
                        <div id="employee-results" class="mt-4 max-h-64 overflow-y-auto">
                            <div class="text-center text-gray-500 py-8">
                                <i class="fas fa-search text-3xl mb-2"></i>
                                <p>Usa el campo de búsqueda o los filtros para encontrar empleados</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Búsqueda de Documentos -->
            <div class="support-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden border border-white/20">
                <div class="bg-gradient-to-r from-green-600 to-teal-500 p-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-file-alt mr-3"></i>
                        Documentos Corporativos
                    </h2>
                    <p class="text-green-100 mt-2">
                        Accede a políticas, manuales y procedimientos
                    </p>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label for="document-search" class="block text-sm font-medium text-gray-700 mb-2">
                                Buscar documentos
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="document-search" 
                                    placeholder="Ej: Manual, Política, Procedimiento..."
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                >
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>
                        
                        <div id="document-tags-container" class="grid grid-cols-1 gap-2">
                            <!-- Los tags se cargarán dinámicamente aquí -->
                            <div class="text-center text-gray-500 py-4">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Cargando categorías...
                            </div>
                        </div>
                        
                        <div id="document-results" class="mt-4 max-h-64 overflow-y-auto">
                            <div class="text-center text-gray-500 py-8">
                                <i class="fas fa-folder-open text-3xl mb-2"></i>
                                <p>Selecciona una categoría o busca documentos específicos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección principal de soporte con tema rojo-amarillo -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Chat interactivo -->
            <div class="lg:col-span-2">
                <div class="support-card bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden border border-white/20">
                    <div class="bg-gradient-to-r from-red-600 via-orange-500 to-yellow-500 p-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-robot mr-3"></i>
                            Asistente de Soporte Técnico
                        </h2>
                        <p class="text-orange-100 mt-2">
                            Selecciona tu problema y te ayudo paso a paso de forma sencilla
                        </p>
                    </div>
                    
                    <!-- Área del chat -->
                    <div id="tech-support-chat" class="h-96 overflow-y-auto p-6 bg-gradient-to-br from-red-50/50 to-yellow-50/50">
                        <div class="flex items-start mb-4">
                            <div class="bg-blue-100 p-3 rounded-full mr-3">
                                <i class="fas fa-robot text-blue-600"></i>
                            </div>
                            <div class="bg-white rounded-lg p-4 shadow-sm max-w-md">
                                <p class="text-gray-800">
                                    ¡Hola! 👋 Soy tu asistente de soporte técnico. 
                                    Estoy aquí para ayudarte a resolver cualquier problema que tengas con tu computadora, 
                                    internet, correo, impresora o cualquier programa.
                                </p>
                                <p class="text-gray-800 mt-2">
                                    <strong>¿Con qué puedo ayudarte hoy?</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Input area -->
                    <div class="p-4 bg-white border-t">
                        <div class="flex flex-wrap gap-3">
                            <button id="main-menu" class="bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white px-6 py-2 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg border-2 border-blue-300">
                                <i class="fas fa-home mr-2"></i>
                                Menú Principal
                            </button>
                            <button id="restart-chat" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                <i class="fas fa-redo mr-2"></i>
                                Nuevo Problema
                            </button>
                            <button id="escalate-to-it" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200" style="display: none;">
                                <i class="fas fa-user-tie mr-2"></i>
                                Contactar IT
                            </button>
                            <button id="mark-resolved" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200" style="display: none;">
                                <i class="fas fa-check mr-2"></i>
                                Problema Resuelto
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel lateral -->
            <div class="space-y-6">
                <!-- Accesos rápidos -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-bolt mr-2 text-yellow-500"></i>
                        Accesos Rápidos
                    </h3>
                    <div class="space-y-3">
                        <button class="w-full text-left p-3 bg-gray-50 hover:bg-blue-50 rounded-lg transition duration-200 border border-gray-200 hover:border-blue-300" onclick="quickAction('restart_computer')">
                            <i class="fas fa-power-off mr-3 text-blue-600"></i>
                            <span class="font-medium">Reiniciar Computadora</span>
                            <p class="text-sm text-gray-600 mt-1">Guía paso a paso para reiniciar</p>
                        </button>
                        
                        <button class="w-full text-left p-3 bg-gray-50 hover:bg-green-50 rounded-lg transition duration-200 border border-gray-200 hover:border-green-300" onclick="quickAction('check_internet')">
                            <i class="fas fa-wifi mr-3 text-green-600"></i>
                            <span class="font-medium">Verificar Internet</span>
                            <p class="text-sm text-gray-600 mt-1">Diagnosticar problemas de conexión</p>
                        </button>
                        
                        <button class="w-full text-left p-3 bg-gray-50 hover:bg-purple-50 rounded-lg transition duration-200 border border-gray-200 hover:border-purple-300" onclick="quickAction('contact_it')">
                            <i class="fas fa-phone mr-3 text-purple-600"></i>
                            <span class="font-medium">Contactar IT</span>
                            <p class="text-sm text-gray-600 mt-1">Hablar directamente con soporte</p>
                        </button>
                    </div>
                </div>

                <!-- Problemas comunes -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-fire mr-2 text-red-500"></i>
                        Problemas Comunes
                    </h3>
                    <div class="space-y-2">
                        @if(isset($stats['categories_popular']) && count($stats['categories_popular']) > 0)
                            @foreach($stats['categories_popular'] as $category)
                                <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                    <span class="text-sm capitalize">{{ ucfirst($category->problem_category) }}</span>
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">{{ $category->count }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-600 text-sm">No hay datos disponibles aún</p>
                        @endif
                    </div>
                </div>

                <!-- Horarios de soporte -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-clock mr-2 text-blue-500"></i>
                        Horarios de Soporte
                    </h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Lunes - Viernes:</span>
                            <span class="font-medium">7:30 AM - 3:00 PM</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Emergencias:</span>
                            <span class="font-medium text-red-600">24/7</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts específicos para esta página -->
@push('scripts')
<script>
let currentSessionId = null;
let currentStep = 'categories';
let currentCategory = null;

document.addEventListener('DOMContentLoaded', function() {
    // Generar nuevo session ID
    currentSessionId = generateSessionId();
    
    // Cargar categorías iniciales
    loadCategories();
    
    // Event listeners
    document.getElementById('main-menu').addEventListener('click', showMainMenu);
    document.getElementById('restart-chat').addEventListener('click', restartChat);
    document.getElementById('escalate-to-it').addEventListener('click', escalateToIT);
    document.getElementById('mark-resolved').addEventListener('click', markAsResolved);
});

function generateSessionId() {
    return 'tech_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
}

function getCurrentCategory() {
    return currentCategory;
}

function loadCategories() {
    fetch('{{ route("tech-support.interact") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            type: 'start',
            session_id: currentSessionId
        })
    })
    .then(response => response.json())
    .then(data => {
        // Primero agregar mensaje del bot si no existe
        setTimeout(() => {
            displayCategories(data.categories);
        }, 300);
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Error al cargar las categorías');
    });
}

function displayCategories(categories) {
    const chatContainer = document.getElementById('tech-support-chat');
    
    // Limpiar opciones anteriores si existen
    const existingOptions = document.getElementById('current-options');
    if (existingOptions) {
        existingOptions.remove();
    }
    
    // Crear un nuevo div para las categorías
    const optionsDiv = document.createElement('div');
    optionsDiv.className = 'grid grid-cols-1 md:grid-cols-2 gap-3 mt-4 mb-4';
    optionsDiv.id = 'current-options';
    
    categories.forEach(category => {
        const button = document.createElement('button');
        button.className = `p-4 bg-${category.color}-50 hover:bg-${category.color}-100 border border-${category.color}-200 hover:border-${category.color}-300 rounded-lg text-left transition duration-200`;
        button.innerHTML = `
            <div class="flex items-center">
                <span class="text-2xl mr-3">${category.icon}</span>
                <div>
                    <div class="font-semibold text-gray-800">${category.title}</div>
                    <div class="text-sm text-gray-600">${category.description}</div>
                </div>
            </div>
        `;
        button.onclick = () => selectCategory(category.id);
        optionsDiv.appendChild(button);
    });
    
    // Agregar las categorías al chat
    chatContainer.appendChild(optionsDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function selectCategory(categoryId) {
    currentCategory = categoryId; // Guardar la categoría actual
    addMessageToChat('user', `Seleccioné: ${categoryId}`);
    
    fetch('{{ route("tech-support.interact") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            type: 'category_selected',
            category: categoryId,
            session_id: currentSessionId
        })
    })
    .then(response => response.json())
    .then(data => {
        // Primero agregar el mensaje del bot
        addMessageToChat('bot', `Perfecto, veo que tienes problemas con ${categoryId}. ¿Cuál de estos describe mejor tu situación?`);
        
        // Después mostrar las opciones
        setTimeout(() => {
            displayProblems(data.problems);
        }, 500);
        
        currentStep = 'problems';
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Error al procesar la categoría');
    });
}

function displayProblems(problems) {
    const chatContainer = document.getElementById('tech-support-chat');
    
    // Crear un nuevo div para las opciones
    const optionsDiv = document.createElement('div');
    optionsDiv.className = 'grid grid-cols-1 md:grid-cols-1 gap-3 mt-4 mb-4';
    optionsDiv.id = 'current-options';
    
    problems.forEach(problem => {
        const button = document.createElement('button');
        button.className = 'p-4 bg-white hover:bg-blue-50 border border-gray-200 hover:border-blue-300 rounded-lg text-left transition duration-200 shadow-sm';
        button.innerHTML = `
            <div class="font-semibold text-gray-800">${problem.title}</div>
            <div class="text-sm text-gray-600 mt-1">${problem.description}</div>
        `;
        button.onclick = () => selectProblem(problem.id);
        optionsDiv.appendChild(button);
    });
    
    // Agregar las opciones al chat
    chatContainer.appendChild(optionsDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function selectProblem(problemId) {
    // Limpiar opciones anteriores
    const existingOptions = document.getElementById('current-options');
    if (existingOptions) {
        existingOptions.remove();
    }
    
    addMessageToChat('user', `Mi problema específico es: ${problemId}`);
    
    fetch('{{ route("tech-support.interact") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            type: 'problem_selected',
            problem_id: problemId,
            category: getCurrentCategory(),
            session_id: currentSessionId
        })
    })
    .then(response => response.json())
    .then(data => {
        // Primero mostrar la solución como mensaje del bot
        addMessageToChat('bot', data.solution.title);
        
        // Después mostrar la solución detallada
        setTimeout(() => {
            displaySolution(data.solution);
            showActionButtons();
        }, 500);
        
        currentStep = 'solution';
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Error al obtener la solución');
    });
}

function displaySolution(solution) {
    const chatContainer = document.getElementById('tech-support-chat');
    
    // Crear el div de la solución
    const solutionDiv = document.createElement('div');
    solutionDiv.className = 'flex items-start mb-4';
    solutionDiv.innerHTML = `
        <div class="bg-blue-100 p-3 rounded-full mr-3">
            <i class="fas fa-lightbulb text-blue-600"></i>
        </div>
        <div class="bg-white border border-gray-200 rounded-lg p-6 max-w-full shadow-sm">
            <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                💡 Solución paso a paso
                <span class="ml-auto text-sm font-normal bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                    ⏱️ ${solution.estimated_time}
                </span>
            </h4>
            <div class="solution-content">
                ${solution.content}
            </div>
            <div class="mt-4 flex justify-center">
                <span class="bg-${solution.priority === 'high' ? 'red' : solution.priority === 'medium' ? 'yellow' : 'green'}-100 text-${solution.priority === 'high' ? 'red' : solution.priority === 'medium' ? 'yellow' : 'green'}-800 px-4 py-2 rounded-full text-sm font-semibold">
                    🎯 Prioridad ${solution.priority === 'high' ? 'Alta' : solution.priority === 'medium' ? 'Media' : 'Baja'}
                </span>
            </div>
        </div>
    `;
    
    chatContainer.appendChild(solutionDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function showActionButtons() {
    document.getElementById('escalate-to-it').style.display = 'inline-block';
    document.getElementById('mark-resolved').style.display = 'inline-block';
}

function addMessageToChat(sender, message) {
    const chatContainer = document.getElementById('tech-support-chat');
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex items-start mb-4 ${sender === 'user' ? 'justify-end' : ''}`;
    
    if (sender === 'bot') {
        messageDiv.innerHTML = `
            <div class="bg-blue-100 p-3 rounded-full mr-3">
                <i class="fas fa-robot text-blue-600"></i>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm max-w-md">
                <p class="text-gray-800">${message}</p>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="bg-blue-500 rounded-lg p-4 shadow-sm max-w-md">
                <p class="text-white">${message}</p>
            </div>
        `;
    }
    
    chatContainer.appendChild(messageDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function showMainMenu() {
    // Limpiar opciones anteriores si existen
    const existingOptions = document.getElementById('current-options');
    if (existingOptions) {
        existingOptions.remove();
    }
    
    // Agregar mensaje del bot sobre el menú
    addMessageToChat('bot', '📋 **Menú Principal - ¿Qué necesitas hacer?**\n\nPuedes elegir una de estas opciones para una experiencia más rápida:');
    
    // Mostrar opciones del menú principal
    setTimeout(() => {
        displayMainMenuOptions();
    }, 300);
    
    // Ocultar botones de acción
    document.getElementById('escalate-to-it').style.display = 'none';
    document.getElementById('mark-resolved').style.display = 'none';
    
    currentStep = 'main_menu';
}

function displayMainMenuOptions() {
    const chatContainer = document.getElementById('tech-support-chat');
    
    // Crear un nuevo div para las opciones del menú
    const optionsDiv = document.createElement('div');
    optionsDiv.className = 'grid grid-cols-1 md:grid-cols-2 gap-3 mt-4 mb-4';
    optionsDiv.id = 'current-options';
    
    const menuOptions = [
        {
            id: 'solve_problem',
            title: '🔧 Resolver un Problema',
            description: 'Asistencia paso a paso para problemas técnicos',
            color: 'blue',
            icon: '🔧',
            action: () => {
                addMessageToChat('user', 'Quiero resolver un problema técnico');
                setTimeout(() => {
                    restartChat();
                }, 500);
            }
        },
        {
            id: 'quick_actions',
            title: '⚡ Acciones Rápidas',
            description: 'Soluciones inmediatas a problemas comunes',
            color: 'green',
            icon: '⚡',
            action: () => {
                addMessageToChat('user', 'Mostrar acciones rápidas');
                setTimeout(() => {
                    showQuickActionsMenu();
                }, 500);
            }
        },
        {
            id: 'contact_support',
            title: '📞 Contactar Soporte',
            description: 'Hablar directamente con el equipo de IT',
            color: 'purple',
            icon: '📞',
            action: () => {
                addMessageToChat('user', 'Quiero contactar con soporte');
                setTimeout(() => {
                    quickAction('contact_it');
                }, 500);
            }
        },
        {
            id: 'system_status',
            title: '📊 Estado del Sistema',
            description: 'Verificar el estado de servicios y conexiones',
            color: 'orange',
            icon: '📊',
            action: () => {
                addMessageToChat('user', 'Verificar estado del sistema');
                setTimeout(() => {
                    showSystemStatus();
                }, 500);
            }
        }
    ];
    
    menuOptions.forEach(option => {
        const button = document.createElement('button');
        button.className = `p-4 bg-${option.color}-50 hover:bg-${option.color}-100 border border-${option.color}-200 hover:border-${option.color}-300 rounded-lg text-left transition-all duration-200 transform hover:scale-105 shadow-sm hover:shadow-md`;
        button.innerHTML = `
            <div class="flex items-center">
                <span class="text-2xl mr-3">${option.icon}</span>
                <div>
                    <div class="font-semibold text-gray-800">${option.title}</div>
                    <div class="text-sm text-gray-600">${option.description}</div>
                </div>
            </div>
        `;
        button.onclick = option.action;
        optionsDiv.appendChild(button);
    });
    
    // Agregar las opciones al chat
    chatContainer.appendChild(optionsDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function showQuickActionsMenu() {
    addMessageToChat('bot', '⚡ **Acciones Rápidas Disponibles:**\n\nElige la acción que necesitas realizar:');
    
    const chatContainer = document.getElementById('tech-support-chat');
    
    const optionsDiv = document.createElement('div');
    optionsDiv.className = 'grid grid-cols-1 gap-3 mt-4 mb-4';
    optionsDiv.id = 'current-options';
    
    const quickActions = [
        { id: 'restart_computer', title: '🔄 Reiniciar Computadora', description: 'Guía paso a paso para reiniciar' },
        { id: 'check_internet', title: '🌐 Verificar Internet', description: 'Diagnosticar problemas de conexión' },
        { id: 'contact_it', title: '📞 Contactar IT', description: 'Información de contacto directa' }
    ];
    
    quickActions.forEach(action => {
        const button = document.createElement('button');
        button.className = 'p-3 bg-white hover:bg-gray-50 border border-gray-200 hover:border-blue-300 rounded-lg text-left transition duration-200 shadow-sm';
        button.innerHTML = `
            <div class="flex items-center">
                <span class="text-xl mr-3">${action.title.split(' ')[0]}</span>
                <div>
                    <div class="font-semibold text-gray-800">${action.title.substring(2)}</div>
                    <div class="text-sm text-gray-600">${action.description}</div>
                </div>
            </div>
        `;
        button.onclick = () => {
            addMessageToChat('user', `Seleccioné: ${action.title}`);
            quickAction(action.id);
        };
        optionsDiv.appendChild(button);
    });
    
    chatContainer.appendChild(optionsDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function showSystemStatus() {
    addMessageToChat('bot', `📊 **Estado del Sistema:**

🔍 **Verificaciones Básicas:**
• Conexión a Internet: ✅ Activa
• Servidor de Correo: ✅ Funcionando  
• Red Interna: ✅ Conectada
• Impresoras de Red: ⚠️ Verificando...

🕐 **Última Actualización:** ${new Date().toLocaleTimeString()}

💡 **Recomendaciones:**
• Si tienes problemas específicos, usa "Resolver un Problema"
• Para asistencia inmediata, contacta IT: 555-TECH

¿Necesitas verificar algo más específico?`);
    
    showActionButtons();
}

function restartChat() {
    currentSessionId = generateSessionId();
    currentStep = 'categories';
    currentCategory = null; // Limpiar categoría actual
    
    // Limpiar chat completamente
    document.getElementById('tech-support-chat').innerHTML = `
        <div class="flex items-start mb-4">
            <div class="bg-blue-100 p-3 rounded-full mr-3">
                <i class="fas fa-robot text-blue-600"></i>
            </div>
            <div class="bg-white rounded-lg p-4 shadow-sm max-w-md">
                <p class="text-gray-800">
                    ¡Hola! 👋 Soy tu asistente de soporte técnico. 
                    Estoy aquí para ayudarte a resolver cualquier problema que tengas.
                </p>
                <p class="text-gray-800 mt-2">
                    <strong>¿Con qué puedo ayudarte hoy?</strong>
                </p>
            </div>
        </div>
    `;
    
    // Ocultar botones de acción
    document.getElementById('escalate-to-it').style.display = 'none';
    document.getElementById('mark-resolved').style.display = 'none';
    
    // Recargar categorías después de un breve delay
    setTimeout(() => {
        loadCategories();
    }, 500);
}

function escalateToIT() {
    fetch('{{ route("tech-support.interact") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            type: 'escalate',
            session_id: currentSessionId,
            reason: 'Usuario solicitó escalamiento manual'
        })
    })
    .then(response => response.json())
    .then(data => {
        addMessageToChat('bot', data.message);
        setTimeout(() => {
            restartChat();
        }, 3000);
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Error al procesar la solicitud');
    });
}

function markAsResolved() {
    fetch('{{ route("tech-support.interact") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            type: 'mark_resolved',
            session_id: currentSessionId
        })
    })
    .then(response => response.json())
    .then(data => {
        addMessageToChat('bot', '¡Excelente! Me alegra saber que pudimos resolver tu problema. 😊\n\nSi tienes algún otro problema, estaré aquí para ayudarte.');
        setTimeout(() => {
            restartChat();
        }, 3000);
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Error al marcar como resuelto');
    });
}

function quickAction(action) {
    // Limpiar opciones anteriores
    const existingOptions = document.getElementById('current-options');
    if (existingOptions) {
        existingOptions.remove();
    }
    
    switch(action) {
        case 'restart_computer':
            addMessageToChat('bot', `**🔄 Cómo reiniciar tu computadora paso a paso:**

1. **Guarda tu trabajo**
   • Guarda todos los documentos abiertos (Ctrl + S)
   • Cierra todos los programas

2. **Reiniciar**
   • Click en el botón de Windows (esquina inferior izquierda)
   • Click en el ícono de encendido ⚡
   • Selecciona "Reiniciar"
   • Espera a que la computadora se reinicie completamente

3. **Después del reinicio**
   • Ingresa tu contraseña si te la pide
   • Espera a que cargue el escritorio
   • Tu computadora debería funcionar mejor

⏱️ **Tiempo estimado:** 3-5 minutos`);
            showActionButtons();
            break;
            
        case 'check_internet':
            addMessageToChat('bot', `**🌐 Verificar problemas de Internet:**

1. **Revisar conexión**
   • Mira la esquina inferior derecha de tu pantalla
   • ¿Ves el símbolo del WiFi 📶?
   • Si tiene una X roja, no estás conectado

2. **Reconectar WiFi**
   • Click en el símbolo de WiFi
   • Busca el nombre de tu red
   • Click en "Conectar"
   • Ingresa la contraseña si te la pide

3. **Probar navegación**
   • Abre tu navegador
   • Ve a google.com
   • Si carga, tu internet está funcionando

Si nada funciona, el problema puede ser del proveedor de internet.`);
            showActionButtons();
            break;
            
        case 'contact_it':
            addMessageToChat('bot', `**📞 Contactar al equipo de IT:**

**Teléfono directo:** 📞 555-TECH (555-8324)
**Email:** 📧 soporte@empresa.com
**Chat interno:** 💬 Disponible en el sistema

**Horarios:**
• Lunes - Viernes: 8:00 AM - 6:00 PM
• Sábados: 9:00 AM - 2:00 PM
• Emergencias: 24/7

**Antes de llamar, ten lista esta información:**
• Tu nombre completo
• Número de empleado
• Descripción del problema
• ¿Qué estabas haciendo cuando ocurrió?

¡El equipo de IT estará encantado de ayudarte! 😊`);
            break;
    }
}

function showError(message) {
    addMessageToChat('bot', `❌ ${message}. Por favor intenta nuevamente o contacta a IT.`);
}

// Funciones para búsqueda de empleados
document.addEventListener('DOMContentLoaded', function() {
    const employeeSearch = document.getElementById('employee-search');
    const documentSearch = document.getElementById('document-search');

    // Cargar tags dinámicos
    loadEmployeeTags();
    loadDocumentTags();

    // Búsqueda de empleados en tiempo real
    let employeeSearchTimeout;
    employeeSearch.addEventListener('input', function() {
        clearTimeout(employeeSearchTimeout);
        employeeSearchTimeout = setTimeout(() => {
            if (this.value.length >= 2) {
                searchEmployees(this.value);
            } else {
                showEmployeeDefaultMessage();
            }
        }, 300);
    });

    // Búsqueda de documentos en tiempo real
    let documentSearchTimeout;
    documentSearch.addEventListener('input', function() {
        clearTimeout(documentSearchTimeout);
        documentSearchTimeout = setTimeout(() => {
            if (this.value.length >= 2) {
                searchDocuments(this.value);
            } else {
                showDocumentDefaultMessage();
            }
        }, 300);
    });
});

function loadEmployeeTags() {
    fetch('/corporate-chat/employees/tags')
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al cargar tags');
        }
        return response.json();
    })
    .then(data => {
        const tagsContainer = document.getElementById('employee-tags');
        let html = '';
        
        // Mostrar TODOS los departamentos disponibles (no solo algunos principales)
        if (data.departments && data.departments.length > 0) {
            data.departments.slice(0, 8).forEach(dept => { // Mostrar hasta 8 departamentos
                const displayName = dept === 'Recursos Humanos' ? 'RRHH' : 
                                   dept === 'Administración y Finanzas' ? 'Admin y Finanzas' :
                                   dept.length > 15 ? dept.substring(0, 12) + '...' : dept;
                html += `
                    <button onclick="selectEmployeeTag('department', '${dept}')" class="employee-tag-btn px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm hover:bg-blue-200 transition-all duration-200" data-type="department" data-value="${dept}" title="${dept}">
                        ${displayName}
                    </button>
                `;
            });
        }
        
        // Agregar algunas posiciones principales si hay espacio
        if (data.positions && data.positions.length > 0) {
            const mainPositions = ['Dirección General', 'Administración y Finanzas', 'Jefe de Área'];
            mainPositions.forEach(pos => {
                if (data.positions.includes(pos)) {
                    const displayName = pos === 'Dirección General' ? 'Dirección' : 
                                       pos === 'Administración y Finanzas' ? 'Finanzas' : pos;
                    html += `
                        <button onclick="selectEmployeeTag('position', '${pos}')" class="employee-tag-btn px-3 py-1 bg-blue-200 text-blue-800 rounded-full text-sm hover:bg-blue-300 transition-all duration-200" data-type="position" data-value="${pos}" title="${pos}">
                            ${displayName}
                        </button>
                    `;
                }
            });
        }
        
        // Botón "Ver todos" mejorado
        html += `
            <button onclick="loadAllEmployees()" class="px-3 py-1 bg-blue-500 text-white rounded-full text-sm hover:bg-blue-600 transition-all duration-200 font-medium">
                <i class="fas fa-users mr-1"></i>Ver todos
            </button>
        `;
        
        tagsContainer.innerHTML = html;
    })
    .catch(error => {
        console.error('Error loading tags:', error);
        // Fallback a tags estáticos si falla
        const tagsContainer = document.getElementById('employee-tags');
        tagsContainer.innerHTML = `
            <button onclick="selectEmployeeTag('department', 'IT')" class="employee-tag-btn px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm hover:bg-blue-200 transition-all duration-200" data-type="department" data-value="IT">
                IT
            </button>
            <button onclick="selectEmployeeTag('department', 'Recursos Humanos')" class="employee-tag-btn px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm hover:bg-blue-200 transition-all duration-200" data-type="department" data-value="Recursos Humanos">
                RRHH
            </button>
            <button onclick="loadAllEmployees()" class="px-3 py-1 bg-blue-500 text-white rounded-full text-sm hover:bg-blue-600 transition-all duration-200 font-medium">
                <i class="fas fa-users mr-1"></i>Ver todos
            </button>
        `;
    });
}

function loadDocumentTags() {
    fetch('/corporate-chat/documents/tags')
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al cargar categorías de documentos');
        }
        return response.json();
    })
    .then(data => {
        const tagsContainer = document.getElementById('document-tags-container');
        let html = '';
        
        // Mapear categorías a nombres y iconos amigables
        const categoryMap = {
            'contexto_planificacion': { name: 'Contexto y Planificación', icon: 'fas fa-calendar-alt' },
            'procedimientos_normativos': { name: 'Políticas y Normas', icon: 'fas fa-gavel' },
            'procedimientos_operativos': { name: 'Procedimientos Operativos', icon: 'fas fa-cogs' },
            'mejora_continua': { name: 'Mejora Continua', icon: 'fas fa-chart-line' },
            'general': { name: 'General', icon: 'fas fa-folder' }
        };
        
        // Crear botones para cada categoría
        data.categories.forEach(category => {
            const categoryInfo = categoryMap[category] || { name: category, icon: 'fas fa-file' };
            html += `
                <button onclick="selectDocumentCategory('${category}')" class="document-category-btn w-full text-left p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-all duration-200 border border-green-200" data-category="${category}">
                    <i class="${categoryInfo.icon} mr-2 text-green-600"></i>
                    <span class="font-medium">${categoryInfo.name}</span>
                </button>
            `;
        });
        
        tagsContainer.innerHTML = html;
    })
    .catch(error => {
        console.error('Error loading document categories:', error);
        // Fallback a categorías estáticas si falla
        const tagsContainer = document.getElementById('document-tags-container');
        tagsContainer.innerHTML = `
            <button onclick="selectDocumentCategory('general')" class="document-category-btn w-full text-left p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-all duration-200 border border-green-200" data-category="general">
                <i class="fas fa-folder mr-2 text-green-600"></i>
                <span class="font-medium">General</span>
            </button>
        `;
    });
}

function searchEmployees(query) {
    const resultsContainer = document.getElementById('employee-results');
    resultsContainer.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-blue-500"></i> Buscando...</div>';

    fetch('/corporate-chat/employees/search?' + new URLSearchParams({
        search: query
    }))
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        console.log('Employees search result:', data); // Debug
        displayEmployeeResults(data.employees || []);
    })
    .catch(error => {
        console.error('Error:', error);
        resultsContainer.innerHTML = '<div class="text-center text-red-500 py-4">Error al buscar empleados. Por favor, intenta de nuevo.</div>';
    });
}

function selectEmployeeTag(type, value) {
    // Remover estado activo de todos los botones de empleados
    document.querySelectorAll('.employee-tag-btn').forEach(btn => {
        const btnType = btn.getAttribute('data-type');
        
        if (btnType === 'department') {
            btn.classList.remove('bg-blue-300', 'text-blue-900');
            btn.classList.add('bg-blue-100', 'text-blue-700');
        } else if (btnType === 'position') {
            btn.classList.remove('bg-blue-400', 'text-blue-900');
            btn.classList.add('bg-blue-200', 'text-blue-800');
        }
    });
    
    // Activar el botón seleccionado
    const selectedBtn = document.querySelector(`[data-type="${type}"][data-value="${value}"]`);
    if (selectedBtn) {
        if (type === 'department') {
            selectedBtn.classList.remove('bg-blue-100', 'text-blue-700');
            selectedBtn.classList.add('bg-blue-300', 'text-blue-900');
        } else if (type === 'position') {
            selectedBtn.classList.remove('bg-blue-200', 'text-blue-800');
            selectedBtn.classList.add('bg-blue-400', 'text-blue-900');
        }
    }
    
    // Ejecutar la búsqueda
    searchEmployeesByType(type, value);
}

function searchEmployeesByType(type, value) {
    const resultsContainer = document.getElementById('employee-results');
    resultsContainer.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-blue-500"></i> Buscando...</div>';

    const params = {};
    params[type] = value;

    fetch('/corporate-chat/employees/search?' + new URLSearchParams(params))
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        console.log('Employees by type result:', data); // Debug
        displayEmployeeResults(data.employees || []);
    })
    .catch(error => {
        console.error('Error:', error);
        resultsContainer.innerHTML = '<div class="text-center text-red-500 py-4">Error al buscar empleados. Por favor, intenta de nuevo.</div>';
    });
}

function loadAllEmployees() {
    const resultsContainer = document.getElementById('employee-results');
    resultsContainer.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-blue-500"></i> Cargando todos los empleados...</div>';

    fetch('/corporate-chat/employees/search')
    .then(response => response.json())
    .then(data => {
        console.log('All employees data:', data); // Debug
        
        if (!data.employees || data.employees.length === 0) {
            resultsContainer.innerHTML = '<div class="text-center text-gray-500 py-8">No se encontraron empleados</div>';
            return;
        }

        // Mostrar todos los empleados en una lista completa
        let html = `
            <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                <h4 class="font-semibold text-blue-800 flex items-center">
                    <i class="fas fa-users mr-2"></i>
                    Directorio Completo de Empleados (${data.employees.length} total)
                </h4>
                <p class="text-sm text-blue-600 mt-1">Mostrando todos los empleados registrados en el sistema</p>
            </div>
            <div class="space-y-3">
        `;

        // Ordenar empleados por departamento y luego por nombre
        const sortedEmployees = data.employees.sort((a, b) => {
            const deptA = a.department || 'ZZ Sin departamento';
            const deptB = b.department || 'ZZ Sin departamento';
            if (deptA !== deptB) {
                return deptA.localeCompare(deptB);
            }
            return (a.full_name || '').localeCompare(b.full_name || '');
        });

        let currentDept = '';
        sortedEmployees.forEach(emp => {
            const empDept = emp.department || 'Sin departamento';
            
            // Agregar separador de departamento si cambió
            if (empDept !== currentDept) {
                html += `
                    <div class="bg-blue-100 px-3 py-2 rounded-lg mt-4 first:mt-0">
                        <h5 class="font-medium text-blue-700 flex items-center">
                            <i class="fas fa-building mr-2 text-blue-600"></i>
                            ${empDept}
                        </h5>
                    </div>
                `;
                currentDept = empDept;
            }

            html += `
                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors ml-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">${emp.full_name || 'Nombre no disponible'}</h4>
                            <p class="text-sm text-gray-600">${emp.position || 'Cargo no especificado'}</p>
                            ${emp.location ? `<p class="text-xs text-gray-500 mt-1"><i class="fas fa-map-marker-alt mr-1"></i>${emp.location}</p>` : ''}
                        </div>
                        <div class="text-right">
                            ${emp.email ? `<a href="mailto:${emp.email}" class="text-blue-600 hover:text-blue-800 text-sm block"><i class="fas fa-envelope mr-1"></i>${emp.email}</a>` : ''}
                            ${emp.phone ? `<a href="tel:${emp.phone}" class="text-green-600 hover:text-green-800 text-sm block mt-1"><i class="fas fa-phone mr-1"></i>${emp.phone}</a>` : ''}
                            ${emp.extension ? `<div class="text-gray-600 text-xs mt-1">Ext: ${emp.extension}</div>` : ''}
                            ${emp.employee_id ? `<div class="text-gray-500 text-xs mt-1">ID: ${emp.employee_id}</div>` : ''}
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';

        resultsContainer.innerHTML = html;
    })
    .catch(error => {
        console.error('Error:', error);
        resultsContainer.innerHTML = '<div class="text-center text-red-500 py-4">Error al cargar empleados. Por favor, intenta de nuevo.</div>';
    });
}

function loadAllDepartments() {
    const resultsContainer = document.getElementById('employee-results');
    resultsContainer.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-blue-500"></i> Cargando departamentos...</div>';

    fetch('/corporate-chat/employees/search')
    .then(response => response.json())
    .then(data => {
        console.log('Response data:', data); // Debug
        
        if (!data.employees || data.employees.length === 0) {
            resultsContainer.innerHTML = '<div class="text-center text-gray-500 py-8">No se encontraron empleados</div>';
            return;
        }

        // Agrupar por departamentos
        const byDepartment = {};
        data.employees.forEach(emp => {
            const dept = emp.department || 'Sin departamento';
            if (!byDepartment[dept]) {
                byDepartment[dept] = [];
            }
            byDepartment[dept].push(emp);
        });

        let html = '<div class="space-y-3">';
        Object.keys(byDepartment).sort().forEach(dept => {
            html += `
                <div class="border rounded-lg p-3">
                    <h4 class="font-semibold text-gray-800 mb-2 flex items-center justify-between">
                        <span>${dept}</span>
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">${byDepartment[dept].length}</span>
                    </h4>
                    <div class="space-y-1">
                        ${byDepartment[dept].slice(0, 3).map(emp => `
                            <div class="text-sm text-gray-600 border-b border-gray-100 pb-1">
                                <strong>${emp.full_name || 'Nombre no disponible'}</strong> - ${emp.position || 'Cargo no especificado'}
                                ${emp.email ? `<br><a href="mailto:${emp.email}" class="text-blue-600 text-xs">${emp.email}</a>` : ''}
                            </div>
                        `).join('')}
                        ${byDepartment[dept].length > 3 ? `
                            <button onclick="searchEmployeesByType('department', '${dept}')" class="text-xs text-blue-600 hover:text-blue-800 underline">
                                Ver todos los ${byDepartment[dept].length} empleados de ${dept}
                            </button>
                        ` : ''}
                    </div>
                </div>
            `;
        });
        html += '</div>';

        resultsContainer.innerHTML = html;
    })
    .catch(error => {
        console.error('Error:', error);
        resultsContainer.innerHTML = '<div class="text-center text-red-500 py-4">Error al cargar departamentos. Por favor, intenta de nuevo.</div>';
    });
}

function displayEmployeeResults(employees) {
    const resultsContainer = document.getElementById('employee-results');
    
    if (!employees || employees.length === 0) {
        resultsContainer.innerHTML = '<div class="text-center text-gray-500 py-8">No se encontraron empleados</div>';
        return;
    }

    let html = '<div class="space-y-3">';
    employees.forEach(emp => {
        html += `
            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">${emp.full_name || 'Nombre no disponible'}</h4>
                        <p class="text-sm text-gray-600">${emp.position || 'Cargo no especificado'}</p>
                        <p class="text-xs text-gray-500">${emp.department || 'Departamento no especificado'}</p>
                        ${emp.location ? `<p class="text-xs text-gray-500 mt-1"><i class="fas fa-map-marker-alt mr-1"></i>${emp.location}</p>` : ''}
                    </div>
                    <div class="text-right">
                        ${emp.email ? `<a href="mailto:${emp.email}" class="text-blue-600 hover:text-blue-800 text-sm block"><i class="fas fa-envelope mr-1"></i>${emp.email}</a>` : ''}
                        ${emp.phone ? `<a href="tel:${emp.phone}" class="text-green-600 hover:text-green-800 text-sm block mt-1"><i class="fas fa-phone mr-1"></i>${emp.phone}</a>` : ''}
                        ${emp.extension ? `<div class="text-gray-600 text-xs mt-1">Ext: ${emp.extension}</div>` : ''}
                        ${emp.employee_id ? `<div class="text-gray-500 text-xs mt-1">ID: ${emp.employee_id}</div>` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';

    resultsContainer.innerHTML = html;
}

function showEmployeeDefaultMessage() {
    document.getElementById('employee-results').innerHTML = `
        <div class="text-center text-gray-500 py-8">
            <i class="fas fa-search text-3xl mb-2"></i>
            <p>Usa el campo de búsqueda o los filtros para encontrar empleados</p>
        </div>
    `;
}

// Funciones para búsqueda de documentos
function searchDocuments(query) {
    const resultsContainer = document.getElementById('document-results');
    resultsContainer.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-green-500"></i> Buscando...</div>';

    fetch('/corporate-chat/documents/search?' + new URLSearchParams({
        search: query
    }))
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        console.log('Documents search result:', data); // Debug
        displayDocumentResults(data.documents || []);
    })
    .catch(error => {
        console.error('Error:', error);
        resultsContainer.innerHTML = '<div class="text-center text-red-500 py-4">Error al buscar documentos. Por favor, intenta de nuevo.</div>';
    });
}

function selectDocumentCategory(category) {
    // Remover estado activo de todos los botones de categoría
    document.querySelectorAll('.document-category-btn').forEach(btn => {
        btn.classList.remove('bg-green-200', 'border-green-400', 'shadow-md');
        btn.classList.add('bg-green-50', 'border-green-200');
        
        // Resetear color del ícono y texto
        const icon = btn.querySelector('i');
        const span = btn.querySelector('span');
        if (icon) icon.classList.remove('text-green-800');
        if (icon) icon.classList.add('text-green-600');
        if (span) span.classList.remove('text-green-800');
    });
    
    // Activar el botón seleccionado
    const selectedBtn = document.querySelector(`[data-category="${category}"]`);
    if (selectedBtn) {
        selectedBtn.classList.remove('bg-green-50', 'border-green-200');
        selectedBtn.classList.add('bg-green-200', 'border-green-400', 'shadow-md');
        
        // Cambiar color del ícono y texto
        const icon = selectedBtn.querySelector('i');
        const span = selectedBtn.querySelector('span');
        if (icon) {
            icon.classList.remove('text-green-600');
            icon.classList.add('text-green-800');
        }
        if (span) {
            span.classList.add('text-green-800');
        }
    }
    
    // Ejecutar la búsqueda
    searchDocumentsByCategory(category);
}

function searchDocumentsByCategory(category) {
    const resultsContainer = document.getElementById('document-results');
    resultsContainer.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-green-500"></i> Buscando...</div>';

    fetch('/corporate-chat/documents/search?' + new URLSearchParams({
        category: category
    }))
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        console.log('Documents by category result:', data); // Debug
        displayDocumentResults(data.documents || []);
    })
    .catch(error => {
        console.error('Error:', error);
        resultsContainer.innerHTML = '<div class="text-center text-red-500 py-4">Error al buscar documentos. Por favor, intenta de nuevo.</div>';
    });
}

function displayDocumentResults(documents) {
    const resultsContainer = document.getElementById('document-results');
    
    if (!documents || documents.length === 0) {
        resultsContainer.innerHTML = '<div class="text-center text-gray-500 py-8">No se encontraron documentos</div>';
        return;
    }

    let html = '<div class="space-y-3">';
    documents.forEach(doc => {
        const categoryName = getCategoryDisplayName(doc.category);
        html += `
            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">${doc.title || 'Documento sin título'}</h4>
                        ${doc.description ? `<p class="text-sm text-gray-600 mt-1">${doc.description}</p>` : ''}
                        <div class="flex items-center mt-2 space-x-2">
                            <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">${categoryName}</span>
                            ${doc.type ? `<span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">${doc.type}</span>` : ''}
                        </div>
                    </div>
                    <div class="ml-4">
                        ${doc.external_url ? `
                            <a href="${doc.external_url}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                <i class="fas fa-external-link-alt mr-1"></i>Ver
                            </a>
                        ` : `
                            <span class="bg-gray-300 text-gray-600 px-3 py-1 rounded text-sm">Sin enlace</span>
                        `}
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';

    resultsContainer.innerHTML = html;
}

function getCategoryDisplayName(category) {
    const names = {
        'contexto_planificacion': 'Contexto de Planificación',
        'procedimientos_normativos': 'Procedimientos Normativos',
        'procedimientos_operativos': 'Procedimientos Operativos',
        'mejora_continua': 'Mejora Continua',
        'general': 'General'
    };
    return names[category] || 'Sin categoría';
}

function showDocumentDefaultMessage() {
    document.getElementById('document-results').innerHTML = `
        <div class="text-center text-gray-500 py-8">
            <i class="fas fa-folder-open text-3xl mb-2"></i>
            <p>Selecciona una categoría o busca documentos específicos</p>
        </div>
    `;
}
</script>
@endpush
@endsection