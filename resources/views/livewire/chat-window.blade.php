<div class="flex flex-col h-full p-4">
    <div id="messages" class="flex-1 overflow-y-auto mb-4" wire:poll.3s="loadMessages">
        @if (!$messages || $messages->count() === 0)
            <p class="text-gray-500 text-center">No hay mensajes aún. ¡Empieza la conversación!</p>
        @else
            @foreach($messages as $m)
                <div class="mb-2">
                    @if($m->role === 'user')
                        <div class="text-right">
                            <div class="inline-block bg-blue-500 text-white p-2 rounded-lg max-w-xl">
                                {{ $m->message }}
                            </div>
                        </div>
                    @else
                        <div class="text-left">
                            <div class="inline-block bg-gray-200 p-2 rounded-lg max-w-xl">
                                {{ $m->message }}
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    <div class="flex items-center space-x-2">
        <input type="file" wire:model="attachment" />
        <input wire:model.defer="input" wire:keydown.enter="sendMessage" 
               class="flex-grow border p-2 rounded" placeholder="Escribe un mensaje" />
        <button wire:click="sendMessage" class="bg-blue-600 text-white px-4 py-2 rounded">
            Enviar
        </button>
    </div>

    <div wire:loading wire:target="sendMessage" class="mt-2 text-sm text-gray-500">
        Enviando...
    </div>

    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('messagesUpdated', () => {
                const el = document.getElementById('messages');
                if (el) { el.scrollTop = el.scrollHeight; }
            });
            // Primer scroll al cargar
            Livewire.emit('messagesUpdated');
        });
    </script>
</div>
