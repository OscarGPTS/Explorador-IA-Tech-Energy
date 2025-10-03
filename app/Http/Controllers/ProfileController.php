<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\Log;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display the user's profile with statistics.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Estadísticas generales del usuario
        $stats = [
            'total_activities' => $user->logs()->count(),
            'total_sessions' => $user->logs()->where('type', 'activity')->distinct('ip_address')->count('ip_address'),
            'last_activity' => $user->logs()->latest()->first()?->created_at,
            'member_since' => $user->created_at,
        ];
        
        // Estadísticas por módulo
        $moduleStats = $user->logs()
            ->select('type', DB::raw('count(*) as count'))
            ->where('type', '!=', 'error')
            ->groupBy('type')
            ->orderBy('count', 'desc')
            ->get();
        
        // Actividad por días (últimos 30 días)
        $activityByDay = $user->logs()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();
        
        // Estadísticas de rendimiento
        $performanceStats = [
            'avg_response_time' => $user->logs()
                ->whereNotNull('response_time')
                ->avg('response_time'),
            'total_errors' => $user->logs()->where('type', 'error')->count(),
            'success_rate' => $this->calculateSuccessRate($user),
        ];
        
        // Navegadores más utilizados
        $browserStats = $user->logs()
            ->select('user_agent', DB::raw('count(*) as count'))
            ->whereNotNull('user_agent')
            ->groupBy('user_agent')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'browser' => $this->extractBrowser($item->user_agent),
                    'count' => $item->count
                ];
            });
        
        // Horarios de mayor actividad
        $hourlyActivity = $user->logs()
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
        
        return view('profile.index', compact(
            'user', 
            'stats', 
            'moduleStats', 
            'activityByDay', 
            'performanceStats', 
            'browserStats', 
            'hourlyActivity'
        ));
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    
    private function calculateSuccessRate($user)
    {
        $totalRequests = $user->logs()->count();
        if ($totalRequests === 0) return 0;
        
        $errorRequests = $user->logs()->where('type', 'error')->count();
        return round((($totalRequests - $errorRequests) / $totalRequests) * 100, 2);
    }
    
    private function extractBrowser($userAgent)
    {
        if (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
        if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
        if (strpos($userAgent, 'Safari') !== false) return 'Safari';
        if (strpos($userAgent, 'Edge') !== false) return 'Edge';
        if (strpos($userAgent, 'Opera') !== false) return 'Opera';
        return 'Otro';
    }
}
