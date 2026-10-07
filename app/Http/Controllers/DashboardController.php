<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        if (auth()->user()->role === 'admin') {
            return view('dashboard.admin', [
                'totalUsers' => User::where('role', 'user')->count(),
                'totalAdmins' => User::where('role', 'admin')->count(),
                'newUsers' => User::where('role', 'user')->where('created_at', '>=', now()->startOfMonth())->count(),
                'recentUsers' => User::where('role', 'user')->latest()->take(5)->get(),
            ]);
        }

        return view('dashboard.user');
    }

    public function users(): View
    {
        return view('dashboard.users', [
            'users' => User::where('role', 'user')->latest()->paginate(10),
        ]);
    }
}
