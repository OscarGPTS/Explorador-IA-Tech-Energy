<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class AiProviderService
{
    public const PROVIDER_OPENAI = 'openai';
    public const PROVIDER_OPENCODE = 'opencode';
    private const DEFAULT_PROVIDER_SETTING = 'ai.default_provider';
    private const MODEL_SETTING_PREFIX = 'ai.model.';

    public function createChatCompletion(array $messages, array $options = []): array
    {
        $provider = $options['provider'] ?? $this->getActiveProvider();
        $config = $this->getProviderConfig($provider);

        if (empty($config['api_key'])) {
            throw new RuntimeException("API key no configurada para el proveedor {$provider}.");
        }

        $payload = array_filter([
            'model' => $options['model'] ?? $config['model'],
            'messages' => $messages,
            'max_tokens' => $options['max_tokens'] ?? null,
            'temperature' => $options['temperature'] ?? null,
        ], static fn ($value) => $value !== null);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $config['api_key'],
            'Content-Type' => 'application/json',
        ])
            ->timeout((int) ($config['timeout'] ?? 60))
            ->connectTimeout((int) ($config['connect_timeout'] ?? 30))
            ->withOptions(['verify' => false])
            ->post($this->buildChatCompletionsUrl($config['base_url']), $payload);

        if ($response->failed()) {
            throw new RuntimeException(
                'Error HTTP del proveedor de IA: ' . $response->status() . ' - ' . $response->body()
            );
        }

        $data = $response->json();
        $content = trim((string) data_get($data, 'choices.0.message.content', ''));

        if ($content === '') {
            throw new RuntimeException('La respuesta del proveedor de IA no contiene contenido utilizable.');
        }

        return [
            'provider' => $provider,
            'model' => $payload['model'],
            'content' => $content,
            'raw' => $data,
        ];
    }

    public function getActiveProvider(): string
    {
        $configuredProvider = SystemSetting::getValue(self::DEFAULT_PROVIDER_SETTING);

        if (is_string($configuredProvider) && $this->isSupportedProvider($configuredProvider)) {
            return $configuredProvider;
        }

        $fallbackProvider = (string) config('ai.default_provider', self::PROVIDER_OPENAI);

        return $this->isSupportedProvider($fallbackProvider)
            ? $fallbackProvider
            : self::PROVIDER_OPENAI;
    }

    public function setActiveProvider(string $provider): void
    {
        if (! $this->isSupportedProvider($provider)) {
            throw new RuntimeException('Proveedor de IA no soportado.');
        }

        SystemSetting::setValue(self::DEFAULT_PROVIDER_SETTING, $provider);
    }

    /**
     * Modelos disponibles para un proveedor (id => etiqueta).
     */
    public function getAvailableModels(string $provider): array
    {
        if (! $this->isSupportedProvider($provider)) {
            throw new RuntimeException('Proveedor de IA no soportado.');
        }

        return (array) config("ai.providers.{$provider}.models", []);
    }

    /**
     * Modelo activo de un proveedor: el guardado en SystemSetting (si es válido)
     * o el modelo por defecto del config.
     */
    public function getActiveModel(string $provider): ?string
    {
        $defaultModel = config("ai.providers.{$provider}.model");
        $availableModels = $this->getAvailableModels($provider);

        $configuredModel = SystemSetting::getValue(self::MODEL_SETTING_PREFIX . $provider);

        if (is_string($configuredModel) && $configuredModel !== '') {
            // Si hay catálogo definido, validamos contra él; si no, aceptamos el guardado.
            if (empty($availableModels) || array_key_exists($configuredModel, $availableModels)) {
                return $configuredModel;
            }
        }

        return $defaultModel;
    }

    /**
     * Fijar el modelo activo de un proveedor.
     */
    public function setActiveModel(string $provider, string $model): void
    {
        if (! $this->isSupportedProvider($provider)) {
            throw new RuntimeException('Proveedor de IA no soportado.');
        }

        $availableModels = $this->getAvailableModels($provider);

        if (! empty($availableModels) && ! array_key_exists($model, $availableModels)) {
            throw new RuntimeException('Modelo no disponible para el proveedor seleccionado.');
        }

        SystemSetting::setValue(self::MODEL_SETTING_PREFIX . $provider, $model);
    }

    public function getProviderSummary(): array
    {
        $activeProvider = $this->getActiveProvider();

        return [
            'active_provider' => $activeProvider,
            'providers' => collect($this->getSupportedProviders())
                ->mapWithKeys(function (string $provider) use ($activeProvider) {
                    $config = $this->getProviderConfig($provider);

                    return [
                        $provider => [
                            'key' => $provider,
                            'label' => $config['label'],
                            'base_url' => $config['base_url'],
                            'model' => $config['model'],
                            'models' => $config['models'],
                            'configured' => ! empty($config['api_key']),
                            'is_active' => $provider === $activeProvider,
                        ],
                    ];
                })
                ->all(),
        ];
    }

    public function getSupportedProviders(): array
    {
        return [
            self::PROVIDER_OPENAI,
            self::PROVIDER_OPENCODE,
        ];
    }

    private function getProviderConfig(string $provider): array
    {
        if (! $this->isSupportedProvider($provider)) {
            throw new RuntimeException('Proveedor de IA no soportado.');
        }

        $config = config("ai.providers.{$provider}", []);

        return [
            'label' => Arr::get($config, 'label', ucfirst($provider)),
            'api_key' => Arr::get($config, 'api_key'),
            'base_url' => Arr::get($config, 'base_url'),
            // Modelo efectivo: respeta el seleccionado en /agent-config (SystemSetting)
            // y cae al modelo por defecto del config si no hay selección válida.
            'model' => $this->getActiveModel($provider),
            'models' => Arr::get($config, 'models', []),
            'timeout' => Arr::get($config, 'timeout', 60),
            'connect_timeout' => Arr::get($config, 'connect_timeout', 30),
        ];
    }

    private function buildChatCompletionsUrl(?string $baseUrl): string
    {
        $normalizedBaseUrl = rtrim((string) $baseUrl, '/');

        if ($normalizedBaseUrl === '') {
            throw new RuntimeException('Base URL no configurada para el proveedor de IA.');
        }

        return $normalizedBaseUrl . '/chat/completions';
    }

    private function isSupportedProvider(string $provider): bool
    {
        return in_array($provider, $this->getSupportedProviders(), true);
    }
}