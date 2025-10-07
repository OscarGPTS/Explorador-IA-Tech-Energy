<!-- filepath: c:\xampp\htdocs\Explorador-IA\resources\views\livewire\chat\chat-index.blade.php -->
<div>
    <!-- Estilos personalizados para el chat -->
    <style>
        .chat-header {
            background: linear-gradient(135deg, #DC2626 0%, #FBBF24 100%);
            position: relative;
            overflow: hidden;
        }

        .chat-title {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .agent-indicator {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .agent-indicator:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.2) 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .chat-button {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .chat-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .back-button {
            background: rgba(255, 255, 255, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1) rotate(-5deg);
        }

        .clear-chat-button {
            background: linear-gradient(135deg, #EF4444 0%, #F87171 100%);
            transition: all 0.3s ease;
        }

        .clear-chat-button:hover {
            background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
        }

        .send-button {
            background: linear-gradient(135deg, #DC2626 0%, #FBBF24 100%);
            transition: all 0.3s ease;
        }

        .send-button:hover {
            background: linear-gradient(135deg, #B91C1C 0%, #F59E0B 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.25);
        }

        .send-button:disabled {
            background: #D1D5DB !important;
            transform: none !important;
            box-shadow: none !important;
        }

        .fade-in-header {
            animation: fadeInDown 0.8s ease-out forwards;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Estilos para mensajes del chat */
        .message-user {
            background: #DC2626;
            color: white;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.15);
            border: none;
        }

        .message-agent {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            color: #374151;
        }

        .message-user .message-time {
            color: rgba(255, 255, 255, 0.8);
        }

        .message-agent .message-time {
            color: rgba(55, 65, 81, 0.6);
        }

        .message-container {
            animation: slideIn 0.3s ease-out;
            transition: all 0.2s ease;
        }

        .message-container:hover {
            transform: translateY(-2px);
        }

        .message-user-container:hover .message-user {
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.25);
        }

        .message-agent-container:hover .message-agent {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .sender-label {
            font-weight: 600;
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
            opacity: 0.8;
        }

        .sender-label-user {
            color: #DC2626;
        }

        .sender-label-agent {
            color: #6B7280;
        }
    </style>

    <div class="flex flex-col bg-white dark:bg-gray-900 top-0 left-0 right-0 bottom-0 mt-2">
    
    <!-- Header con degradado moderno -->
    <div style="background: linear-gradient(135deg, #DC2626 0%, #FBBF24 100%);" class="border-b border-gray-200 dark:border-gray-700 px-4 py-2 shadow-xl">
        <div class="flex justify-between items-center mb-1">
            <div class="flex flex-1 items-center space-x-4">
                
                <a href="/" class="p-2 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-sm transition-all border border-white/30">
                        <svg width="20px" height="20px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                            <path fill="white" d="M224 480h640a32 32 0 1 1 0 64H224a32 32 0 0 1 0-64z"/>
                            <path fill="white" d="m237.248 512 265.408 265.344a32 32 0 0 1-45.312 45.312l-288-288a32 32 0 0 1 0-45.312l288-288a32 32 0 1 1 45.312 45.312L237.248 512z"/>
                        </svg>
                    </a>

                <div class="flex items-center space-x-3">
                    
                    <div>
                        <h1 class="text-3xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                      d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.847a4.5 4.5 0 003.09 3.09L15.75 12l-2.847.813a4.5 4.5 0 00-3.09 3.091zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                            </svg>
                            Buscador Inteligente
                        </h1>
                        <p class="text-white/90 text-xs">Encuentra información corporativa al instante con IA</p>
                    </div>
                </div>
            </div>
            
            <!-- Botón limpiar chat -->
            <button 
                wire:click="clearChat" 
                class="send-button px-4 py-2 text-white rounded-full font-medium shadow-lg text-sm"
                onclick="return confirm('¿Estás seguro de que quieres limpiar el chat?')"
            >
                <div class="flex items-center">
                   <svg fill="#FFFFFF"  width="16px" height="16px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M5.755,20.283,4,8H20L18.245,20.283A2,2,0,0,1,16.265,22H7.735A2,2,0,0,1,5.755,20.283ZM21,4H16V3a1,1,0,0,0-1-1H9A1,1,0,0,0,8,3V4H3A1,1,0,0,0,3,6H21a1,1,0,0,0,0-2Z"/></svg>
                   <span class="ml-2">Limpiar</span> 
                </div>
            </button>
        </div>
        <br>
        <!-- Indicador de agente actual -->
        @if($currentAgentConfig)
        <div class="flex items-center space-x-3 pb-1">
            <div class="flex items-center space-x-2 px-3 py-1 rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 shadow-lg transition-all duration-300 hover:bg-white/30">
                <div class="text-sm">{{ $currentAgentConfig['agent_role']['icon'] ?? '🤖' }}</div>
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-white">
                        {{ $currentAgentConfig['name'] }}
                    </span>
                    @if($currentAgentConfig['is_user_setting'] && $currentAgentConfig['custom_prompt'])
                    <span class="text-xl text-white/80">Personalizado</span>
                    @endif
                </div>
            </div>
            
            <!-- Botón para cambiar agente -->
            <button 
                wire:click="toggleAgentSelector"
                class="flex items-center space-x-2 px-3 py-1 text-white hover:text-white bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg font-medium border border-white/30 shadow-lg text-sm transition-all"
                title="Cambiar agente"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                <span>Cambiar</span>
            </button>
        </div>
        @endif
        
        <!-- Selector de agente -->
        @if($showAgentSelector)
        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Seleccionar Agente</h3>
            
            <!-- Roles del sistema -->
            <div class="mb-4">
                <h4 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Roles Predefinidos</h4>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
                    @foreach($availableAgentRoles as $role)
                    <button 
                        wire:click="changeAgent('role', {{ $role['id'] }})"
                        class="flex items-center space-x-2 p-2 text-left hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg border border-gray-200 dark:border-gray-600 transition-colors {{ $currentAgentConfig && !$currentAgentConfig['is_user_setting'] && $currentAgentConfig['agent_role']['id'] == $role['id'] ? 'bg-blue-100 dark:bg-blue-900/40 border-blue-300 dark:border-blue-500' : '' }}"
                    >
                        <span class="text-lg">{{ $role['icon'] }}</span>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $role['name'] }}</div>
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>
            
            <!-- Configuraciones personalizadas del usuario -->
            @if(count($userAgentSettings) > 0)
            <div>
                <h4 class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Mis Configuraciones</h4>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
                    @foreach($userAgentSettings as $setting)
                    <button 
                        wire:click="changeAgent('setting', {{ $setting['id'] }})"
                        class="flex items-center space-x-2 p-2 text-left hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg border border-gray-200 dark:border-gray-600 transition-colors {{ $currentAgentConfig && $currentAgentConfig['is_user_setting'] && $currentAgentConfig['id'] == $setting['id'] ? 'bg-green-100 dark:bg-green-900/40 border-green-300 dark:border-green-500' : '' }}"
                    >
                        <span class="text-lg">{{ $setting['agent_role']['icon'] }}</span>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $setting['name'] }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $setting['agent_role']['name'] }}</div>
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
            
            <div class="mt-3 flex justify-end">
                <button 
                    wire:click="toggleAgentSelector"
                    class="px-3 py-1 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
                >
                    Cerrar
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- Messages Container -->
    <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-white dark:bg-gray-800" id="messages-container">
        @forelse($messages as $msg)
            <div class="flex {{ $msg['emisor_id'] == auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs lg:max-w-md {{ $msg['emisor_id'] == auth()->id() ? 'message-user-container' : 'message-agent-container' }}">
                    <!-- Nombre del emisor -->
                    <div class="sender-label {{ $msg['emisor_id'] == auth()->id() ? 'text-right sender-label-user' : 'text-left sender-label-agent' }}">
                        @if($msg['emisor_id'] == auth()->id())
                            <div class="flex items-center justify-end space-x-2">
                                <span>Tú</span>
                                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            </div>
                        @else
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <span>Agente GPT</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Mensaje -->
                    <div class="message-container px-5 py-4 rounded-2xl {{ $msg['emisor_id'] == auth()->id() ? 'message-user' : 'message-agent' }}">
                        <!-- Imágenes del mensaje -->
                        @if(!empty($msg['files']) && count($msg['files']) > 0)
                            <div class="mb-3">
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach($msg['files'] as $file)
                                        @if($file['is_image'])
                                            <div class="relative group">
                                                <img 
                                                    src="{{ $file['url'] }}" 
                                                    alt="{{ $file['name'] }}" 
                                                    class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                                    onclick="openImageModal('{{ $file['url'] }}', '{{ $file['name'] }}')"
                                                >
                                                <div class="absolute bottom-1 left-1 bg-black bg-opacity-50 text-white text-xs px-1 py-0.5 rounded">
                                                    {{ $file['size'] }}
                                                </div>
                                                <!-- Indicador de click para expandir -->
                                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black bg-opacity-20 rounded-lg">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Documentos no-imagen -->
                                            <div class="flex items-center p-3 bg-[#F9BE00] text-black rounded-lg">
                                                <!-- Icono según extensión del archivo -->
                                                <div class="flex-shrink-0 mr-3">
                                                    @php
                                                        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                                    @endphp
                                                    
                                                    @if($extension === 'pdf')
                                                        <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M4 18h12V6l-4-4H4v16zm-2 1V4c0-1.1.9-2 2-2h8l4 4v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2z"/>
                                                        </svg>
                                                    @elseif(in_array($extension, ['doc', 'docx']))
                                                        <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M4 18h12V6l-4-4H4v16zm-2 1V4c0-1.1.9-2 2-2h8l4 4v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2z"/>
                                                        </svg>
                                                    @elseif(in_array($extension, ['xls', 'xlsx']))
                                                        <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M4 18h12V6l-4-4H4v16zm-2 1V4c0-1.1.9-2 2-2h8l4 4v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2z"/>
                                                        </svg>
                                                    @elseif(in_array($extension, ['ppt', 'pptx']))
                                                        <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M4 18h12V6l-4-4H4v16zm-2 1V4c0-1.1.9-2 2-2h8l4 4v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2z"/>
                                                        </svg>
                                                    @elseif($extension === 'txt')
                                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="text-sm font-medium {{ $msg['emisor_id'] == auth()->id() ? 'text-white' : 'text-gray-900 dark:text-white' }} truncate">{{ $file['name'] }}</div>
                                                    <div class="text-xs {{ $msg['emisor_id'] == auth()->id() ? 'text-blue-100' : 'text-gray-500 dark:text-gray-400' }}">{{ $file['size'] }} • {{ strtoupper($extension) }}</div>
                                                </div>
                                                <!-- Botón de descarga -->
                                                <a href="{{ $file['url'] }}" download="{{ $file['name'] }}" class="flex-shrink-0 ml-2 {{ $msg['emisor_id'] == auth()->id() ? 'text-white hover:text-blue-100' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-200' }} transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Texto del mensaje -->
                        @if($msg['message'])
                            <p class="text-sm whitespace-pre-wrap leading-relaxed">{{ $msg['message'] }}</p>
                        @endif
                        
                        <p class="text-xs mt-3 message-time font-medium">
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
                <p>Envía tu primer mensaje o una imagen para comenzar a chatear con la IA.</p>
            </div>
        @endforelse

        @if($isLoading)
            <div class="flex justify-start">
                <div class="max-w-xs lg:max-w-md message-agent-container">
                    <div class="sender-label text-left sender-label-agent">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                            <span>Agente GPT</span>
                        </div>
                    </div>
                    <div class="message-container px-5 py-4 rounded-2xl message-agent">
                        <div class="flex items-center space-x-3">
                            <div class="animate-spin rounded-full h-5 w-5 border-2 border-gray-300 border-t-blue-500"></div>
                            <p class="text-sm">IA está escribiendo...</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Message Input -->
    <div class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-6 py-4">
        <!-- Preview de imágenes -->
        @if(!empty($previewImages))
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Imágenes seleccionadas:</span>
                    <button 
                        type="button" 
                        wire:click="clearImages" 
                        class="text-xs text-red-500 hover:text-red-700"
                    >
                        Limpiar todo
                    </button>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($previewImages as $index => $preview)
                        <div class="relative">
                            <img 
                                src="{{ $preview['url'] }}" 
                                alt="{{ $preview['name'] }}" 
                                class="w-20 h-20 object-cover rounded-lg border border-gray-200 dark:border-gray-600"
                            >
                            <button 
                                type="button" 
                                wire:click="removeImage({{ $index }})"
                                class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600"
                            >
                                ×
                            </button>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-20 truncate">
                                {{ $preview['name'] }}
                            </div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">
                                {{ $preview['size'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Documentos seleccionados -->
        @if(!empty($previewDocuments))
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Documentos seleccionados:</span>
                    <button 
                        type="button" 
                        wire:click="clearDocuments" 
                        class="text-xs text-red-500 hover:text-red-700"
                    >
                        Limpiar todo
                    </button>
                </div>
                <div class="space-y-2">
                    @foreach($previewDocuments as $index => $preview)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center space-x-3">
                                <!-- Icono según tipo de archivo -->
                                <div class="flex-shrink-0">
                                    @if(in_array(strtolower($preview['type']), ['pdf']))
                                        <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 18h12V6l-4-4H4v16zm-2 1V4c0-1.1.9-2 2-2h8l4 4v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2z"/>
                                        </svg>
                                    @elseif(in_array(strtolower($preview['type']), ['doc', 'docx']))
                                        <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 18h12V6l-4-4H4v16zm-2 1V4c0-1.1.9-2 2-2h8l4 4v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2z"/>
                                        </svg>
                                    @elseif(in_array(strtolower($preview['type']), ['xls', 'xlsx']))
                                        <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 18h12V6l-4-4H4v16zm-2 1V4c0-1.1.9-2 2-2h8l4 4v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2z"/>
                                        </svg>
                                    @elseif(in_array(strtolower($preview['type']), ['ppt', 'pptx']))
                                        <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 18h12V6l-4-4H4v16zm-2 1V4c0-1.1.9-2 2-2h8l4 4v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2z"/>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 18h12V6l-4-4H4v16zm-2 1V4c0-1.1.9-2 2-2h8l4 4v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $preview['name'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $preview['size'] }} • {{ strtoupper($preview['type']) }}</p>
                                </div>
                            </div>
                            <button 
                                type="button" 
                                wire:click="removeDocument({{ $index }})" 
                                class="flex-shrink-0 text-red-500 hover:text-red-700 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <form wire:submit.prevent="sendMessage" class="space-y-3">
            <!-- Área de subida de archivos -->
            <div class="flex items-center space-x-2">
                <input 
                    type="file" 
                    wire:model="images" 
                    multiple 
                    accept="image/*"
                    class="hidden"
                    id="image-upload"
                >
                <label 
                    for="image-upload" 
                    class="flex items-center px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Agregar imágenes
                </label>
                
                <!-- Nuevo selector de documentos -->
                <input 
                    type="file" 
                    wire:model="documents" 
                    multiple 
                    accept=".pdf,.doc,.docx,.txt,.xls,.xlsx,.ppt,.pptx,.rtf,.csv"
                    class="hidden"
                    id="document-upload"
                >
                <label 
                    for="document-upload" 
                    class="flex items-center px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Agregar documentos
                </label>
                
                <!-- Indicadores de carga -->
                <div wire:loading wire:target="images" class="text-xs text-blue-500">
                    Procesando imágenes...
                </div>
                <div wire:loading wire:target="documents" class="text-xs text-blue-500">
                    Procesando documentos...
                </div>
            </div>

            <!-- Input de mensaje y botón de enviar -->
            <div class="flex space-x-4">
                <div class="flex-1">
                    <input 
                        id="messageInput"
                        type="text" 
                        wire:model="message"
                        placeholder="Escribe tu mensaje o selecciona imágenes/documentos..."
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-300 {{ $errorMessage ? 'border-red-500 focus:ring-red-500' : '' }}"
                        maxlength="1000"
                        wire:keydown.enter="sendMessage"
                    >
                    
                    <!-- Mensaje de error -->
                    @if($errorMessage)
                        <p class="text-red-500 text-xs mt-1">{{ $errorMessage }}</p>
                    @endif
                </div>
                <button 
                    type="submit" 
                    id="sumbitInputBtn"
                    class="send-button px-6 py-2 rounded-full text-white font-medium flex items-center justify-center min-w-[120px] shadow-lg"
                    wire:loading.attr="disabled"
                    wire:target="sendMessage,images"
                >
                    <div wire:loading.remove wire:target="sendMessage,images" class="flex items-center">
                        Enviar
                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ml-2">
                            <path d="M11.5003 12H5.41872M5.24634 12.7972L4.24158 15.7986C3.69128 17.4424 3.41613 18.2643 3.61359 18.7704C3.78506 19.21 4.15335 19.5432 4.6078 19.6701C5.13111 19.8161 5.92151 19.4604 7.50231 18.7491L17.6367 14.1886C19.1797 13.4942 19.9512 13.1471 20.1896 12.6648C20.3968 12.2458 20.3968 11.7541 20.1896 11.3351C19.9512 10.8529 19.1797 10.5057 17.6367 9.81135L7.48483 5.24303C5.90879 4.53382 5.12078 4.17921 4.59799 4.32468C4.14397 4.45101 3.77572 4.78336 3.60365 5.22209C3.40551 5.72728 3.67772 6.54741 4.22215 8.18767L5.24829 11.2793C5.34179 11.561 5.38855 11.7019 5.407 11.8459C5.42338 11.9738 5.42321 12.1032 5.40651 12.231C5.38768 12.375 5.34057 12.5157 5.24634 12.7972Z" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div wire:loading wire:target="sendMessage" class="flex items-center">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                        Enviando...
                    </div>
                    <div wire:loading wire:target="images" class="flex items-center">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                        Subiendo...
                    </div>
                </button>
            </div>
        </form>
        
        <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
            <div class="flex items-center justify-between">
                <div>
                    @if(strlen($message) > 0)
                        Caracteres: {{ strlen($message) }}/1000 • 
                    @endif
                    @if(!empty($previewImages))
                        {{ count($previewImages) }} imagen(es) seleccionada(s) • 
                    @endif
                    @if(!empty($previewDocuments))
                        {{ count($previewDocuments) }} documento(s) seleccionado(s) • 
                    @endif
                    Presiona Enter para enviar
                </div>
                <div class="text-right">
                    <div>Imágenes: JPG, PNG, GIF, WebP (máx. 2MB c/u, 5 archivos)</div>
                    <div>Documentos: PDF, DOC, TXT, XLS, PPT (máx. 10MB c/u, 5 archivos)</div>
                    <div class="text-xs text-gray-400 mt-1">Límite total: 8 archivos por mensaje</div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal para ver imágenes en tamaño completo -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
        <button 
            onclick="closeImageModal()" 
            class="absolute top-4 right-4 bg-black bg-opacity-50 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-opacity-75 transition-colors"
        >
            ×
        </button>
        <div id="modalImageName" class="absolute bottom-4 left-4 bg-black bg-opacity-50 text-white px-3 py-2 rounded-lg text-sm"></div>
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

    // Funciones para el modal de imágenes
    window.openImageModal = function(imageUrl, imageName) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const modalImageName = document.getElementById('modalImageName');
        
        modalImage.src = imageUrl;
        modalImage.alt = imageName;
        modalImageName.textContent = imageName;
        modal.classList.remove('hidden');
        
        // Cerrar con Escape
        const handleEscape = function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
                document.removeEventListener('keydown', handleEscape);
            }
        };
        document.addEventListener('keydown', handleEscape);
    };

    window.closeImageModal = function() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
    };

    // Cerrar modal al hacer click fuera de la imagen
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });

    // Limpiar y enfocar input después de enviar mensaje
    Livewire.on('messageSent', () => {
    const input = document.getElementById('messageInput');
    if (input) {
        input.value = '';  
        input.focus();    
    }
    });

    const input = document.getElementById('messageInput');

    const form = input.closest('form');
    form.addEventListener('submit', () => {
        setTimeout(() => {
            input.value = ''; 
            input.focus();   
        }, 50); 
    });

    Livewire.hook('message.sent', () => {
        input.value = '';
        input.focus();
    });

</script>
@endscript
</div>