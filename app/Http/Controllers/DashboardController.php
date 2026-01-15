<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $sites = Site::with('user')->latest()->paginate(10);
            $totalSites = Site::count();
            $totalUsers = \App\Models\User::count();

            return view('admin.dashboard', compact('sites', 'totalSites', 'totalUsers'));
        }

        $sites = $user->sites()->withCount('pages')->latest()->paginate(6);

        return view('dashboard', compact('sites'));
    }
}