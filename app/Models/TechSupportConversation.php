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
        'resolved_at'
    ];

    protected $casts = [
        'context_data' => 'array',
        'problem_solved' => 'boolean',
        'escalated_to_human' => 'boolean',
        'resolved_at' => 'datetime'
    ];

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
            default => 'Sin Categoría'
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
        return self::recent($days)
            ->selectRaw('problem_category, COUNT(*) as count, 
                        SUM(CASE WHEN problem_solved = 1 THEN 1 ELSE 0 END) as solved,
                        SUM(CASE WHEN escalated_to_human = 1 THEN 1 ELSE 0 END) as escalated')
            ->whereNotNull('problem_category')
            ->groupBy('problem_category')
            ->orderByDesc('count')
            ->get()
            ->toArray();
    }
}
