<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TechSupportConversation extends Model
{
    protected $fillable = [
        'session_id',
        'user_ip',
        'user_agent',
        'employee_id',
        'problem_category',
        'problem_type',
        'user_message',
        'bot_response',
        'response_type',
        'problem_solved',
        'escalated_to_human',
        'resolution_method',
        'interaction_step',
        'context_data',
        'resolved_at',
        // Nuevas columnas para la estructura dinámica
        'tech_support_category_id',
        'tech_support_problem_id',
        'problem_category_dynamic',
        'problem_key'
    ];

    protected $casts = [
        'context_data' => 'array',
        'problem_solved' => 'boolean',
        'escalated_to_human' => 'boolean',
        'resolved_at' => 'datetime'
    ];

    // Relaciones
    public function category()
    {
        return $this->belongsTo(TechSupportCategory::class, 'tech_support_category_id');
    }

    public function problem()
    {
        return $this->belongsTo(TechSupportProblem::class, 'tech_support_problem_id');
    }

    // Scopes
    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('problem_category', $category);
    }

    public function scopeBySession(Builder $query, string $sessionId): Builder
    {
        return $query->where('session_id', $sessionId)->orderBy('interaction_step');
    }

    public function scopeSolved(Builder $query): Builder
    {
        return $query->where('problem_solved', true);
    }

    public function scopeEscalated(Builder $query): Builder
    {
        return $query->where('escalated_to_human', true);
    }

    public function scopeRecent(Builder $query, int $days = 7): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getCategoryLabelAttribute(): string
    {
        // Preferir la categoría dinámica si existe
        if ($this->category) {
            return $this->category->display_name;
        }
        
        // Fallback a la categoría estática
        return match($this->problem_category) {
            'computer' => 'Problemas de Computadora',
            'internet' => 'Problemas de Internet',
            'email' => 'Problemas de Email',
            'printer' => 'Problemas de Impresora',
            'software' => 'Problemas de Software',
            'access' => 'Problemas de Acceso',
            'google' => 'Google Suite',
            'office' => 'Microsoft Office',
            'other' => 'Otros',
            default => $this->problem_category_dynamic ?? 'Sin Categoría'
        };
    }

    public function getResponseTypeLabelAttribute(): string
    {
        return match($this->response_type) {
            'solution_provided' => 'Solución Proporcionada',
            'escalated_to_it' => 'Escalado a IT',
            'partial_solution' => 'Solución Parcial',
            'information_request' => 'Solicitud de Información',
            default => 'Desconocido'
        };
    }

    // Helper methods
    public static function getPopularProblems(int $days = 30, int $limit = 10): array
    {
        return self::recent($days)
            ->selectRaw('problem_type, problem_category, COUNT(*) as count')
            ->whereNotNull('problem_type')
            ->groupBy('problem_type', 'problem_category')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public static function getSolutionEffectiveness(): array
    {
        $total = self::count();
        $solved = self::solved()->count();
        $escalated = self::escalated()->count();
        
        return [
            'total_conversations' => $total,
            'solved_by_bot' => $solved,
            'escalated_to_human' => $escalated,
            'effectiveness_rate' => $total > 0 ? round(($solved / $total) * 100, 2) : 0,
            'escalation_rate' => $total > 0 ? round(($escalated / $total) * 100, 2) : 0
        ];
    }

    public static function getCategoryStats(int $days = 30): array
    {
        // Estadísticas usando las nuevas categorías dinámicas
        $dynamicStats = self::join('tech_support_categories', 'tech_support_conversations.tech_support_category_id', '=', 'tech_support_categories.id')
            ->selectRaw('tech_support_categories.display_name as category_name, 
                        tech_support_categories.name as category_key,
                        COUNT(*) as count, 
                        SUM(CASE WHEN tech_support_conversations.problem_solved = 1 THEN 1 ELSE 0 END) as solved,
                        SUM(CASE WHEN tech_support_conversations.escalated_to_human = 1 THEN 1 ELSE 0 END) as escalated')
            ->where('tech_support_conversations.created_at', '>=', now()->subDays($days))
            ->whereNotNull('tech_support_conversations.tech_support_category_id')
            ->groupBy('tech_support_categories.id', 'tech_support_categories.display_name', 'tech_support_categories.name')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'problem_category' => $item->category_key,
                    'category_name' => $item->category_name,
                    'count' => (int) $item->count,
                    'solved' => (int) $item->solved,
                    'escalated' => (int) $item->escalated,
                    'is_dynamic' => true
                ];
            });

        // Estadísticas usando las categorías estáticas (para compatibilidad)
        $staticStats = self::selectRaw('problem_category, COUNT(*) as count, 
                    SUM(CASE WHEN problem_solved = 1 THEN 1 ELSE 0 END) as solved,
                    SUM(CASE WHEN escalated_to_human = 1 THEN 1 ELSE 0 END) as escalated')
            ->where('created_at', '>=', now()->subDays($days))
            ->whereNotNull('problem_category')
            ->whereNull('tech_support_category_id') // Solo los que no tienen categoría dinámica
            ->groupBy('problem_category')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'problem_category' => $item->problem_category,
                    'category_name' => self::getCategoryNameFromOldValue($item->problem_category),
                    'count' => (int) $item->count,
                    'solved' => (int) $item->solved,
                    'escalated' => (int) $item->escalated,
                    'is_dynamic' => false
                ];
            });

        // Combinar ambos conjuntos de estadísticas
        return $dynamicStats->concat($staticStats)->toArray();
    }

    private static function getCategoryNameFromOldValue($category): string
    {
        return match($category) {
            'computer' => 'Computadora (Legacy)',
            'internet' => 'Internet (Legacy)',
            'email' => 'Email (Legacy)',
            'printer' => 'Impresora (Legacy)',
            'software' => 'Software (Legacy)',
            'access' => 'Acceso (Legacy)',
            'google' => 'Google Suite (Legacy)',
            'office' => 'Microsoft Office (Legacy)',
            'other' => 'Otros (Legacy)',
            default => $category . ' (Legacy)'
        };
    }
}
