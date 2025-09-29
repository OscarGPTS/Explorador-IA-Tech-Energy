<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RecommendationRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->resolveRequestSignature($request);
        
        // Diferentes límites según el tipo de operación
        $limits = $this->getOperationLimits($request);
        
        foreach ($limits as $limit) {
            $executed = RateLimiter::attempt(
                $key . ':' . $limit['operation'],
                $limit['maxAttempts'],
                function () {
                    return true;
                },
                $limit['decayMinutes'] * 60
            );

            if (!$executed) {
                return response()->json([
                    'success' => false,
                    'message' => "Límite de {$limit['operation']} excedido. Intenta nuevamente en {$limit['decayMinutes']} minutos.",
                    'retry_after' => RateLimiter::availableIn($key . ':' . $limit['operation'])
                ], 429);
            }
        }

        return $next($request);
    }

    /**
     * Resolve la signature única para el request
     */
    protected function resolveRequestSignature(Request $request): string
    {
        if ($user = $request->user()) {
            return 'recommendation_api:user:' . $user->id;
        }

        return 'recommendation_api:ip:' . $request->ip();
    }

    /**
     * Obtiene los límites según el tipo de operación
     */
    protected function getOperationLimits(Request $request): array
    {
        $path = $request->path();
        $method = $request->method();

        // Operaciones de generación (más costosas)
        if (str_contains($path, 'generate')) {
            return [
                [
                    'operation' => 'generate',
                    'maxAttempts' => 10,  // 10 generaciones por hora
                    'decayMinutes' => 60
                ],
                [
                    'operation' => 'generate_daily',
                    'maxAttempts' => 50,  // 50 generaciones por día
                    'decayMinutes' => 1440
                ]
            ];
        }

        // Operaciones de lectura (menos costosas)
        if ($method === 'GET') {
            return [
                [
                    'operation' => 'read',
                    'maxAttempts' => 100, // 100 consultas por hora
                    'decayMinutes' => 60
                ]
            ];
        }

        // Límite general
        return [
            [
                'operation' => 'general',
                'maxAttempts' => 60,  // 60 requests por hora
                'decayMinutes' => 60
            ]
        ];
    }
}
