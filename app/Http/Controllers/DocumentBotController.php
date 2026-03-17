<?php

namespace App\Http\Controllers;

use App\Services\DocumentBotService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class DocumentBotController extends Controller
{
    private DocumentBotService $documentBotService;

    public function __construct(DocumentBotService $documentBotService)
    {
        $this->documentBotService = $documentBotService;
    }

    /**
     * Mostrar la vista principal del buscador de documentos
     */
    public function index(): View
    {
        return view('document-bot.index');
    }

    /**
     * Health check del sistema
     */
    public function healthCheck(): JsonResponse
    {
        $simpleHealth = $this->documentBotService->simpleHealthCheck();
        $advancedHealth = $this->documentBotService->advancedHealthCheck();

        return response()->json([
            'success' => true,
            'simple_bot' => $simpleHealth,
            'advanced_bot' => $advancedHealth,
            'timestamp' => now()->toIso8601String()
        ]);
    }

    /**
     * Consulta general (bot simple)
     */
    public function query(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'pregunta' => 'required|string|min:3|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->documentBotService->simpleQuery($request->pregunta);

        return response()->json($result);
    }

    /**
     * Analizar documento específico
     */
    public function analyzeDocument(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'documento_id' => 'required|integer|min:1',
            'pregunta' => 'nullable|string|min:3|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->documentBotService->analyzeDocument(
            $request->documento_id,
            $request->pregunta
        );

        return response()->json($result);
    }

    /**
     * Listar todos los documentos
     */
    public function listDocuments(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limite' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $limite = $request->input('limite', 100);
        $result = $this->documentBotService->listDocuments($limite);

        return response()->json($result);
    }

    /**
     * Listar documentos recientes
     */
    public function recentDocuments(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limite' => 'nullable|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $limite = $request->input('limite', 10);
        $result = $this->documentBotService->recentDocuments($limite);

        return response()->json($result);
    }

    /**
     * Consulta rápida (bot avanzado)
     */
    public function quickQuery(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'pregunta' => 'required|string|min:3|max:1000',
            'filtros' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->documentBotService->quickQuery(
            $request->pregunta,
            $request->filtros
        );

        return response()->json($result);
    }

    /**
     * Razonamiento profundo (bot avanzado)
     */
    public function deepReasoning(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'pregunta' => 'required|string|min:3|max:1000',
            'filtros' => 'nullable|array',
            'k' => 'nullable|integer|min:1|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->documentBotService->deepReasoning(
            $request->pregunta,
            $request->filtros,
            $request->input('k', 10)
        );

        return response()->json($result);
    }

    /**
     * Búsqueda semántica
     */
    public function semanticSearch(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:3|max:500',
            'k' => 'nullable|integer|min:1|max:20',
            'filtros' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->documentBotService->semanticSearch(
            $request->input('query'),
            $request->input('k', 5),
            $request->filtros
        );

        return response()->json($result);
    }

    /**
     * Obtener estadísticas
     */
    public function stats(): JsonResponse
    {
        $result = $this->documentBotService->getStats();

        return response()->json($result);
    }

    /**
     * Reindexar documentos (operación administrativa)
     */
    public function reindex(): JsonResponse
    {
        // Solo permitir a usuarios autenticados y administradores
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'error' => 'No autorizado'
            ], 403);
        }

        $result = $this->documentBotService->reindex();

        return response()->json($result);
    }
}
