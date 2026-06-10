<?php

namespace App\Services;

class AiService
{
    /**
     * Envía mensajes a OpenAI y devuelve la respuesta
     */
    public static function ask(array $messages)
    {
        // Convertimos los mensajes al formato requerido por el proveedor activo
        $chatMessages = collect($messages)->map(fn($m) => [
            'role' => $m['role'] === 'user' ? 'user' : 'assistant',
            'content' => $m['content'] ?? ''
        ])->toArray();

        try {
            $response = app(AiProviderService::class)->createChatCompletion($chatMessages, [
                'max_tokens' => 500,
            ]);

            return $response['content'];
        } catch (\Exception $e) {
            return "Error al contactar la IA: " . $e->getMessage();
        }
    }

    /**
     * Convierte tus modelos Chat a un array para enviar a OpenAI
     */
    public static function buildMessagesFromChats($chats)
    {
        return collect($chats)->map(fn($c) => [
            'role' => $c->role === 'user' ? 'user' : 'assistant',
            'content' => $c->message ?? ''
        ])->toArray();
    }
}
