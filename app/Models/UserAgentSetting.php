<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAgentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agent_role_id',
        'custom_prompt',
        'preferences',
        'is_default',
        'name'
    ];

    protected $casts = [
        'preferences' => 'array',
        'is_default' => 'boolean'
    ];

    /**
     * Relación con usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con rol de agente
     */
    public function agentRole()
    {
        return $this->belongsTo(AgentRole::class);
    }

    /**
     * Relación con configuraciones de chat
     */
    public function chatConfigurations()
    {
        return $this->hasMany(ChatConfiguration::class);
    }

    /**
     * Scope para configuraciones por defecto
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope para configuraciones de un usuario específico
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
