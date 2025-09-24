<?php

namespace App\Services;

use GuzzleHttp\Client;

class AiService
{
    /**
     * Envía mensajes a OpenAI y devuelve la respuesta
     */
    public static function ask(array $messages)
    {
        $apiKey = env('OPENAI_API_KEY');

        $client = new Client([
            
            'base_uri' => 'https://api.openai.com/ ',
            'timeout'  => 30,
        ]);

        // Convertimos los mensajes al formato requerido por OpenAI
        $chatMessages = collect($messages)->map(fn($m) => [
            'role' => $m['role'] === 'user' ? 'user' : 'assistant',
            'content' => $m['content'] ?? ''
        ])->toArray();

        $payload = [
            'model' => 'gpt-5',
            'messages' => $chatMessages,
            'max_tokens' => 500,
        ];

        try {
            $response = $client->post('v1/responses ', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => $payload,
            ]);
            dd($response);
            $body = json_decode($response->getBody(), true);

            return $body['choices'][0]['message']['content'] ?? 'No response from AI';
        } catch (\Exception $e) {
            // Para depuración puedes hacer dump($e->getMessage())
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
