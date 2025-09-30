<!-- filepath: c:\xampp\htdocs\Explorador-IA\resources\views\livewire\chat\chat-index.blade.php -->
<div>
    <div class="flex flex-col h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="fixed top-16 z-10 left-0 right-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Chat con IA</h1>
                
                <!-- Indicador de agente actual -->
                @if($currentAgentConfig)
                <div class="flex items-center mt-2 space-x-3">
                    <div class="flex items-center space-x-2 bg-blue-50 dark:bg-blue-900/20 px-3 py-1 rounded-full">
                        <span class="text-2xl">{{ $currentAgentConfig['agent_role']['icon'] ?? '🤖' }}</span>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-blue-900 dark:text-blue-100">
                                {{ $currentAgentConfig['name'] }}
                            </span>
                            @if($currentAgentConfig['is_user_setting'] && $currentAgentConfig['custom_prompt'])
                            <span class="text-xs text-blue-600 dark:text-blue-300">Personalizado</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Botón para cambiar agente -->
                    <button 
                        wire:click="toggleAgentSelector"
                        class="flex items-center space-x-1 px-3 py-1 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                        title="Cambiar agente"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        <span>Cambiar</span>
                    </button>
                    
                    <!-- Botón para configurar -->
                    {{-- <a 
                        href="{{ route('agent.config.view') }}" 
                        class="flex items-center space-x-1 px-3 py-1 text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                        title="Configurar agentes"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Configurar</span>
                    </a> --}}
                </div>
                @endif
            </div>
            
            <button 
                wire:click="clearChat" 
                class="px-4 py-2 text-sm bg-red-500 hover:bg-red-600 text-white rounded-full transition-colors"
                onclick="return confirm('¿Estás seguro de que quieres limpiar el chat?')"
            >
                <div class="flex">
                   <svg fill="#FFFFFF"  width="16px" height="16px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M5.755,20.283,4,8H20L18.245,20.283A2,2,0,0,1,16.265,22H7.735A2,2,0,0,1,5.755,20.283ZM21,4H16V3a1,1,0,0,0-1-1H9A1,1,0,0,0,8,3V4H3A1,1,0,0,0,3,6H21a1,1,0,0,0,0-2Z"/></svg>
                   <p class="text-white ml-2">Limpiar Chat </p> 
                </div>
            </button>
        </div>
        
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
    <div class="flex-1 overflow-y-auto p-6 space-y-4 mt-20" id="messages-container">
        @forelse($messages as $msg)
            <div class="flex {{ $msg['emisor_id'] == auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs lg:max-w-md">
                    <!-- Nombre del emisor -->
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1 {{ $msg['emisor_id'] == auth()->id() ? 'text-right' : 'text-left' }}">
                        {{ $msg['emisor_id'] == auth()->id() ? 'Tú' : 'Agente GPT' }}
                    </div>
                    
                    <!-- Mensaje -->
                    <div class="px-4 py-3 rounded-lg {{ $msg['emisor_id'] == auth()->id() ? 'bg-[#FFECB0] text-black' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700' }}">
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
                            <p class="text-sm whitespace-pre-wrap">{{ $msg['message'] }}</p>
                        @endif
                        
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
                <p>Envía tu primer mensaje o una imagen para comenzar a chatear con la IA.</p>
            </div>
        @endforelse

        @if($isLoading)
            <div class="flex justify-start">
                <div class="max-w-xs lg:max-w-md">
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                        Agente GPT
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
                    class="px-6 py-2 bg-[#FFDE72] hover:bg-[#FFD03A] disabled:bg-stone-200 rounded-full disabled:cursor-not-allowed text-black font-medium transition-colors flex items-center justify-center min-w-[120px]"
                    wire:loading.attr="disabled"
                    wire:target="sendMessage,images"
                >
                    <div wire:loading.remove wire:target="sendMessage,images" class="flex items-center">
                        Enviar
                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ml-2">
                            <path d="M11.5003 12H5.41872M5.24634 12.7972L4.24158 15.7986C3.69128 17.4424 3.41613 18.2643 3.61359 18.7704C3.78506 19.21 4.15335 19.5432 4.6078 19.6701C5.13111 19.8161 5.92151 19.4604 7.50231 18.7491L17.6367 14.1886C19.1797 13.4942 19.9512 13.1471 20.1896 12.6648C20.3968 12.2458 20.3968 11.7541 20.1896 11.3351C19.9512 10.8529 19.1797 10.5057 17.6367 9.81135L7.48483 5.24303C5.90879 4.53382 5.12078 4.17921 4.59799 4.32468C4.14397 4.45101 3.77572 4.78336 3.60365 5.22209C3.40551 5.72728 3.67772 6.54741 4.22215 8.18767L5.24829 11.2793C5.34179 11.561 5.38855 11.7019 5.407 11.8459C5.42338 11.9738 5.42321 12.1032 5.40651 12.231C5.38768 12.375 5.34057 12.5157 5.24634 12.7972Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
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