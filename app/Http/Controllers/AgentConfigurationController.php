<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgentRole;
use App\Models\UserAgentSetting;
use App\Models\ChatConfiguration;
use App\Services\AiProviderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AgentConfigurationController extends Controller
{
    public function __construct(private AiProviderService $aiProviderService)
    {
    }

    /**
     * Obtener todos los roles de agente disponibles
     */
    public function getAvailableRoles()
    {
        $roles = AgentRole::active()->ordered()->get();
        
        return response()->json([
            'success' => true,
            'roles' => $roles
        ]);
    }
    
    /**
     * Obtener las configuraciones de agente del usuario
     */
    public function getUserSettings()
    {
        $settings = UserAgentSetting::with('agentRole')
            ->forUser(Auth::id())
            ->get();
            
        return response()->json([
            'success' => true,
            'settings' => $settings
        ]);
    }
    
    /**
     * Crear una nueva configuración de agente para el usuario
     */
    public function createUserSetting(Request $request)
    {
        $request->validate([
            'agent_role_id' => 'required|exists:agent_roles,id',
            'name' => 'required|string|max:255',
            'custom_prompt' => 'nullable|string',
            'preferences' => 'nullable|array',
            'is_default' => 'boolean'
        ]);
        
        // Si se está marcando como default, desmarcar otros defaults del usuario
        if ($request->is_default) {
            UserAgentSetting::forUser(Auth::id())
                ->update(['is_default' => false]);
        }
        
        $setting = UserAgentSetting::create([
            'user_id' => Auth::id(),
            'agent_role_id' => $request->agent_role_id,
            'name' => $request->name,
            'custom_prompt' => $request->custom_prompt,
            'preferences' => $request->preferences ?? [],
            'is_default' => $request->is_default ?? false
        ]);
        
        $setting->load('agentRole');
        
        return response()->json([
            'success' => true,
            'setting' => $setting,
            'message' => 'Configuración de agente creada exitosamente'
        ]);
    }
    
    /**
     * Actualizar una configuración de agente del usuario
     */
    public function updateUserSetting(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'custom_prompt' => 'nullable|string',
            'preferences' => 'nullable|array',
            'is_default' => 'boolean'
        ]);
        
        $setting = UserAgentSetting::forUser(Auth::id())->findOrFail($id);
        
        // Si se está marcando como default, desmarcar otros defaults del usuario
        if ($request->is_default && !$setting->is_default) {
            UserAgentSetting::forUser(Auth::id())
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
        }
        
        $setting->update([
            'name' => $request->name,
            'custom_prompt' => $request->custom_prompt,
            'preferences' => $request->preferences ?? [],
            'is_default' => $request->is_default ?? false
        ]);
        
        $setting->load('agentRole');
        
        return response()->json([
            'success' => true,
            'setting' => $setting,
            'message' => 'Configuración actualizada exitosamente'
        ]);
    }
    
    /**
     * Eliminar una configuración de agente del usuario
     */
    public function deleteUserSetting($id)
    {
        $setting = UserAgentSetting::forUser(Auth::id())->findOrFail($id);
        
        // Si era la configuración por defecto, marcar otra como defecto (si existe)
        if ($setting->is_default) {
            $nextDefault = UserAgentSetting::forUser(Auth::id())
                ->where('id', '!=', $id)
                ->first();
                
            if ($nextDefault) {
                $nextDefault->update(['is_default' => true]);
            }
        }
        
        $setting->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Configuración eliminada exitosamente'
        ]);
    }
    
    /**
     * Aplicar una configuración de agente a un chat específico
     */
    public function applyChatConfiguration(Request $request, $chatGroupId)
    {
        $request->validate([
            'user_agent_setting_id' => 'required|exists:user_agent_settings,id',
            'temperature' => 'nullable|numeric|min:0|max:2',
            'max_tokens' => 'nullable|integer|min:1|max:4000',
            'enabled_features' => 'nullable|array'
        ]);
        
        // Verificar que la configuración pertenece al usuario actual
        $userSetting = UserAgentSetting::forUser(Auth::id())
            ->findOrFail($request->user_agent_setting_id);
        
        // Desactivar cualquier configuración previa para este chat
        ChatConfiguration::forChat($chatGroupId)->update(['is_active' => false]);
        
        $chatConfig = ChatConfiguration::updateOrCreate(
            [
                'chat_group_id' => $chatGroupId,
                'user_agent_setting_id' => $request->user_agent_setting_id
            ],
            [
                'temperature' => $request->temperature ?? 0.7,
                'max_tokens' => $request->max_tokens ?? 2000,
                'enabled_features' => $request->enabled_features ?? [],
                'is_active' => true
            ]
        );
        
        $chatConfig->load(['userAgentSetting.agentRole']);
        
        return response()->json([
            'success' => true,
            'configuration' => $chatConfig,
            'message' => 'Configuración aplicada al chat exitosamente'
        ]);
    }
    
    /**
     * Obtener la configuración activa de un chat
     */
    public function getChatConfiguration($chatGroupId)
    {
        $config = ChatConfiguration::with(['userAgentSetting.agentRole'])
            ->forChat($chatGroupId)
            ->active()
            ->first();
            
        return response()->json([
            'success' => true,
            'configuration' => $config
        ]);
    }
    
    /**
     * Obtener configuración por defecto del usuario o sistema
     */
    public function getDefaultConfiguration()
    {
        $userDefault = UserAgentSetting::with('agentRole')
            ->forUser(Auth::id())
            ->default()
            ->first();
            
        if (!$userDefault) {
            // Si el usuario no tiene configuración por defecto, usar la del sistema
            $systemDefault = AgentRole::default()->first();
            
            return response()->json([
                'success' => true,
                'configuration' => [
                    'agent_role' => $systemDefault,
                    'system_prompt' => $systemDefault->system_prompt,
                    'instructions' => $systemDefault->instructions,
                    'temperature' => 0.7,
                    'max_tokens' => 2000
                ]
            ]);
        }
        
        return response()->json([
            'success' => true,
            'configuration' => [
                'user_setting' => $userDefault,
                'agent_role' => $userDefault->agentRole,
                'system_prompt' => $userDefault->agentRole->system_prompt,
                'custom_prompt' => $userDefault->custom_prompt,
                'instructions' => $userDefault->agentRole->instructions,
                'temperature' => 0.7,
                'max_tokens' => 2000
            ]
        ]);
    }

    /**
     * Obtener la configuración global del proveedor de IA.
     */
    public function getProviderConfiguration()
    {
        return response()->json([
            'success' => true,
            'configuration' => $this->aiProviderService->getProviderSummary(),
        ]);
    }

    /**
     * Actualizar el proveedor global activo.
     */
    public function updateProviderConfiguration(Request $request)
    {
        $validated = $request->validate([
            'provider' => ['required', 'string', Rule::in($this->aiProviderService->getSupportedProviders())],
            'model' => ['nullable', 'string'],
        ]);

        $summary = $this->aiProviderService->getProviderSummary();
        $providerConfig = $summary['providers'][$validated['provider']] ?? null;

        if (! $providerConfig || ! $providerConfig['configured']) {
            return response()->json([
                'success' => false,
                'message' => 'El proveedor seleccionado no tiene credenciales configuradas.',
            ], 422);
        }

        // Validar el modelo (si se envió) contra el catálogo del proveedor.
        if (! empty($validated['model'])) {
            $availableModels = $providerConfig['models'] ?? [];

            if (! empty($availableModels) && ! array_key_exists($validated['model'], $availableModels)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El modelo seleccionado no está disponible para este proveedor.',
                ], 422);
            }
        }

        $this->aiProviderService->setActiveProvider($validated['provider']);

        if (! empty($validated['model'])) {
            $this->aiProviderService->setActiveModel($validated['provider'], $validated['model']);
        }

        return response()->json([
            'success' => true,
            'configuration' => $this->aiProviderService->getProviderSummary(),
            'message' => 'Proveedor de IA actualizado correctamente.',
        ]);
    }
}
