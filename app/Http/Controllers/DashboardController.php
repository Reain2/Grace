<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        return match (auth()->user()->role) {
            Role::Superadmin => to_route('superadmin.dashboard'),
            Role::ReligionAdmin => to_route('admin.dashboard'),
            Role::User => to_route('user.dashboard'),
        };
    }

    public function user(): View
    {
        return view('user.dashboard');
    }

    public function admin(): View
    {
        return view('admin.dashboard');
    }

    public function superadmin(): View
    {
        return view('superadmin.dashboard');
    }
}
