<!-- Chat Flotante Corporativo -->
<div id="corporate-chat-widget" class="fixed bottom-4 right-4 z-50">
    <!-- Botón para abrir/cerrar chat -->
    <button
        id="chat-toggle-btn"
        class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition-all duration-300 transform hover:scale-110 focus:outline-none focus:ring-4 focus:ring-blue-300"
        onclick="toggleCorporateChat()"
    >
        <svg id="chat-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <!-- Ventana del chat -->
    <div
        id="chat-window"
        class="hidden absolute bottom-16 right-0 bg-white dark:bg-gray-800 rounded-lg shadow-2xl border border-gray-200 dark:border-gray-700 w-80 h-[500px] flex flex-col"
    >
        <!-- Header del chat -->
        <div class="bg-blue-600 text-white p-4 rounded-t-lg flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                    <span class="text-sm font-bold">🏢</span>
                </div>
                <div>
                    <h3 class="font-semibold text-sm">Asistente Corporativo</h3>
                    <p class="text-xs text-blue-100">En línea • Respuesta inmediata</p>
                </div>
            </div>
            <button onclick="toggleCorporateChat()" class="text-blue-100 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Área de mensajes -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50 dark:bg-gray-900">
            <!-- Mensaje de bienvenida -->
            <div class="flex items-start">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                    <span class="text-white text-xs">🏢</span>
                </div>
                <div class="bg-white dark:bg-gray-700 rounded-lg p-3 max-w-xs shadow-sm">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        ¡Hola! Soy tu asistente de información corporativa. 
                        Puedo ayudarte con empleados, ubicaciones y documentos. 
                        ¿Qué necesitas?
                    </p>
                </div>
            </div>
        </div>

        <!-- Sugerencias rápidas -->
        <div id="chat-suggestions" class="px-4 py-2 border-t border-gray-200 dark:border-gray-600">
            <div class="flex flex-wrap gap-1">
                <button onclick="sendQuickMessage('Buscar empleado')" class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-full hover:bg-gray-300 dark:hover:bg-gray-500">
                    👤 Empleado
                </button>
                <button onclick="sendQuickMessage('Ver ubicaciones')" class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-full hover:bg-gray-300 dark:hover:bg-gray-500">
                    📍 Ubicaciones
                </button>
                <button onclick="sendQuickMessage('Encontrar documento')" class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-full hover:bg-gray-300 dark:hover:bg-gray-500">
                    📄 Documentos
                </button>
            </div>
        </div>

        <!-- Input del chat -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-600">
            <div class="flex items-center space-x-2">
                <input
                    type="text"
                    id="chat-input"
                    placeholder="Escribe tu mensaje..."
                    class="flex-1 border border-gray-300 dark:border-gray-600 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300"
                    onkeypress="handleChatKeyPress(event)"
                >
                <button
                    onclick="sendChatMessage()"
                    class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-2 transition-colors duration-200"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Indicador de escritura -->
        <div id="typing-indicator" class="hidden px-4 py-2 text-xs text-gray-500 italic">
            El asistente está escribiendo...
        </div>
    </div>
</div>

<script>
let chatContext = { step: 'initial' };
let chatIsOpen = false;

function toggleCorporateChat() {
    const chatWindow = document.getElementById('chat-window');
    const chatIcon = document.getElementById('chat-icon');
    const closeIcon = document.getElementById('close-icon');
    
    chatIsOpen = !chatIsOpen;
    
    if (chatIsOpen) {
        chatWindow.classList.remove('hidden');
        chatIcon.classList.add('hidden');
        closeIcon.classList.remove('hidden');
        document.getElementById('chat-input').focus();
    } else {
        chatWindow.classList.add('hidden');
        chatIcon.classList.remove('hidden');
        closeIcon.classList.add('hidden');
    }
}

function sendQuickMessage(message) {
    document.getElementById('chat-input').value = message;
    sendChatMessage();
}

function handleChatKeyPress(event) {
    if (event.key === 'Enter') {
        sendChatMessage();
    }
}

async function sendChatMessage() {
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Agregar mensaje del usuario
    addMessageToChat(message, 'user');
    input.value = '';
    
    // Mostrar indicador de escritura
    showTypingIndicator();
    
    try {
        const response = await fetch('/corporate-chat/message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                message: message,
                context: chatContext
            })
        });
        
        const data = await response.json();
        
        // Ocultar indicador de escritura
        hideTypingIndicator();
        
        // Agregar respuesta del asistente
        addMessageToChat(data.message, 'assistant');
        
        // Actualizar contexto
        if (data.context) {
            chatContext = data.context;
        }
        
        // Actualizar sugerencias
        if (data.suggestions) {
            updateSuggestions(data.suggestions);
        }
        
    } catch (error) {
        hideTypingIndicator();
        addMessageToChat('Lo siento, hubo un error al procesar tu mensaje. Por favor intenta de nuevo.', 'assistant');
        console.error('Error en chat corporativo:', error);
    }
}

function addMessageToChat(message, sender) {
    const messagesContainer = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex items-start';
    
    if (sender === 'user') {
        messageDiv.innerHTML = `
            <div class="flex justify-end w-full">
                <div class="bg-blue-600 text-white rounded-lg p-3 max-w-xs shadow-sm ml-auto">
                    <p class="text-sm">${escapeHtml(message)}</p>
                </div>
                <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center ml-2 flex-shrink-0">
                    <span class="text-white text-xs">👤</span>
                </div>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                <span class="text-white text-xs">🏢</span>
            </div>
            <div class="bg-white dark:bg-gray-700 rounded-lg p-3 max-w-xs shadow-sm">
                <p class="text-sm text-gray-700 dark:text-gray-300">${formatMessage(message)}</p>
            </div>
        `;
    }
    
    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function showTypingIndicator() {
    document.getElementById('typing-indicator').classList.remove('hidden');
}

function hideTypingIndicator() {
    document.getElementById('typing-indicator').classList.add('hidden');
}

function updateSuggestions(suggestions) {
    const suggestionsContainer = document.getElementById('chat-suggestions');
    const buttonsHTML = suggestions.map(suggestion => 
        `<button onclick="sendQuickMessage('${escapeHtml(suggestion)}')" class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-full hover:bg-gray-300 dark:hover:bg-gray-500">
            ${escapeHtml(suggestion)}
        </button>`
    ).join('');
    
    suggestionsContainer.innerHTML = `<div class="flex flex-wrap gap-1">${buttonsHTML}</div>`;
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

function formatMessage(message) {
    // Convertir markdown básico a HTML y manejar saltos de línea
    return escapeHtml(message)
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/\n/g, '<br>')
        .replace(/•/g, '&bullet;');
}

// Opcional: Auto-abrir chat en ciertas páginas
document.addEventListener('DOMContentLoaded', function() {
    // Puedes agregar lógica aquí para auto-abrir el chat en páginas específicas
});
</script>

<style>
/* Animaciones adicionales para el chat */
#corporate-chat-widget #chat-window {
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Scroll personalizado para el área de mensajes */
#chat-messages::-webkit-scrollbar {
    width: 4px;
}

#chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
}

#chat-messages::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

#chat-messages::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}
</style>