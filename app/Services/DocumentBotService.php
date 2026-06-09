<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class DocumentBotService
{
    private string $baseUrl;
    private int $timeout;

    public function __construct()
    {
        // URL base de la API de bots (configurable desde .env mediante DOCUMENT_BOT_API_URL).
        // En local apunta a tu instancia de pruebas y en el servidor a la URL final.
        $this->baseUrl = rtrim(config('services.document_bot.url'), '/');
        $this->timeout = (int) config('services.document_bot.timeout');
    }

    /**
     * Health check del bot simple
     */
    public function simpleHealthCheck(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/bot-simple/health");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Error en la respuesta del servidor',
                'status' => $response->status()
            ];
        } catch (Exception $e) {
            Log::error('Error en health check del bot simple', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Health check del bot avanzado
     */
    public function advancedHealthCheck(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/bot-avanzado/health");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Error en la respuesta del servidor',
                'status' => $response->status()
            ];
        } catch (Exception $e) {
            Log::error('Error en health check del bot avanzado', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Consulta general al bot simple
     */
    public function simpleQuery(string $pregunta): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/api/v1/bot-simple/query", [
                    'pregunta' => $pregunta
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Error en la consulta',
                'status' => $response->status(),
                'message' => $response->json()['detail'] ?? 'Error desconocido'
            ];
        } catch (Exception $e) {
            Log::error('Error en consulta simple', [
                'pregunta' => $pregunta,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Analizar documento específico
     */
    public function analyzeDocument(int $documentoId, ?string $pregunta = null): array
    {
        try {
            $payload = ['documento_id' => $documentoId];
            
            if ($pregunta) {
                $payload['pregunta'] = $pregunta;
            }

            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/api/v1/bot-simple/analyze-document", $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Error al analizar documento',
                'status' => $response->status(),
                'message' => $response->json()['detail'] ?? 'Error desconocido'
            ];
        } catch (Exception $e) {
            Log::error('Error al analizar documento', [
                'documento_id' => $documentoId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Listar todos los documentos
     */
    public function listDocuments(int $limite = 100): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/bot-simple/documents", [
                    'limite' => $limite
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            Log::warning('listDocuments: respuesta no exitosa de API bots', [
                'url' => "{$this->baseUrl}/api/v1/bot-simple/documents",
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 500),
            ]);

            return [
                'success' => false,
                'error' => 'La API de bots respondió con estado HTTP ' . $response->status(),
                'status' => $response->status()
            ];
        } catch (Exception $e) {
            Log::error('Error al listar documentos (excepción)', [
                'url' => "{$this->baseUrl}/api/v1/bot-simple/documents",
                'error' => $e->getMessage(),
                'class' => get_class($e),
            ]);

            return [
                'success' => false,
                'error' => 'No se pudo contactar al servicio de bots: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Listar documentos recientes
     */
    public function recentDocuments(int $limite = 10): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/bot-simple/recent-documents", [
                    'limite' => $limite
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Error al obtener documentos recientes',
                'status' => $response->status()
            ];
        } catch (Exception $e) {
            Log::error('Error al obtener documentos recientes', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Consulta rápida al bot avanzado
     */
    public function quickQuery(string $pregunta, ?array $filtros = null): array
    {
        try {
            $payload = ['pregunta' => $pregunta];
            
            if ($filtros) {
                $payload['filtros'] = $filtros;
            }

            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/api/v1/bot-avanzado/consulta-rapida", $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Error en consulta rápida',
                'status' => $response->status(),
                'message' => $response->json()['detail'] ?? 'Error desconocido'
            ];
        } catch (Exception $e) {
            Log::error('Error en consulta rápida avanzada', [
                'pregunta' => $pregunta,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Razonamiento profundo del bot avanzado
     */
    public function deepReasoning(string $pregunta, ?array $filtros = null, int $k = 10): array
    {
        try {
            $payload = [
                'pregunta' => $pregunta,
                'k' => $k
            ];
            
            if ($filtros) {
                $payload['filtros'] = $filtros;
            }

            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/api/v1/bot-avanzado/razonamiento-profundo", $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Error en razonamiento profundo',
                'status' => $response->status(),
                'message' => $response->json()['detail'] ?? 'Error desconocido'
            ];
        } catch (Exception $e) {
            Log::error('Error en razonamiento profundo', [
                'pregunta' => $pregunta,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Búsqueda semántica
     */
    public function semanticSearch(string $query, int $k = 5, ?array $filtros = null): array
    {
        try {
            $payload = [
                'query' => $query,
                'k' => $k
            ];
            
            if ($filtros) {
                $payload['filtros'] = $filtros;
            }

            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/api/v1/bot-avanzado/busqueda-semantica", $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Error en búsqueda semántica',
                'status' => $response->status(),
                'message' => $response->json()['detail'] ?? 'Error desconocido'
            ];
        } catch (Exception $e) {
            Log::error('Error en búsqueda semántica', [
                'query' => $query,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Obtener estadísticas del bot avanzado
     */
    public function getStats(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/bot-avanzado/stats");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Error al obtener estadísticas',
                'status' => $response->status()
            ];
        } catch (Exception $e) {
            Log::error('Error al obtener estadísticas', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Listar todos los documentos desde bot avanzado
     */
    public function advancedListDocuments(int $limite = 100): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/bot-avanzado/documents", [
                    'limite' => $limite
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Error al listar documentos',
                'status' => $response->status()
            ];
        } catch (Exception $e) {
            Log::error('Error al listar documentos (bot avanzado)', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Listar documentos recientes desde bot avanzado
     */
    public function advancedRecentDocuments(int $limite = 10): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get("{$this->baseUrl}/api/v1/bot-avanzado/recent-documents", [
                    'limite' => $limite
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Error al obtener documentos recientes',
                'status' => $response->status()
            ];
        } catch (Exception $e) {
            Log::error('Error al obtener documentos recientes (bot avanzado)', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Reindexar documentos (operación administrativa)
     */
    public function reindex(): array
    {
        try {
            // Aumentar timeout para esta operación larga
            $response = Http::timeout(300)
                ->post("{$this->baseUrl}/api/v1/bot-avanzado/reindexar");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Error al reindexar documentos',
                'status' => $response->status()
            ];
        } catch (Exception $e) {
            Log::error('Error al reindexar documentos', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
