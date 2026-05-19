<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers    = User::count();
        $activeUsers   = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();

        $todayLogins = User::whereDate('last_login_at', today())->count();

        $roleCounts = User::selectRaw('role, count(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role');

        $recentUsers = User::latest()->take(8)->get();

        $recentActivity = User::whereNotNull('last_login_at')
            ->orderBy('last_login_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'todayLogins',
            'roleCounts',
            'recentUsers',
            'recentActivity'
        ));
    }
}