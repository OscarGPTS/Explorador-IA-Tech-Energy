<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use App\Models\AgentRole;
use App\Models\ChatConfiguration;
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
            'lastMonthStats'
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

    public function export(Request $request)
    {
        $type = $request->get('type', 'users');
        $format = $request->get('format', 'json');

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
                }
                
                fclose($file);
            }, "stats_{$type}_" . now()->format('Y-m-d') . '.csv');
        }

        return response()->json($data);
    }
}
