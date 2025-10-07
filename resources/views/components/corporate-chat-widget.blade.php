<!-- Chat Flotante Corporativo -->
<div id="corporate-chat" class="fixed bottom-4 right-4 z-50">
   
    <!-- Botón para abrir/cerrar chat -->
    <button
        id="chat-toggle-btn"
        class="bg-gradient-to-r from-red-600 to-yellow-500 hover:from-red-700 hover:to-yellow-600 text-white rounded-full p-4 shadow-lg transition-all duration-300 transform hover:scale-110 focus:outline-none focus:ring-4 focus:ring-red-300"
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
        <div class="bg-gradient-to-r from-red-600 to-yellow-500 text-white p-4 rounded-t-lg flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-yellow-400 rounded-full flex items-center justify-center mr-3 shadow-lg">
                    <span class="text-sm font-bold">🏢</span>
                </div>
                <div>
                    <h3 class="font-semibold text-sm">Asistente Corporativo</h3>
                    <p class="text-xs text-red-100">En línea • Respuesta inmediata</p>
                </div>
            </div>
            <button onclick="toggleCorporateChat()" class="text-red-100 hover:text-white transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Área de mensajes -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50 dark:bg-gray-900">
            <!-- Mensaje de bienvenida -->
            <div class="flex items-start">
                <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-yellow-400 rounded-full flex items-center justify-center mr-2 flex-shrink-0 shadow-lg">
                    <span class="text-white text-xs">🏢</span>
                </div>
                <div class="bg-white dark:bg-gray-700 rounded-lg p-3 max-w-xs shadow-sm border border-red-100">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        ¡Hola! Soy tu asistente de información corporativa. 
                        Puedo ayudarte con empleados, documentos y soporte técnico. 
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
                <button onclick="sendQuickMessage('Soporte técnico')" class="text-xs bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-full hover:bg-gray-300 dark:hover:bg-gray-500">
                    � Soporte
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
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.5003 12H5.41872M5.24634 12.7972L4.24158 15.7986C3.69128 17.4424 3.41613 18.2643 3.61359 18.7704C3.78506 19.21 4.15335 19.5432 4.6078 19.6701C5.13111 19.8161 5.92151 19.4604 7.50231 18.7491L17.6367 14.1886C19.1797 13.4942 19.9512 13.1471 20.1896 12.6648C20.3968 12.2458 20.3968 11.7541 20.1896 11.3351C19.9512 10.8529 19.1797 10.5057 17.6367 9.81135L7.48483 5.24303C5.90879 4.53382 5.12078 4.17921 4.59799 4.32468C4.14397 4.45101 3.77572 4.78336 3.60365 5.22209C3.40551 5.72728 3.67772 6.54741 4.22215 8.18767L5.24829 11.2793C5.34179 11.561 5.38855 11.7019 5.407 11.8459C5.42338 11.9738 5.42321 12.1032 5.40651 12.231C5.38768 12.375 5.34057 12.5157 5.24634 12.7972Z" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
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

async function sendChatMessage(buttonAction = null, buttonValue = null) {
    const input = document.getElementById('chat-input');
    const message = buttonAction ? `Button: ${buttonAction}` : input.value.trim();
    
    if (!message && !buttonAction) return;
    
    // Agregar mensaje del usuario (solo si no es una acción de botón)
    if (!buttonAction) {
        addMessageToChat(message, 'user');
        input.value = '';
    }
    
    // Mostrar indicador de escritura
    showTypingIndicator();
    
    try {
        // Preparar contexto con información del botón si aplica
        const requestContext = { ...chatContext };
        if (buttonAction) {
            requestContext.action = buttonAction;
            requestContext.value = buttonValue;
        }
        
        const response = await fetch('/corporate-chat/message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                message: buttonAction ? buttonValue || buttonAction : message,
                context: requestContext
            })
        });
        
        const data = await response.json();
        
        // Ocultar indicador de escritura
        hideTypingIndicator();
        
        // Agregar respuesta del asistente
        addMessageToChat(data.message, 'assistant', data.buttons);
        
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

function addMessageToChat(message, sender, buttons = null) {
    const messagesContainer = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex items-start mb-3';
    
    if (sender === 'user') {
        messageDiv.innerHTML = `
            <div class="flex justify-end w-full">
                <div class="bg-gradient-to-r from-red-600 to-yellow-500 text-white rounded-lg p-3 max-w-xs shadow-sm ml-auto">
                    <p class="text-sm">${escapeHtml(message)}</p>
                </div>
                <div class="w-8 h-8 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full flex items-center justify-center ml-2 flex-shrink-0 shadow-lg">
                    <span class="text-white text-xs">👤</span>
                </div>
            </div>
        `;
    } else {
        let buttonsHTML = '';
        if (buttons && buttons.length > 0) {
            buttonsHTML = `
                <div class="mt-3 space-y-1">
                    ${buttons.map(button => `
                        <button 
                            onclick="sendChatMessage('${button.action}', '${button.value || ''}')"
                            class="block w-full text-left text-sm bg-gradient-to-r from-red-50 to-yellow-50 hover:from-red-100 hover:to-yellow-100 text-red-700 border border-red-200 rounded-lg p-2 transition-all duration-200 shadow-sm"
                            title="${button.description || ''}"
                        >
                            ${escapeHtml(button.text)}
                            ${button.description ? `<div class="text-xs text-red-500 mt-1">${escapeHtml(button.description)}</div>` : ''}
                        </button>
                    `).join('')}
                </div>
            `;
        }
        
        messageDiv.innerHTML = `
            <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-yellow-400 rounded-full flex items-center justify-center mr-2 flex-shrink-0 shadow-lg">
                <span class="text-white text-xs">🏢</span>
            </div>
            <div class="bg-white dark:bg-gray-700 rounded-lg p-3 max-w-xs shadow-sm border border-red-100">
                <p class="text-sm text-gray-700 dark:text-gray-300">${formatMessage(message)}</p>
                ${buttonsHTML}
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
        `<button onclick="sendQuickMessage('${escapeHtml(suggestion)}')" class="text-xs bg-gradient-to-r from-red-100 to-yellow-100 hover:from-red-200 hover:to-yellow-200 text-red-700 px-2 py-1 rounded-full transition-all duration-200 border border-red-200">
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
        .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 underline">$1</a>')
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