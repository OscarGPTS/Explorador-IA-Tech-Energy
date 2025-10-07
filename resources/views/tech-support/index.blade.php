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
                        <div class="flex space-x-3">
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
</script>
@endpush
@endsection