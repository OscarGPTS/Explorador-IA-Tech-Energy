<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use App\Models\Log;
use App\Models\AgentRole;
use App\Models\ChatConfiguration;
use App\Exports\ModuleUsageExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminStatsController extends Controller
{
    public function dashboard()
    {
        // Estadísticas generales
        $totalUsers = User::count();
        $activeUsersToday = User::whereDate('updated_at', today())->count();
        $totalChats = Chat::count();
        $chatsToday = Chat::whereDate('created_at', today())->count();

        // Usuarios registrados en los últimos 30 días
        $usersLast30Days = User::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Actividad de chat en los últimos 7 días
        $chatActivityLast7Days = Chat::where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Usuarios más activos (por número de chats)
        $mostActiveUsers = User::select('users.id', 'users.name', 'users.email', DB::raw('COUNT(chats.id) as chat_count'))
            ->leftJoin('chats', 'users.id', '=', 'chats.emisor_id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('chat_count')
            ->limit(10)
            ->get();

        // Configuraciones de agentes más utilizadas
        $popularAgentConfigs = AgentRole::select('agent_roles.name', 'agent_roles.description', DB::raw('COUNT(user_agent_settings.id) as usage_count'))
            ->leftJoin('user_agent_settings', 'agent_roles.id', '=', 'user_agent_settings.agent_role_id')
            ->groupBy('agent_roles.id', 'agent_roles.name', 'agent_roles.description')
            ->orderByDesc('usage_count')
            ->limit(5)
            ->get();

        // Distribución de mensajes por tipo (usuario vs IA)
        $messageDistribution = collect([
            (object)[
                'sender' => 'user',
                'count' => Chat::whereNotNull('emisor_id')->count()
            ],
            (object)[
                'sender' => 'ia',
                'count' => Chat::whereNull('emisor_id')->count()
            ]
        ]);

        // Horarios de mayor actividad
        $activityByHour = Chat::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Estadísticas del último mes
        $lastMonthStats = [
            'new_users' => User::where('created_at', '>=', now()->subMonth())->count(),
            'total_messages' => Chat::where('created_at', '>=', now()->subMonth())->count(),
            'active_configurations' => ChatConfiguration::where('is_active', true)->count(),
            'avg_messages_per_user' => Chat::where('created_at', '>=', now()->subMonth())
                ->whereNotNull('emisor_id')
                ->selectRaw('emisor_id, COUNT(*) as count')
                ->groupBy('emisor_id')
                ->get()
                ->avg('count')
        ];

        // Estadísticas de uso por módulos/apps
        $moduleUsage = Log::select('type', DB::raw('COUNT(*) as usage_count'), DB::raw('COUNT(DISTINCT user_id) as unique_users'))
            ->groupBy('type')
            ->orderByDesc('usage_count')
            ->get();

        // Uso de módulos en los últimos 30 días
        $moduleUsageLast30Days = Log::select('type', DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('type', 'date')
            ->orderBy('date')
            ->get()
            ->groupBy('type');

        // Módulos más populares por usuarios únicos
        $topModulesByUsers = Log::select('type', DB::raw('COUNT(DISTINCT user_id) as unique_users'))
            ->groupBy('type')
            ->orderByDesc('unique_users')
            ->limit(5)
            ->get();

        // Actividad por módulo en las últimas 24 horas
        $moduleActivityToday = Log::select('type', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDay())
            ->groupBy('type')
            ->orderByDesc('count')
            ->get();

        return view('admin.stats.dashboard', compact(
            'totalUsers',
            'activeUsersToday', 
            'totalChats',
            'chatsToday',
            'usersLast30Days',
            'chatActivityLast7Days',
            'mostActiveUsers',
            'popularAgentConfigs',
            'messageDistribution',
            'activityByHour',
            'lastMonthStats',
            'moduleUsage',
            'moduleUsageLast30Days',
            'topModulesByUsers',
            'moduleActivityToday'
        ));
    }

    public function users()
    {
        // Estadísticas detalladas de usuarios
        $totalUsers = User::count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $googleUsers = User::whereNotNull('google_id')->count();
        $regularUsers = User::whereNull('google_id')->count();

        // Usuarios por mes de registro
        $usersByMonth = User::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Usuarios más activos con detalles
        $activeUsersDetailed = User::select(
                'users.id',
                'users.name', 
                'users.email',
                'users.created_at',
                'users.updated_at',
                DB::raw('COUNT(chats.id) as total_messages'),
                DB::raw('COUNT(DISTINCT DATE(chats.created_at)) as active_days'),
                DB::raw('MAX(chats.created_at) as last_message_at')
            )
            ->leftJoin('chats', 'users.id', '=', 'chats.emisor_id')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.created_at', 'users.updated_at')
            ->orderByDesc('total_messages')
            ->limit(20)
            ->get();

        // Actividad por día de la semana
        $activityByDayOfWeek = Chat::selectRaw('DAYOFWEEK(created_at) as day_of_week, COUNT(*) as count')
            ->groupBy('day_of_week')
            ->orderBy('day_of_week')
            ->get()
            ->map(function($item) {
                $days = ['', 'Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                $item->day_name = $days[$item->day_of_week];
                return $item;
            });

        return view('admin.stats.users', compact(
            'totalUsers',
            'verifiedUsers',
            'googleUsers',
            'regularUsers',
            'usersByMonth',
            'activeUsersDetailed',
            'activityByDayOfWeek'
        ));
    }

    public function chats()
    {
        // Estadísticas de chat
        $totalChats = Chat::count();
        $totalGroups = Chat::distinct('chatgroup_id')->count();
        $avgMessagesPerGroup = $totalGroups > 0 ? round($totalChats / $totalGroups, 2) : 0;

        // Conversaciones más largas
        $longestConversations = Chat::select('chatgroup_id', 'emisor_id', DB::raw('COUNT(*) as message_count'))
            ->with('user:id,name,email')
            ->whereNotNull('emisor_id')
            ->groupBy('chatgroup_id', 'emisor_id')
            ->orderByDesc('message_count')
            ->limit(10)
            ->get();

        // Actividad de chat por hora del día
        $chatsByHour = Chat::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->pluck('count', 'hour')
            ->toArray();

        // Rellenar horas faltantes con 0
        $hourlyActivity = [];
        for ($i = 0; $i < 24; $i++) {
            $hourlyActivity[$i] = $chatsByHour[$i] ?? 0;
        }

        // Mensajes por tipo en los últimos 30 días
        $messageTypeStats = collect([
            (object)[
                'sender' => 'user',
                'count' => Chat::where('created_at', '>=', now()->subDays(30))->whereNotNull('emisor_id')->count()
            ],
            (object)[
                'sender' => 'ia',
                'count' => Chat::where('created_at', '>=', now()->subDays(30))->whereNull('emisor_id')->count()
            ]
        ]);

        // Usuarios con más mensajes en la última semana
        $weeklyTopUsers = User::select('users.name', 'users.email', DB::raw('COUNT(chats.id) as weekly_messages'))
            ->join('chats', 'users.id', '=', 'chats.emisor_id')
            ->where('chats.created_at', '>=', now()->subWeek())
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('weekly_messages')
            ->limit(10)
            ->get();

        return view('admin.stats.chats', compact(
            'totalChats',
            'totalGroups',
            'avgMessagesPerGroup',
            'longestConversations',
            'hourlyActivity',
            'messageTypeStats',
            'weeklyTopUsers'
        ));
    }

    public function modules()
    {
        // Estadísticas detalladas de módulos
        $totalLogs = Log::count();
        $totalModules = Log::distinct('type')->count();
        $logsToday = Log::whereDate('created_at', today())->count();
        $uniqueUsersToday = Log::whereDate('created_at', today())->distinct('user_id')->count();

        // Módulos más usados
        $moduleUsageStats = Log::select(
                'type',
                DB::raw('COUNT(*) as total_usage'),
                DB::raw('COUNT(DISTINCT user_id) as unique_users'),
                DB::raw('AVG(CASE WHEN status_code = "200" THEN 1 ELSE 0 END) * 100 as success_rate'),
                DB::raw('MIN(created_at) as first_used'),
                DB::raw('MAX(created_at) as last_used')
            )
            ->groupBy('type')
            ->orderByDesc('total_usage')
            ->get();

        // Actividad por módulo en los últimos 7 días
        $weeklyModuleActivity = Log::select(
                'type',
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as usage_count')
            )
            ->where('created_at', '>=', now()->subWeek())
            ->groupBy('type', 'date')
            ->orderBy('date')
            ->get()
            ->groupBy('type');

        // Usuarios más activos por módulo
        $topUsersByModule = Log::select(
                'type',
                'user_id',
                'users.name',
                'users.email',
                DB::raw('COUNT(*) as usage_count')
            )
            ->join('users', 'logs.user_id', '=', 'users.id')
            ->groupBy('type', 'user_id', 'users.name', 'users.email')
            ->orderByDesc('usage_count')
            ->get()
            ->groupBy('type');

        // Análisis de errores por módulo
        $moduleErrors = Log::select(
                'type',
                DB::raw('COUNT(*) as total_requests'),
                DB::raw('SUM(CASE WHEN status_code != "200" THEN 1 ELSE 0 END) as error_count'),
                DB::raw('(SUM(CASE WHEN status_code != "200" THEN 1 ELSE 0 END) / COUNT(*)) * 100 as error_rate')
            )
            ->groupBy('type')
            ->havingRaw('COUNT(*) > 0')
            ->orderByDesc('error_rate')
            ->get();

        // Patrones de uso por hora del día
        $hourlyUsageByModule = Log::select(
                'type',
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as usage_count')
            )
            ->groupBy('type', 'hour')
            ->orderBy('hour')
            ->get()
            ->groupBy('type');

        return view('admin.stats.modules', compact(
            'totalLogs',
            'totalModules',
            'logsToday',
            'uniqueUsersToday',
            'moduleUsageStats',
            'weeklyModuleActivity',
            'topUsersByModule',
            'moduleErrors',
            'hourlyUsageByModule'
        ));
    }

    public function errors(Request $request)
    {
        // Filtros
        $startDate = $request->get('start_date', now()->subDays(7)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $moduleType = $request->get('module_type');
        $statusCode = $request->get('status_code');
        
        // Query base para errores (códigos >= 400 o con error_details)
        $query = Log::with('user:id,name,email')
            ->where(function($q) {
                $q->where('status_code', '>=', 400)
                  ->orWhereNotNull('error_details');
            });
            
        // Aplicar filtros
        if ($startDate) {
            $query->where('created_at', '>=', $startDate . ' 00:00:00');
        }
        if ($endDate) {
            $query->where('created_at', '<=', $endDate . ' 23:59:59');
        }
        if ($moduleType) {
            $query->where('type', $moduleType);
        }
        if ($statusCode) {
            $query->where('status_code', $statusCode);
        }
        
        // Obtener errores paginados
        $errors = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Estadísticas de errores
        $errorStats = [
            'total_errors' => Log::where('status_code', '>=', 400)
                ->orWhereNotNull('error_details')
                ->count(),
            'errors_today' => Log::where('created_at', '>=', today())
                ->where(function($q) {
                    $q->where('status_code', '>=', 400)
                      ->orWhereNotNull('error_details');
                })
                ->count(),
            'most_common_error' => Log::where('status_code', '>=', 400)
                ->orWhereNotNull('error_details')
                ->selectRaw('status_code, COUNT(*) as count')
                ->groupBy('status_code')
                ->orderByDesc('count')
                ->first(),
            'errors_by_module' => Log::where('status_code', '>=', 400)
                ->orWhereNotNull('error_details')
                ->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->orderByDesc('count')
                ->get()
        ];
        
        // Códigos de estado disponibles para filtro
        $availableStatusCodes = Log::where('status_code', '>=', 400)
            ->distinct()
            ->pluck('status_code')
            ->sort()
            ->values();
            
        // Tipos de módulo disponibles
        $availableModules = Log::distinct()->pluck('type')->sort()->values();
        
        return view('admin.stats.errors', compact(
            'errors',
            'errorStats',
            'availableStatusCodes',
            'availableModules',
            'startDate',
            'endDate',
            'moduleType',
            'statusCode'
        ));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'users');
        $format = $request->get('format', 'json');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $moduleType = $request->get('module_type');

        // Exportación específica para módulos en Excel
        if ($type === 'modules' && in_array($format, ['excel', 'xlsx'])) {
            $filename = 'modulos_uso_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            return Excel::download(new ModuleUsageExport($startDate, $endDate, $moduleType), $filename);
        }

        $data = [];
        
        switch($type) {
            case 'users':
                $data = User::with(['chats' => function($query) {
                    $query->selectRaw('emisor_id, COUNT(*) as message_count')
                        ->groupBy('emisor_id');
                }])->get();
                break;
            case 'chats':
                $data = Chat::with('user:id,name,email')->get();
                break;
            case 'modules':
                $query = Log::with('user:id,name,email');
                if ($startDate) $query->where('created_at', '>=', $startDate);
                if ($endDate) $query->where('created_at', '<=', $endDate);
                if ($moduleType) $query->where('type', $moduleType);
                $data = $query->orderBy('created_at', 'desc')->get();
                break;
            case 'errors':
                $query = Log::with('user:id,name,email')
                    ->where(function($q) {
                        $q->where('status_code', '>=', 400)
                          ->orWhereNotNull('error_details');
                    });
                if ($startDate) $query->where('created_at', '>=', $startDate);
                if ($endDate) $query->where('created_at', '<=', $endDate);
                if ($moduleType) $query->where('type', $moduleType);
                $data = $query->orderBy('created_at', 'desc')->get();
                break;
        }

        if ($format === 'csv') {
            return response()->streamDownload(function() use ($data, $type) {
                $file = fopen('php://output', 'w');
                
                if ($type === 'users' && $data->count() > 0) {
                    fputcsv($file, ['ID', 'Nombre', 'Email', 'Fecha Registro', 'Total Mensajes']);
                    foreach ($data as $user) {
                        fputcsv($file, [
                            $user->id,
                            $user->name,
                            $user->email,
                            $user->created_at->format('Y-m-d H:i:s'),
                            $user->chats->sum('message_count') ?? 0
                        ]);
                    }
                } elseif ($type === 'modules' && $data->count() > 0) {
                    fputcsv($file, ['ID', 'Módulo', 'Usuario', 'Email', 'Actividad', 'Estado', 'Fecha', 'Método', 'URL', 'IP', 'Tiempo Respuesta', 'Error Details']);
                    foreach ($data as $log) {
                        $errorDetails = '';
                        if ($log->error_details) {
                            $errorData = json_decode($log->error_details, true);
                            $errorDetails = $errorData['exception_class'] ?? 'Error';
                        }
                        
                        fputcsv($file, [
                            $log->id,
                            ucfirst($log->type),
                            $log->user ? $log->user->name : 'Usuario eliminado',
                            $log->user ? $log->user->email : 'N/A',
                            $log->message,
                            $log->status_code,
                            $log->created_at->format('Y-m-d H:i:s'),
                            $log->method ?? 'N/A',
                            $log->url ?? 'N/A',
                            $log->ip_address ?? 'N/A',
                            $log->response_time ?? 'N/A',
                            $errorDetails
                        ]);
                    }
                } elseif ($type === 'errors' && $data->count() > 0) {
                    fputcsv($file, ['ID', 'Módulo', 'Usuario', 'Email', 'Error', 'Estado', 'Fecha', 'Método', 'URL', 'IP', 'Tiempo Respuesta', 'Exception Class', 'Archivo', 'Línea', 'Request Data', 'Stack Trace']);
                    foreach ($data as $log) {
                        $errorData = json_decode($log->error_details, true) ?? [];
                        $requestData = json_encode($log->request_data) ?? '';
                        
                        fputcsv($file, [
                            $log->id,
                            ucfirst($log->type),
                            $log->user ? $log->user->name : 'Usuario eliminado',
                            $log->user ? $log->user->email : 'N/A',
                            $log->message,
                            $log->status_code,
                            $log->created_at->format('Y-m-d H:i:s'),
                            $log->method ?? 'N/A',
                            $log->url ?? 'N/A',
                            $log->ip_address ?? 'N/A',
                            $log->response_time ?? 'N/A',
                            $errorData['exception_class'] ?? 'N/A',
                            $errorData['file'] ?? 'N/A',
                            $errorData['line'] ?? 'N/A',
                            strlen($requestData) > 500 ? substr($requestData, 0, 500) . '...' : $requestData,
                            strlen($log->stack_trace ?? '') > 1000 ? substr($log->stack_trace, 0, 1000) . '...' : ($log->stack_trace ?? 'N/A')
                        ]);
                    }
                }
                
                fclose($file);
            }, "stats_{$type}_" . now()->format('Y-m-d') . '.csv');
        }

        return response()->json($data);
    }
}
