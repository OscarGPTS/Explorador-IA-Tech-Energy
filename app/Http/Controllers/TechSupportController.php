<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\TechSupportConversation;
use App\Models\TechSupportCategory;
use App\Models\TechSupportProblem;
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
