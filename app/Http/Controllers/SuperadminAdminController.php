<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Enums\UserStatus;
use App\Enums\VerificationStatus;
use App\Models\Tradition;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SuperadminAdminController extends Controller
{
    public function index(): View
    {
        return view('superadmin.admins.index', ['admins' => User::where('role', Role::ReligionAdmin)->with('tradition')->latest()->paginate(20), 'traditions' => Tradition::where('is_active', true)->orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'unique:users,email'], 'tradition_id' => ['required', 'exists:traditions,id']]);
        User::create($data + ['password' => Hash::make('password'), 'role' => Role::ReligionAdmin, 'status' => UserStatus::Active, 'verification_status' => VerificationStatus::Approved, 'email_verified_at' => now()]);

        return back()->with('status', 'Admin agama dibuat. Password awal: password.');
    }

    public function toggle(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === Role::ReligionAdmin, 403);
        $user->update(['status' => $user->status === UserStatus::Active ? UserStatus::Suspended : UserStatus::Active]);

        return back()->with('status', 'Status admin diperbarui.');
    }
}
