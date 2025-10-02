<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo registrar si el usuario está autenticado
        if (Auth::check()) {
            $user = Auth::user();
            $uri = $request->getRequestUri();
            $method = $request->getMethod();
            
            // Determinar el tipo de módulo basado en la URL
            $type = $this->determineModuleType($uri);
            
            // Solo registrar si es una actividad relevante
            if ($type && $method === 'GET' && !$this->shouldSkipLogging($uri)) {
                Log::create([
                    'type' => $type,
                    'message' => $this->generateMessage($type, $uri, $method),
                    'status_code' => $response->getStatusCode(),
                    'user_id' => $user->id
                ]);
            }
        }

        return $response;
    }

    /**
     * Determinar el tipo de módulo basado en la URI
     */
    private function determineModuleType(string $uri): ?string
    {
        if (str_contains($uri, '/chat')) {
            return 'chat';
        }
        
        if (str_contains($uri, '/news') || str_contains($uri, '/noticias')) {
            return 'news';
        }
        
        if (str_contains($uri, '/recommendations') || str_contains($uri, '/recomendaciones')) {
            return 'recommendations';
        }
        
        if (str_contains($uri, '/admin/employees')) {
            return 'employee_management';
        }
        
        if (str_contains($uri, '/admin/stats')) {
            return 'analytics';
        }
        
        if (str_contains($uri, '/admin')) {
            return 'admin_panel';
        }
        
        if (str_contains($uri, '/profile') || str_contains($uri, '/perfil')) {
            return 'profile';
        }
        
        if (str_contains($uri, '/dashboard') || $uri === '/' || $uri === '/home') {
            return 'dashboard';
        }

        return null;
    }

    /**
     * Generar mensaje descriptivo para el log
     */
    private function generateMessage(string $type, string $uri, string $method): string
    {
        $messages = [
            'chat' => 'Acceso al módulo de Chat',
            'news' => 'Consulta de Noticias',
            'recommendations' => 'Revisión de Recomendaciones',
            'employee_management' => 'Gestión de Empleados',
            'analytics' => 'Consulta de Estadísticas',
            'admin_panel' => 'Acceso al Panel de Administración',
            'profile' => 'Acceso al Perfil de Usuario',
            'dashboard' => 'Acceso al Dashboard Principal'
        ];

        return $messages[$type] ?? "Actividad en {$type}";
    }

    /**
     * Determinar si se debe omitir el logging para ciertas URLs
     */
    private function shouldSkipLogging(string $uri): bool
    {
        $skipPatterns = [
            '/api/',
            '/livewire/',
            '.js',
            '.css',
            '.ico',
            '.png',
            '.jpg',
            '.jpeg',
            '.gif',
            '.svg',
            '/storage/'
        ];

        foreach ($skipPatterns as $pattern) {
            if (str_contains($uri, $pattern)) {
                return true;
            }
        }

        return false;
    }
}
