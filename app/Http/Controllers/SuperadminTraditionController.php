<?php

namespace App\Http\Controllers;

use App\Models\Tradition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperadminTraditionController extends Controller
{
    public function index(): View
    {
        return view('superadmin.traditions.index', ['traditions' => Tradition::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255', 'unique:traditions,name'], 'slug' => ['required', 'alpha_dash', 'max:255', 'unique:traditions,slug']]);
        Tradition::create($data);

        return back()->with('status', 'Tradisi dibuat.');
    }

    public function toggle(Request $request, Tradition $tradition): RedirectResponse
    {
        $tradition->update(['is_active' => ! $tradition->is_active]);

        return back()->with('status', 'Status tradisi diperbarui.');
    }
}
