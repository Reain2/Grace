<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminEventController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.events.index', ['events' => Event::where('tradition_id', $request->user()->tradition_id)->latest()->paginate(15)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['title' => 'required|string|max:255', 'description' => 'required|string', 'starts_at' => 'required|date|after:now', 'ends_at' => 'required|date|after:starts_at', 'location' => 'nullable|string|max:255', 'link' => 'nullable|url', 'quota' => 'nullable|integer|min:1', 'is_interfaith' => 'boolean']);
        $request->user()->tradition->events()->create($data + ['status' => 'published', 'created_by' => $request->user()->id]);

        return back()->with('status', 'Event dibuat.');
    }
}
