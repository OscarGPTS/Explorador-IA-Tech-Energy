<?php
// filepath: c:\xampp\htdocs\Explorador-IA\app\Livewire\Chat\ChatIndex.php

namespace App\Livewire\Chat;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Chatgroup;
use App\Models\Chat;
use OpenAI\Laravel\Facades\OpenAI;

class ChatIndex extends Component
{
    public $message = '';
    public $chatgroup_id;
    public $chatgroup;
    public $messages = [];
    public $isLoading = false;
    public $errorMessage = '';

    protected $listeners = ['messageUpdated' => 'loadMessages'];

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Crear o recuperar el chatgroup del usuario
        $this->chatgroup = Chatgroup::firstOrCreate([
            'user_id' => Auth::id(),
        ], [
            'name' => 'Chat con IA - ' . Auth::user()->name
        ]);
        
        $this->chatgroup_id = $this->chatgroup->id;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = Chat::where('chatgroup_id', $this->chatgroup_id)
            ->with(['emisor'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($chat) {
                return [
                    'id' => $chat->id,
                    'message' => $chat->message,
                    'emisor_id' => $chat->emisor_id,
                    'receiver' => $chat->receiver,
                    'created_at' => $chat->created_at,
                    'emisor_name' => $chat->emisor ? $chat->emisor->name : 'IA'
                ];
            })
            ->toArray();
    }

    public function updatedMessage()
    {
        // Limpiar mensaje de error cuando el usuario empiece a escribir
        $this->errorMessage = '';
    }

    // Método alternativo usando Laravel HTTP Client
    private function sendToOpenAIWithHttp($messages)
    {
        $apiKey = config('openai.api_key') ?? env('OPENAI_API_KEY');
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])
        ->timeout(60)
        ->connectTimeout(30)
        ->withOptions([
            'verify' => false, // Deshabilitar verificación SSL para XAMPP
        ])
        ->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'max_tokens' => 500,
            'temperature' => 0.7,
        ]);

        if ($response->failed()) {
            throw new \Exception('Error HTTP: ' . $response->status() . ' - ' . $response->body());
        }

        return $response->json();
    }

    public function sendMessage()
    {
        // Limpiar errores previos
        $this->errorMessage = '';

        // Validar que el mensaje no esté vacío
        if (empty(trim($this->message))) {
            $this->errorMessage = 'Por favor, escribe un mensaje antes de enviarlo.';
            return;
        }

        // Validar longitud del mensaje
        if (strlen(trim($this->message)) > 1000) {
            $this->errorMessage = 'El mensaje es demasiado largo. Máximo 1000 caracteres.';
            return;
        }

        $this->isLoading = true;

        try {
            // Log de debugging
            Log::info('Iniciando envío de mensaje', [
                'user_id' => Auth::id(),
                'message_length' => strlen($this->message),
                'chatgroup_id' => $this->chatgroup_id
            ]);

            // Verificar que la API key esté configurada
            $apiKey = config('openai.api_key') ?? env('OPENAI_API_KEY');
            
            if (empty($apiKey)) {
                throw new \Exception('API key de OpenAI no encontrada en configuración');
            }

            // Guardar mensaje del usuario
            $userMessage = Chat::create([
                'message' => trim($this->message),
                'emisor_id' => Auth::id(),
                'receiver' => 1, // ID del sistema/IA
                'chatgroup_id' => $this->chatgroup_id,
            ]);

            Log::info('Mensaje del usuario guardado', ['message_id' => $userMessage->id]);

            $currentMessage = trim($this->message);
            $this->reset('message'); // Limpiar el input
            $this->loadMessages();

            // Preparar mensajes para OpenAI
            $messages = [
                [
                    'role' => 'system',
                    'content' => 'Eres un asistente de IA útil y amigable. Responde de manera clara y concisa en español.'
                ],
                [
                    'role' => 'user',
                    'content' => $currentMessage
                ],
            ];

            Log::info('Enviando request a OpenAI (método HTTP)', [
                'message_preview' => substr($currentMessage, 0, 50) . '...'
            ]);

            // Intentar primero con Laravel HTTP Client
            try {
                $response = $this->sendToOpenAIWithHttp($messages);
                
                if (!isset($response['choices'][0]['message']['content'])) {
                    throw new \Exception('Respuesta inválida de OpenAI: no se encontró contenido');
                }

                $aiResponse = $response['choices'][0]['message']['content'];
                
                Log::info('Respuesta recibida de OpenAI (HTTP)', [
                    'response_length' => strlen($aiResponse)
                ]);

            } catch (\Exception $httpException) {
                Log::warning('HTTP Client falló, intentando con OpenAI Laravel package', [
                    'http_error' => $httpException->getMessage()
                ]);

                // Fallback: usar el package de OpenAI Laravel
                $openaiResponse = OpenAI::chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => $messages,
                    'max_tokens' => 500,
                    'temperature' => 0.7,
                ]);

                if (empty($openaiResponse->choices) || !isset($openaiResponse->choices[0]->message->content)) {
                    throw new \Exception('Respuesta inválida de OpenAI: no se encontró contenido');
                }

                $aiResponse = $openaiResponse->choices[0]->message->content;
                
                Log::info('Respuesta recibida de OpenAI (Package)', [
                    'response_length' => strlen($aiResponse)
                ]);
            }

            // Guardar respuesta de la IA
            $aiMessage = Chat::create([
                'message' => trim($aiResponse),
                'emisor_id' => 1, // ID del sistema/IA
                'receiver' => Auth::id(),
                'chatgroup_id' => $this->chatgroup_id,
            ]);

            Log::info('Respuesta de IA guardada', ['ai_message_id' => $aiMessage->id]);

        } catch (\OpenAI\Exceptions\ErrorException $e) {
            // Error específico de OpenAI API
            Log::error('Error de OpenAI API', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'user_id' => Auth::id()
            ]);

            $errorMsg = 'Error de OpenAI API: ' . $e->getMessage();
            $this->errorMessage = $errorMsg;

            Chat::create([
                'message' => $errorMsg,
                'emisor_id' => 1,
                'receiver' => Auth::id(),
                'chatgroup_id' => $this->chatgroup_id,
            ]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Error de conexión HTTP
            Log::error('Error de conexión HTTP', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            $errorMsg = 'Error de conexión. Verifica tu conexión a internet e inténtalo nuevamente.';
            $this->errorMessage = $errorMsg;

            Chat::create([
                'message' => $errorMsg,
                'emisor_id' => 1,
                'receiver' => Auth::id(),
                'chatgroup_id' => $this->chatgroup_id,
            ]);

        } catch (\Exception $e) {
            // Error general
            Log::error('Error general en sendMessage', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => substr($e->getTraceAsString(), 0, 500),
                'user_id' => Auth::id()
            ]);

            $errorMsg = 'Error: ' . $e->getMessage();
            $this->errorMessage = $errorMsg;

            Chat::create([
                'message' => $errorMsg,
                'emisor_id' => 1,
                'receiver' => Auth::id(),
                'chatgroup_id' => $this->chatgroup_id,
            ]);
        }

        $this->isLoading = false;
        $this->loadMessages();
    }

    public function testOpenAI()
    {
        try {
            Log::info('Testeando conexión OpenAI con HTTP Client');
            
            $messages = [
                ['role' => 'user', 'content' => 'Responde solo: "Test exitoso"']
            ];

            // Probar con HTTP Client primero
            try {
                $response = $this->sendToOpenAIWithHttp($messages);
                $result = $response['choices'][0]['message']['content'];
                $this->errorMessage = 'Test HTTP exitoso: ' . $result;
                Log::info('Test HTTP exitoso', ['response' => $result]);
            } catch (\Exception $httpException) {
                Log::info('HTTP falló, probando con OpenAI package');
                
                // Fallback con OpenAI package
                $response = OpenAI::chat()->create([
                    'model' => 'gpt-5-mini',
                    'messages' => $messages,
                    'max_tokens' => 10
                ]);

                $result = $response->choices[0]->message->content;
                $this->errorMessage = 'Test Package exitoso: ' . $result;
                Log::info('Test Package exitoso', ['response' => $result]);
            }
            
        } catch (\Exception $e) {
            $errorMsg = 'Test falló: ' . $e->getMessage();
            $this->errorMessage = $errorMsg;
            Log::error('Test falló', [
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
        }
    }

    public function clearChat()
    {
        Chat::where('chatgroup_id', $this->chatgroup_id)->delete();
        $this->loadMessages();
        $this->errorMessage = '';
        $this->dispatch('chatCleared');
    }

    public function render()
    {
        return view('livewire.chat.chat-index');
    }
}