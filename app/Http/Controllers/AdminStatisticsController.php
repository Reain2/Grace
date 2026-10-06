<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRsvp;
use App\Models\ReadingProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminStatisticsController extends Controller
{
    public function index(Request $request): View
    {
        $traditionId = $request->user()->tradition_id;
        $verifiedUsers = User::where('tradition_id', $traditionId)->where('role', 'user')->where('verification_status', 'approved')->count();
        $upcomingEvents = Event::where('tradition_id', $traditionId)->where('status', 'published')->where('starts_at', '>=', now())->count();
        $goingRsvps = EventRsvp::whereHas('event', fn ($query) => $query->where('tradition_id', $traditionId))->where('status', 'going')->count();
        $completedReadings = ReadingProgress::whereHas('enrollment.user', fn ($query) => $query->where('tradition_id', $traditionId))->count();

        return view('admin.statistics.index', compact('verifiedUsers', 'upcomingEvents', 'goingRsvps', 'completedReadings'));
    }
}
