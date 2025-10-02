<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Exception;
use Throwable;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        try {
            $response = $next($request);
            
            // Log actividad normal
            $this->logActivity($request, $response, $startTime);
            
            return $response;
            
        } catch (Throwable $exception) {
            // Log errores detallados
            $this->logError($request, $exception, $startTime);
            
            // Re-lanzar la excepción para que Laravel la maneje normalmente
            throw $exception;
        }
    }

    /**
     * Registrar actividad normal del usuario
     */
    private function logActivity(Request $request, Response $response, float $startTime): void
    {
        // Solo registrar si el usuario está autenticado
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();
        $uri = $request->getRequestUri();
        $method = $request->getMethod();
        $responseTime = round((microtime(true) - $startTime) * 1000, 3);
        
        // Determinar el tipo de módulo basado en la URL
        $type = $this->determineModuleType($uri);
        
        // Solo registrar si es una actividad relevante
        if ($type && !$this->shouldSkipLogging($uri)) {
            $logData = [
                'type' => $type,
                'message' => $this->generateMessage($type, $uri, $method),
                'status_code' => $response->getStatusCode(),
                'user_id' => $user->id,
                'method' => $method,
                'url' => $uri,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'response_time' => $responseTime,
            ];

            // Solo capturar request data en casos específicos (errores o métodos importantes)
            if ($response->getStatusCode() >= 400 || in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH'])) {
                $logData['request_data'] = $this->sanitizeRequestData($request);
            }

            // Capturar response data solo en errores
            if ($response->getStatusCode() >= 400) {
                $logData['response_data'] = $this->sanitizeResponseData($response);
            }

            Log::create($logData);
        }
    }

    /**
     * Registrar errores detallados
     */
    private function logError(Request $request, Throwable $exception, float $startTime): void
    {
        $user = Auth::check() ? Auth::user() : null;
        $uri = $request->getRequestUri();
        $method = $request->getMethod();
        $responseTime = round((microtime(true) - $startTime) * 1000, 3);
        
        $type = $this->determineModuleType($uri) ?? 'error';
        
        $errorDetails = [
            'exception_class' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'code' => $exception->getCode(),
        ];

        Log::create([
            'type' => $type,
            'message' => "ERROR: {$exception->getMessage()}",
            'status_code' => $this->getHttpStatusFromException($exception),
            'user_id' => $user?->id,
            'method' => $method,
            'url' => $uri,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'response_time' => $responseTime,
            'request_data' => $this->sanitizeRequestData($request),
            'error_details' => $errorDetails,
            'stack_trace' => $exception->getTraceAsString(),
        ]);
    }

    /**
     * Limpiar y sanitizar datos del request
     */
    private function sanitizeRequestData(Request $request): array
    {
        $data = $request->all();
        
        // Remover campos sensibles
        $sensitiveFields = ['password', 'password_confirmation', '_token', 'api_key', 'secret'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[HIDDEN]';
            }
        }
        
        // Limitar el tamaño de los datos
        return $this->limitDataSize($data);
    }

    /**
     * Limpiar y sanitizar datos del response
     */
    private function sanitizeResponseData(Response $response): array
    {
        $content = $response->getContent();
        
        // Si es JSON, decodificar
        if ($response->headers->get('Content-Type') && str_contains($response->headers->get('Content-Type'), 'json')) {
            $data = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $this->limitDataSize($data);
            }
        }
        
        // Si el contenido es muy largo, truncar
        if (strlen($content) > 1000) {
            $content = substr($content, 0, 1000) . '... [TRUNCATED]';
        }
        
        return ['content' => $content, 'headers' => $response->headers->all()];
    }

    /**
     * Limitar el tamaño de los datos para evitar logs excesivamente grandes
     */
    private function limitDataSize($data, int $maxDepth = 3, int $currentDepth = 0): array
    {
        if ($currentDepth >= $maxDepth) {
            return ['[MAX_DEPTH_REACHED]'];
        }

        if (!is_array($data)) {
            return $data;
        }

        $result = [];
        $count = 0;
        $maxItems = 50;

        foreach ($data as $key => $value) {
            if ($count >= $maxItems) {
                $result['[TRUNCATED]'] = '... más elementos truncados';
                break;
            }

            if (is_array($value)) {
                $result[$key] = $this->limitDataSize($value, $maxDepth, $currentDepth + 1);
            } elseif (is_string($value) && strlen($value) > 500) {
                $result[$key] = substr($value, 0, 500) . '... [TRUNCATED]';
            } else {
                $result[$key] = $value;
            }
            
            $count++;
        }

        return $result;
    }

    /**
     * Obtener código de estado HTTP de una excepción
     */
    private function getHttpStatusFromException(Throwable $exception): int
    {
        // Verificar si es una HttpException que tiene getStatusCode
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            return $exception->getStatusCode();
        }
        
        // Mapear tipos comunes de excepciones a códigos HTTP
        $exceptionMap = [
            'Illuminate\Database\Eloquent\ModelNotFoundException' => 404,
            'Illuminate\Auth\AuthenticationException' => 401,
            'Illuminate\Auth\Access\AuthorizationException' => 403,
            'Illuminate\Validation\ValidationException' => 422,
            'Symfony\Component\HttpKernel\Exception\NotFoundHttpException' => 404,
            'Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException' => 405,
        ];
        
        $exceptionClass = get_class($exception);
        
        return $exceptionMap[$exceptionClass] ?? 500;
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

        $baseMessage = $messages[$type] ?? "Actividad en {$type}";
        
        if ($method !== 'GET') {
            $baseMessage .= " ({$method})";
        }

        return $baseMessage;
    }

    /**
     * Determinar si se debe omitir el logging para ciertas URLs
     */
    private function shouldSkipLogging(string $uri): bool
    {
        $skipPatterns = [
            '/livewire/message/',
            '/livewire/upload-file',
            '.js',
            '.css',
            '.ico',
            '.png',
            '.jpg',
            '.jpeg',
            '.gif',
            '.svg',
            '.woff',
            '.woff2',
            '.ttf',
            '/storage/',
            '/build/',
            '/_debugbar',
            '/telescope',
        ];

        foreach ($skipPatterns as $pattern) {
            if (str_contains($uri, $pattern)) {
                return true;
            }
        }

        return false;
    }
}
