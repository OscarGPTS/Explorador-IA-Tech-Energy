<!-- filepath: c:\xampp\htdocs\Explorador-IA\resources\views\livewire\chat\chat-index.blade.php -->
<div class="flex flex-col h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Chat con IA</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Conversa con OpenAI GPT-3.5</p>
            </div>
            <button 
                wire:click="clearChat" 
                class="px-4 py-2 text-sm bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors"
                onclick="return confirm('¿Estás seguro de que quieres limpiar el chat?')"
            >
                Limpiar Chat
            </button>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="flex-1 overflow-y-auto p-6 space-y-4" id="messages-container">
        @forelse($messages as $msg)
            <div class="flex {{ $msg['emisor_id'] == auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs lg:max-w-md">
                    <!-- Nombre del emisor -->
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1 {{ $msg['emisor_id'] == auth()->id() ? 'text-right' : 'text-left' }}">
                        {{ $msg['emisor_id'] == auth()->id() ? 'Tú' : 'IA Assistant' }}
                    </div>
                    
                    <!-- Mensaje -->
                    <div class="px-4 py-3 rounded-lg {{ $msg['emisor_id'] == auth()->id() ? 'bg-blue-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700' }}">
                        <p class="text-sm whitespace-pre-wrap">{{ $msg['message'] }}</p>
                        <p class="text-xs mt-2 opacity-70">
                            {{ \Carbon\Carbon::parse($msg['created_at'])->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                <div class="mx-auto w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <p class="text-lg font-medium mb-2">¡Inicia una conversación!</p>
                <p>Envía tu primer mensaje para comenzar a chatear con la IA.</p>
            </div>
        @endforelse

        @if($isLoading)
            <div class="flex justify-start">
                <div class="max-w-xs lg:max-w-md">
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                        IA Assistant
                    </div>
                    <div class="px-4 py-3 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-2">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-500"></div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">IA está escribiendo...</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Message Input -->
    <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-6 py-4">
        <form wire:submit.prevent="sendMessage" class="flex space-x-4">
            <div class="flex-1">
                <input 
                    type="text" 
                    wire:model.live="message"
                    placeholder="Escribe tu mensaje..."
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-300 {{ $errorMessage ? 'border-red-500 focus:ring-red-500' : '' }}"
                    maxlength="1000"
                    @keydown.enter="$wire.sendMessage()"
                >
                
                <!-- Mensaje de error -->
                @if($errorMessage)
                    <p class="text-red-500 text-xs mt-1">{{ $errorMessage }}</p>
                @endif
            </div>
            <button 
                type="submit" 
                class="px-6 py-3 bg-blue-500 hover:bg-blue-600 disabled:bg-blue-400 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-colors flex items-center justify-center min-w-[100px]"
                wire:loading.attr="disabled"
                wire:target="sendMessage"
            >
                <div wire:loading.remove wire:target="sendMessage" class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Enviar
                </div>
                <div wire:loading wire:target="sendMessage" class="flex items-center">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                    Enviando...
                </div>
            </button>
        </form>
        <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
            Presiona Enter para enviar • Máximo 1000 caracteres
            @if(strlen($message) > 0)
                • Caracteres: {{ strlen($message) }}/1000
            @endif
        </div>
    </div>
</div>

@script
<script>
    // Auto-scroll to bottom when new messages arrive
    document.addEventListener('livewire:updated', () => {
        setTimeout(() => {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }, 100);
    });

    // Focus en el input después de enviar mensaje
    $wire.on('chatCleared', () => {
        setTimeout(() => {
            const input = document.querySelector('input[wire\\:model\\.live="message"]');
            if (input) {
                input.focus();
            }
        }, 100);
    });
</script>
@endscript