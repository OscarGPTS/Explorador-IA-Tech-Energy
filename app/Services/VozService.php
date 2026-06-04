<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class VozService
{
    private string $baseUrl;
    private int $timeout;

    public function __construct()
    {
        // Reutiliza la misma API de bots que el resto del proyecto.
        // Se puede sobreescribir con VOZ_API_URL en el .env.
        $this->baseUrl = env('VOZ_API_URL', env('DOCUMENT_BOT_API_URL', 'https://bots.tech-energy.lat'));
        $this->timeout = (int) env('VOZ_API_TIMEOUT', 120);
    }

    /**
     * Health del servicio de voz.
     */
    public function health(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/voz/health");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => 'El servicio de voz respondió con estado HTTP ' . $response->status(),
                'status' => $response->status(),
            ];
        } catch (Exception $e) {
            Log::error('Error en health del servicio de voz', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'No se pudo contactar al servicio de voz: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Envía un audio al servicio de voz y devuelve texto + audio (base64).
     *
     * @param  UploadedFile  $audio              Audio de la pregunta (webm/wav/mp3/ogg/m4a)
     * @param  string        $formatoRespuesta   texto | audio | ambos
     */
    public function consulta(UploadedFile $audio, string $formatoRespuesta = 'ambos'): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->attach(
                    'file',
                    file_get_contents($audio->getRealPath()),
                    $audio->getClientOriginalName() ?: 'pregunta.webm',
                    ['Content-Type' => $audio->getMimeType() ?: 'application/octet-stream']
                )
                ->post("{$this->baseUrl}/api/v1/voz/consulta", [
                    'formato_respuesta' => $formatoRespuesta,
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['detail'] ?? 'Error en la consulta de voz',
                'status' => $response->status(),
            ];
        } catch (Exception $e) {
            Log::error('Error en consulta de voz', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'No se pudo procesar el audio: ' . $e->getMessage(),
            ];
        }
    }
}
