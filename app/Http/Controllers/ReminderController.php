<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReminderController extends Controller
{
    public function index(Request $request): View
    {
        return view('reminders.index', ['reminders' => $request->user()->reminders()->latest()->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'remind_at' => ['required', 'date_format:H:i'],
            'days' => ['required', 'array', 'min:1'],
            'days.*' => ['integer', 'between:1,7'],
            'channel' => ['required', 'in:web,email,both'],
            'timezone' => ['required', 'timezone'],
        ]);
        $request->user()->reminders()->create($data);

        return back()->with('status', 'Pengingat dibuat.');
    }

    public function toggle(Request $request, Reminder $reminder): RedirectResponse
    {
        abort_unless($reminder->user_id === $request->user()->id, 403);
        $reminder->update(['is_active' => ! $reminder->is_active]);

        return back()->with('status', 'Status pengingat diperbarui.');
    }

    public function destroy(Request $request, Reminder $reminder): RedirectResponse
    {
        abort_unless($reminder->user_id === $request->user()->id, 403);
        $reminder->delete();

        return back()->with('status', 'Pengingat dihapus.');
    }
}
