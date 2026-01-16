<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // For now, let's just require auth
        if (!auth()->check()) {
            // Simple auth check - we'll add proper login later
            return redirect('/'); // or create a login page
        }

        $sites = auth()->user()->sites()->latest()->get();
        return view('dashboard', compact('sites'));
    }
}