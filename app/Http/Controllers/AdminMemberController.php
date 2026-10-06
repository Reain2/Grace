<?php

namespace App\Http\Controllers;

use App\Enums\UserStatus;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMemberController extends Controller
{
    public function index(Request $request): View
    {
        $members = User::query()
            ->where('role', 'user')
            ->where('tradition_id', $request->user()->tradition_id)
            ->latest()
            ->paginate(20);

        return view('admin.members.index', compact('members'));
    }

    public function toggle(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role->value === 'user' && $user->tradition_id === $request->user()->tradition_id, 403);

        $status = $user->status === UserStatus::Active ? UserStatus::Suspended : UserStatus::Active;
        $user->update(['status' => $status]);
        AuditLog::create(['actor_id' => $request->user()->id, 'action' => 'member.status_changed', 'target_type' => User::class, 'target_id' => $user->id, 'meta' => ['status' => $status->value]]);

        return back()->with('status', 'Status user diperbarui.');
    }
}
