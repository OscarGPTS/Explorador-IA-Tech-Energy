<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_group_id',
        'user_agent_setting_id',
        'context_data',
        'temperature',
        'max_tokens',
        'enabled_features',
        'is_active'
    ];

    protected $casts = [
        'context_data' => 'array',
        'enabled_features' => 'array',
        'temperature' => 'float',
        'is_active' => 'boolean'
    ];

    /**
     * Relación con grupo de chat
     */
    public function chatGroup()
    {
        return $this->belongsTo(Chatgroup::class, 'chat_group_id');
    }

    /**
     * Relación con configuración de usuario
     */
    public function userAgentSetting()
    {
        return $this->belongsTo(UserAgentSetting::class);
    }

    /**
     * Scope para configuraciones activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para configuraciones de un chat específico
     */
    public function scopeForChat($query, $chatGroupId)
    {
        return $query->where('chat_group_id', $chatGroupId);
    }

    /**
     * Obtener el prompt completo combinando rol y configuración personalizada
     */
    public function getFullPrompt()
    {
        $userSetting = $this->userAgentSetting;
        $agentRole = $userSetting->agentRole;
        
        $basePrompt = $agentRole->system_prompt;
        $instructions = $agentRole->instructions;
        $customPrompt = $userSetting->custom_prompt;
        
        $fullPrompt = $basePrompt;
        
        if ($instructions) {
            $fullPrompt .= "\n\nInstrucciones específicas:\n" . $instructions;
        }
        
        if ($customPrompt) {
            $fullPrompt .= "\n\nPersonalización del usuario:\n" . $customPrompt;
        }
        
        return $fullPrompt;
    }
}
