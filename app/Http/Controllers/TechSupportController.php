<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\TechSupportConversation;
use App\Models\TechSupportCategory;
use App\Models\TechSupportProblem;
use App\Services\AiProviderService;
use Illuminate\Support\Facades\DB;

class TechSupportController extends Controller
{
    /**
     * Mostrar la página principal del módulo de soporte técnico
     */
    public function index()
    {
        $stats = $this->getStats();
        $categories = TechSupportCategory::active()->ordered()->with('problems')->get();
        
        return view('tech-support.index', compact('stats', 'categories'));
    }

    /**
     * Mostrar el dashboard de estadísticas
     */
    public function dashboard()
    {
        $stats = $this->getDetailedStats();
        
        return view('tech-support.dashboard', compact('stats'));
    }

    /**
     * Manejar interacciones del chat
     */
    public function handleInteraction(Request $request)
    {
        $type = $request->input('type');
        $sessionId = $request->input('session_id', Str::uuid());

        try {
            switch ($type) {
                case 'start':
                    return $this->startConversation($sessionId);
                
                case 'category_selected':
                    return $this->handleCategorySelection($request, $sessionId);
                
                case 'problem_selected':
                    return $this->handleProblemSelection($request, $sessionId);
                
                case 'mark_resolved':
                    return $this->markResolved($sessionId);
                
                case 'escalate':
                    return $this->escalateToIT($sessionId, $request->input('reason'));

                case 'ai_resolve':
                    return $this->aiResolve($request, $sessionId);

                default:
                    return response()->json(['error' => 'Tipo de interacción no válido'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error en soporte técnico', [
                'error' => $e->getMessage(),
                'type' => $type,
                'session_id' => $sessionId
            ]);
            
            return response()->json([
                'error' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Iniciar conversación
     */
    private function startConversation($sessionId)
    {
        return $this->getCategories();
    }

    /**
     * Obtener las categorías principales dinámicamente
     */
    private function getCategories()
    {
        $categories = TechSupportCategory::active()->ordered()->get();
        
        $formattedCategories = $categories->map(function ($category) {
            return [
                'id' => $category->name,
                'title' => $category->icon . ' ' . $category->display_name,
                'description' => $category->description,
                'icon' => $category->icon,
                'color' => $this->getCategoryColor($category->name)
            ];
        })->toArray();

        return response()->json(['categories' => $formattedCategories]);
    }

    /**
     * Obtener color para una categoría
     */
    private function getCategoryColor($categoryName)
    {
        $colors = [
            'computadora' => 'blue',
            'internet' => 'green',
            'correo' => 'yellow',
            'impresora' => 'purple',
            'software' => 'indigo',
            'acceso' => 'red'
        ];

        return $colors[$categoryName] ?? 'gray';
    }

    /**
     * Manejar selección de categoría
     */
    private function handleCategorySelection(Request $request, $sessionId)
    {
        $category = $request->input('category');
        $problems = $this->getProblemsByCategory($category);

        // Registrar interacción
        $categoryModel = TechSupportCategory::where('name', $category)->first();
        
        TechSupportConversation::create([
            'session_id' => $sessionId,
            'tech_support_category_id' => $categoryModel?->id,
            'problem_category_dynamic' => $category,
            'problem_category' => $this->mapCategoryToLegacy($category),
            'user_message' => "Seleccionó categoría: {$category}",
            'bot_response' => 'Categoría seleccionada, mostrando problemas disponibles',
            'response_type' => 'information_request',
            'interaction_step' => 1,
            'user_ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return response()->json([
            'category' => $category,
            'problems' => $problems,
            'session_id' => $sessionId
        ]);
    }

    /**
     * Obtener problemas por categoría dinámicamente
     */
    private function getProblemsByCategory($category)
    {
        $categoryModel = TechSupportCategory::where('name', $category)->first();
        
        if (!$categoryModel) {
            return [];
        }

        $problems = $categoryModel->problems()->get();
        
        return $problems->map(function ($problem) {
            return [
                'id' => $problem->problem_key,
                'title' => $problem->title,
                'description' => $problem->description
            ];
        })->toArray();
    }

    /**
     * Manejar selección de problema
     */
    private function handleProblemSelection(Request $request, $sessionId)
    {
        $problemId = $request->input('problem_id');
        $category = $request->input('category');
        
        $solution = $this->getSolutionForProblem($problemId);
        
        // Registrar interacción con solución
        $problem = TechSupportProblem::where('problem_key', $problemId)->first();
        $categoryModel = TechSupportCategory::where('name', $category)->first();
        
        TechSupportConversation::create([
            'session_id' => $sessionId,
            'tech_support_category_id' => $categoryModel?->id,
            'tech_support_problem_id' => $problem?->id,
            'problem_category_dynamic' => $category,
            'problem_key' => $problemId,
            'problem_category' => $this->mapCategoryToLegacy($category),
            'problem_type' => $problemId,
            'user_message' => "Seleccionó problema: {$problemId}",
            'bot_response' => $solution['title'] ?? 'Solución proporcionada',
            'response_type' => 'solution_provided',
            'interaction_step' => 2,
            'context_data' => ['solution' => $solution],
            'user_ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return response()->json([
            'solution' => $solution,
            'session_id' => $sessionId
        ]);
    }

    /**
     * Obtener solución para un problema dinámicamente
     */
    private function getSolutionForProblem($problemId)
    {
        $problem = TechSupportProblem::where('problem_key', $problemId)->where('is_active', true)->first();
        
        if (!$problem) {
            return [
                'title' => '❓ Problema no encontrado',
                'content' => '<div class="text-center p-6"><p class="text-gray-600">Lo siento, no pude encontrar una solución para este problema específico. Por favor, contacta al departamento de IT para obtener ayuda personalizada.</p></div>',
                'priority' => 'medium',
                'estimated_time' => 'N/A'
            ];
        }

        return [
            'title' => $problem->solution_title,
            'content' => $problem->solution_content,
            'priority' => $problem->priority,
            'estimated_time' => $problem->estimated_time
        ];
    }

    /**
     * Marcar problema como resuelto
     */
    private function markResolved($sessionId)
    {
        $conversation = TechSupportConversation::where('session_id', $sessionId)->latest()->first();
        
        if ($conversation) {
            $conversation->update([
                'problem_solved' => true,
                'resolved_at' => now(),
                'resolution_method' => 'self_service'
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Problema marcado como resuelto']);
    }

    /**
     * Escalar a IT
     */
    private function escalateToIT($sessionId, $reason = null)
    {
        $conversation = TechSupportConversation::where('session_id', $sessionId)->latest()->first();
        
        if ($conversation) {
            $conversation->update([
                'escalated_to_human' => true,
                'resolution_method' => 'escalated_to_it',
                'context_data' => array_merge($conversation->context_data ?? [], ['escalation_reason' => $reason])
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Gracias por usar el sistema de soporte técnico. Para problemas más complejos que requieran atención personalizada, por favor contacta directamente al departamento de IT.'
        ]);
    }

    /**
     * Resolver un problema técnico libre con IA (EVIA · soporte técnico).
     * Reutiliza la misma API de OpenAI que /chat pero con un prompt acotado
     * a soporte técnico y respuesta paso a paso compacta.
     */
    private function aiResolve(Request $request, $sessionId)
    {
        $problem = trim((string) $request->input('problem', ''));

        if ($problem === '' || mb_strlen($problem) < 5) {
            return response()->json([
                'success' => false,
                'error' => 'Describe tu problema con al menos unas palabras para poder ayudarte.'
            ], 422);
        }

        // Límite duro de tokens de entrada para no encarecer la llamada.
        if (mb_strlen($problem) > 800) {
            $problem = mb_substr($problem, 0, 800);
        }

        $providerService = app(AiProviderService::class);
        $providerSummary = $providerService->getProviderSummary();
        $activeProvider = $providerSummary['active_provider'];
        $activeProviderConfig = $providerSummary['providers'][$activeProvider] ?? null;

        if (! $activeProviderConfig || ! $activeProviderConfig['configured']) {
            Log::error('aiResolve: proveedor de IA sin configurar', [
                'provider' => $activeProvider,
            ]);
            return response()->json([
                'success' => false,
                'error' => 'El servicio de IA no está configurado. Contacta al equipo de IT.'
            ], 500);
        }

        $systemPrompt = "Eres EVIA, asistente de soporte técnico corporativo (Oil & Gas).\n"
            . "Responde en español, breve y en pasos numerados (máximo 6 pasos).\n"
            . "Reglas:\n"
            . "- No saludes ni te despidas.\n"
            . "- Cada paso en una sola línea, comenzando con un verbo en imperativo.\n"
            . "- Si el problema requiere intervención de IT (hardware roto, permisos de red, instalación de software), termina con: 'Si no se resuelve, contacta a IT.'\n"
            . "- Si la pregunta no es técnica, responde solo: 'Solo puedo ayudarte con problemas técnicos.'\n"
            . "- No inventes contraseñas, accesos, ni datos del usuario.\n"
            . "- No pidas información sensible.";

        try {
            $result = $providerService->createChatCompletion([
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $problem],
                ],
                [
                // deepseek-v4-flash es un modelo de razonamiento: los reasoning_tokens
                // se descuentan de max_tokens. Con un presupuesto bajo (350) el modelo
                // agota el límite "pensando" y devuelve content vacío -> 500.
                // Se sube para garantizar espacio para la respuesta final.
                'max_tokens' => 1500,
                'temperature' => 0.3,
                ]
            );

            $answer = $result['content'];

            if ($answer === '') {
                return response()->json([
                    'success' => false,
                    'error' => 'La IA no devolvió una respuesta válida. Intenta reformular tu problema.'
                ], 502);
            }

            // Registro de la conversación (best-effort, no rompe si falla)
            try {
                TechSupportConversation::create([
                    'session_id' => $sessionId,
                    'user_id' => auth()->id(),
                    'user_message' => $problem,
                    'bot_response' => $answer,
                    // 'problem_category' es un ENUM acotado; el origen real (ai_resolve)
                    // se conserva en context_data['source'].
                    'problem_category' => 'other',
                    'problem_solved' => false,
                    'escalated_to_human' => false,
                    'context_data' => [
                        'source' => 'ai_resolve',
                        'provider' => $result['provider'],
                        'model' => $result['model'],
                        'tokens' => $result['raw']['usage'] ?? null,
                    ],
                ]);
            } catch (\Throwable $e) {
                Log::debug('aiResolve: no se pudo persistir la conversación', ['error' => $e->getMessage()]);
            }

            return response()->json([
                'success' => true,
                'answer' => $answer,
                'provider' => $result['provider'],
                'model' => $result['model'],
                'usage' => $result['raw']['usage'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('aiResolve: excepción al llamar al proveedor IA', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
                'provider' => $activeProvider,
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Error de conexión con el servicio de IA. Verifica tu conexión a internet.'
            ], 500);
        }
    }

    /**
     * Obtener estadísticas básicas
     */
    private function getStats()
    {
        $totalConversations = TechSupportConversation::count();
        $todayConversations = TechSupportConversation::whereDate('tech_support_conversations.created_at', today())->count();
        $solvedProblems = TechSupportConversation::where('problem_solved', true)->count();
        $escalatedProblems = TechSupportConversation::where('escalated_to_human', true)->count();

        return [
            'total_conversations' => $totalConversations,
            'today_conversations' => $todayConversations,
            'solved_problems' => $solvedProblems,
            'escalated_problems' => $escalatedProblems,
            'effectiveness_rate' => $totalConversations > 0 ? round(($solvedProblems / $totalConversations) * 100, 1) : 0
        ];
    }

    /**
     * Obtener estadísticas detalladas para el dashboard
     */
    private function getDetailedStats()
    {
        $stats = $this->getStats();
        
        // Estadísticas diarias (últimos 7 días)
        $dailyStats = TechSupportConversation::selectRaw('DATE(tech_support_conversations.created_at) as date, COUNT(*) as count')
            ->where('tech_support_conversations.created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'count' => (int) $item->count
                ];
            });

        // Estadísticas por categoría usando el método del modelo
        $categoryStats = TechSupportConversation::getCategoryStats(30);

        // Distribución horaria
        $hourlyDistribution = TechSupportConversation::selectRaw('HOUR(tech_support_conversations.created_at) as hour, COUNT(*) as count')
            ->where('tech_support_conversations.created_at', '>=', now()->subDays(30))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->map(function ($item) {
                return [
                    'hour' => (int) $item->hour,
                    'count' => (int) $item->count
                ];
            });

        return array_merge($stats, [
            'daily_stats' => $dailyStats,
            'category_stats' => $categoryStats,
            'hourly_distribution' => $hourlyDistribution
        ]);
    }

    /**
     * Mapear categoría nueva a formato legacy para compatibilidad
     */
    private function mapCategoryToLegacy($category)
    {
        $mapping = [
            'computadora' => 'computer',
            'internet' => 'internet',
            'correo' => 'email',
            'impresora' => 'printer',
            'software' => 'software',
            'acceso' => 'access'
        ];

        return $mapping[$category] ?? 'other';
    }
}
