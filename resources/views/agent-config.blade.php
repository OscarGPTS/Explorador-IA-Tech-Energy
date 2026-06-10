@extends('layouts.app')

@section('title', 'Configuración de Agentes IA')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                🤖 Configuración de Agentes IA
            </h1>
            <p class="text-gray-600">
                Personaliza el comportamiento de la IA según tus necesidades. Puedes configurar diferentes agentes para distintos propósitos.
            </p>
        </div>

        <!-- Loading State -->
        <div id="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>

        <!-- Content Container -->
        <div id="content" class="hidden">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Proveedor de IA Activo</h2>
                        <p class="text-gray-600 text-sm mt-1">Cambia el proveedor por defecto para /chat y /tech-support sin reiniciar la aplicación.</p>
                    </div>
                    <div id="provider-status-badge" class="text-xs font-semibold px-3 py-1 rounded-full bg-gray-100 text-gray-700 w-fit">
                        Cargando...
                    </div>
                </div>
                <div class="p-6">
                    <div id="provider-config-panel"></div>
                </div>
            </div>

            <!-- Available Roles Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Roles de Agente Disponibles</h2>
                    <p class="text-gray-600 text-sm mt-1">Elige un rol base y personalízalo según tus necesidades</p>
                </div>
                <div class="p-6">
                    <div id="available-roles" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Roles will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- User Configurations Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Mis Configuraciones</h2>
                        <p class="text-gray-600 text-sm mt-1">Gestiona tus configuraciones personalizadas de agentes IA</p>
                    </div>
                    <button id="create-config-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        ➕ Nueva Configuración
                    </button>
                </div>
                <div class="p-6">
                    <div id="user-configurations" class="space-y-4">
                        <!-- User configurations will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div id="config-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 id="modal-title" class="text-lg font-semibold text-gray-900">Nueva Configuración de Agente</h3>
                </div>
                <form id="config-form" class="p-6 space-y-6">
                    <input type="hidden" id="config-id">
                    
                    <!-- Agent Role Selection -->
                    <div>
                        <label for="agent-role-select" class="block text-sm font-medium text-gray-700 mb-2">
                            Rol Base del Agente
                        </label>
                        <select id="agent-role-select" name="agent_role_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecciona un rol...</option>
                        </select>
                    </div>

                    <!-- Configuration Name -->
                    <div>
                        <label for="config-name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de la Configuración
                        </label>
                        <input type="text" id="config-name" name="name" required 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ej: Mi Asistente de RH Personalizado">
                    </div>

                    <!-- Custom Prompt -->
                    <div>
                        <label for="custom-prompt" class="block text-sm font-medium text-gray-700 mb-2">
                            Prompt Personalizado (Opcional)
                        </label>
                        <textarea id="custom-prompt" name="custom_prompt" rows="4"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Personaliza las instrucciones adicionales para el agente..."></textarea>
                        <p class="text-sm text-gray-500 mt-1">
                            Estas instrucciones se añadirán al comportamiento base del rol seleccionado.
                        </p>
                    </div>

                    <!-- Default Setting -->
                    <div class="flex items-center">
                        <input type="checkbox" id="is-default" name="is_default" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is-default" class="ml-2 block text-sm text-gray-700">
                            Usar como configuración por defecto
                        </label>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancel-btn" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let availableRoles = [];
    let userConfigurations = [];
    let providerConfiguration = null;
    let editingConfigId = null;

    // Load initial data
    Promise.all([
        fetch('/agent-config/provider').then(r => r.json()),
        fetch('/agent-config/roles').then(r => r.json()),
        fetch('/agent-config/settings').then(r => r.json())
    ]).then(([providerResponse, rolesResponse, settingsResponse]) => {
        if (providerResponse.success) {
            providerConfiguration = providerResponse.configuration;
            renderProviderConfiguration();
        }

        if (rolesResponse.success) {
            availableRoles = rolesResponse.roles;
            renderAvailableRoles();
        }
        
        if (settingsResponse.success) {
            userConfigurations = settingsResponse.settings;
            renderUserConfigurations();
        }
        
        document.getElementById('loading').classList.add('hidden');
        document.getElementById('content').classList.remove('hidden');
    }).catch(error => {
        console.error('Error loading data:', error);
        document.getElementById('loading').innerHTML = '<p class="text-red-600">Error cargando datos</p>';
    });

    function renderProviderConfiguration() {
        const badge = document.getElementById('provider-status-badge');
        const panel = document.getElementById('provider-config-panel');

        if (!providerConfiguration) {
            badge.textContent = 'Sin datos';
            badge.className = 'text-xs font-semibold px-3 py-1 rounded-full bg-red-100 text-red-700 w-fit';
            panel.innerHTML = '<p class="text-sm text-red-600">No se pudo cargar la configuración del proveedor.</p>';
            return;
        }

        const activeProvider = providerConfiguration.active_provider;
        const providers = Object.values(providerConfiguration.providers || {});
        const activeConfig = providerConfiguration.providers?.[activeProvider];

        badge.textContent = `Activo: ${activeConfig?.label || activeProvider}`;
        badge.className = 'text-xs font-semibold px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 w-fit';

        panel.innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,320px)_minmax(0,1fr)] gap-6 items-start">
                <div class="space-y-4">
                    <div>
                        <label for="active-provider-select" class="block text-sm font-medium text-gray-700 mb-2">Proveedor por defecto</label>
                        <select id="active-provider-select" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            ${providers.map(provider => `
                                <option value="${provider.key}" ${provider.key === activeProvider ? 'selected' : ''}>
                                    ${provider.label}${provider.configured ? '' : ' (sin configurar)'}
                                </option>
                            `).join('')}
                        </select>
                    </div>
                    <button id="save-provider-btn" class="bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Guardar proveedor activo
                    </button>
                    <p id="provider-feedback" class="text-sm text-gray-500"></p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    ${providers.map(provider => `
                        <div class="border rounded-lg p-4 ${provider.is_active ? 'border-emerald-300 bg-emerald-50' : 'border-gray-200 bg-gray-50'}">
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <h3 class="font-semibold text-gray-900">${provider.label}</h3>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full ${provider.configured ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700'}">
                                    ${provider.configured ? 'Configurado' : 'Sin credenciales'}
                                </span>
                            </div>
                            <dl class="space-y-2 text-sm text-gray-600">
                                <div>
                                    <dt class="font-medium text-gray-800">Base URL</dt>
                                    <dd class="break-all">${provider.base_url || 'No definida'}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-800">Modelo</dt>
                                    <dd>${provider.model || 'No definido'}</dd>
                                </div>
                            </dl>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;

        document.getElementById('save-provider-btn').addEventListener('click', updateProviderConfiguration);
    }

    function updateProviderConfiguration() {
        const select = document.getElementById('active-provider-select');
        const feedback = document.getElementById('provider-feedback');

        feedback.textContent = 'Guardando proveedor activo...';
        feedback.className = 'text-sm text-gray-500';

        fetch('/agent-config/provider', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ provider: select.value })
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'No se pudo actualizar el proveedor.');
            }

            providerConfiguration = data.configuration;
            renderProviderConfiguration();
            const nextFeedback = document.getElementById('provider-feedback');
            if (nextFeedback) {
                nextFeedback.textContent = data.message || 'Proveedor actualizado.';
                nextFeedback.className = 'text-sm text-emerald-600';
            }
        })
        .catch(error => {
            feedback.textContent = error.message;
            feedback.className = 'text-sm text-red-600';
        });
    }

    // Render available roles
    function renderAvailableRoles() {
        const container = document.getElementById('available-roles');
        container.innerHTML = availableRoles.map(role => `
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer" 
                 onclick="createConfigFromRole('${role.id}', '${role.name}')">
                <div class="flex items-center mb-2">
                    <span class="text-2xl mr-3">${role.icon}</span>
                    <h3 class="font-semibold text-gray-900">${role.name}</h3>
                    ${role.is_default ? '<span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Por defecto</span>' : ''}
                </div>
                <p class="text-gray-600 text-sm">${role.description}</p>
                <div class="mt-3 flex flex-wrap gap-1">
                    ${role.capabilities.map(cap => `
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">${cap}</span>
                    `).join('')}
                </div>
            </div>
        `).join('');
    }

    // Render user configurations
    function renderUserConfigurations() {
        const container = document.getElementById('user-configurations');
        
        if (userConfigurations.length === 0) {
            container.innerHTML = `
                <div class="text-center py-12 text-gray-500">
                    <div class="text-4xl mb-4">🤖</div>
                    <p class="text-lg font-medium mb-2">No tienes configuraciones personalizadas</p>
                    <p class="text-sm">Crea tu primera configuración haciendo clic en "Nueva Configuración"</p>
                </div>
            `;
            return;
        }

        container.innerHTML = userConfigurations.map(config => `
            <div class="border border-gray-200 rounded-lg p-4 ${config.is_default ? 'border-blue-300 bg-blue-50' : ''}">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <span class="text-xl mr-3">${config.agent_role.icon}</span>
                            <h3 class="font-semibold text-gray-900">${config.name}</h3>
                            ${config.is_default ? '<span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Por defecto</span>' : ''}
                        </div>
                        <p class="text-sm text-gray-600 mb-2">Basado en: ${config.agent_role.name}</p>
                        ${config.custom_prompt ? `<p class="text-sm text-gray-700 italic">"${config.custom_prompt.substring(0, 100)}..."</p>` : ''}
                    </div>
                    <div class="flex space-x-2 ml-4">
                        <button onclick="editConfiguration(${config.id})" 
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            ✏️ Editar
                        </button>
                        ${!config.is_default ? `
                            <button onclick="deleteConfiguration(${config.id})" 
                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                🗑️ Eliminar
                            </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        `).join('');
    }

    // Populate agent role select
    function populateAgentRoleSelect() {
        const select = document.getElementById('agent-role-select');
        select.innerHTML = '<option value="">Selecciona un rol...</option>' + 
            availableRoles.map(role => `
                <option value="${role.id}">${role.icon} ${role.name}</option>
            `).join('');
    }

    // Create config from role
    window.createConfigFromRole = function(roleId, roleName) {
        editingConfigId = null;
        document.getElementById('modal-title').textContent = 'Nueva Configuración de Agente';
        document.getElementById('config-form').reset();
        populateAgentRoleSelect();
        document.getElementById('agent-role-select').value = roleId;
        document.getElementById('config-name').value = `Mi ${roleName} Personalizado`;
        document.getElementById('config-modal').classList.remove('hidden');
        document.getElementById('config-modal').classList.add('flex');
    };

    // Edit configuration
    window.editConfiguration = function(configId) {
        const config = userConfigurations.find(c => c.id === configId);
        if (!config) return;

        editingConfigId = configId;
        document.getElementById('modal-title').textContent = 'Editar Configuración';
        populateAgentRoleSelect();
        
        document.getElementById('config-id').value = config.id;
        document.getElementById('agent-role-select').value = config.agent_role_id;
        document.getElementById('config-name').value = config.name;
        document.getElementById('custom-prompt').value = config.custom_prompt || '';
        document.getElementById('is-default').checked = config.is_default;
        
        document.getElementById('config-modal').classList.remove('hidden');
        document.getElementById('config-modal').classList.add('flex');
    };

    // Delete configuration
    window.deleteConfiguration = function(configId) {
        if (!confirm('¿Estás seguro de que deseas eliminar esta configuración?')) return;

        fetch(`/agent-config/settings/${configId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(r => r.json())
        .then(response => {
            if (response.success) {
                userConfigurations = userConfigurations.filter(c => c.id !== configId);
                renderUserConfigurations();
            } else {
                alert('Error al eliminar la configuración');
            }
        });
    };

    // Event listeners
    document.getElementById('create-config-btn').addEventListener('click', function() {
        editingConfigId = null;
        document.getElementById('modal-title').textContent = 'Nueva Configuración de Agente';
        document.getElementById('config-form').reset();
        populateAgentRoleSelect();
        document.getElementById('config-modal').classList.remove('hidden');
        document.getElementById('config-modal').classList.add('flex');
    });

    document.getElementById('cancel-btn').addEventListener('click', function() {
        document.getElementById('config-modal').classList.add('hidden');
        document.getElementById('config-modal').classList.remove('flex');
    });

    // Form submission
    document.getElementById('config-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        data.is_default = document.getElementById('is-default').checked;

        const url = editingConfigId ? 
            `/agent-config/settings/${editingConfigId}` : 
            '/agent-config/settings';
        const method = editingConfigId ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(r => r.json())
        .then(response => {
            if (response.success) {
                if (editingConfigId) {
                    const index = userConfigurations.findIndex(c => c.id == editingConfigId);
                    userConfigurations[index] = response.setting;
                } else {
                    userConfigurations.push(response.setting);
                }
                renderUserConfigurations();
                document.getElementById('config-modal').classList.add('hidden');
                document.getElementById('config-modal').classList.remove('flex');
            } else {
                alert('Error al guardar la configuración');
            }
        });
    });

    // Close modal on outside click
    document.getElementById('config-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
            this.classList.remove('flex');
        }
    });
});
</script>
@endsection