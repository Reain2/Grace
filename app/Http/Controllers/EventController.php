<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRsvp;
use App\Services\EventService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function __construct(private readonly EventService $service) {}

    public function index(Request $request): View
    {
        $events = Event::where('status', 'published')->where(fn ($q) => $q->where('tradition_id', $request->user()->tradition_id)->orWhere('is_interfaith', true))->where('starts_at', '>=', now())->orderBy('starts_at')->paginate(12);

        return view('events.index', compact('events'));
    }

    public function rsvp(Request $request, Event $event): RedirectResponse
    {
        $this->service->rsvp($request->user(), $event);

        return back()->with('status', 'RSVP berhasil.');
    }

    public function cancel(Request $request, EventRsvp $rsvp): RedirectResponse
    {
        $this->service->cancel($request->user(), $rsvp);

        return back()->with('status', 'RSVP dibatalkan.');
    }
}
