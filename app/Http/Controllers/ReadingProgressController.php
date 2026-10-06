<?php

namespace App\Http\Controllers;

use App\Models\ReadingPlan;
use App\Models\UserReadingPlan;
use App\Services\ReadingProgressService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReadingProgressController extends Controller
{
    public function __construct(private readonly ReadingProgressService $service) {}

    public function show(Request $request, UserReadingPlan $enrollment): View
    {
        abort_unless($enrollment->user_id === $request->user()->id, 403);
        $enrollment->load('plan.items', 'progress');

        return view('reading-plans.progress', [
            'enrollment' => $enrollment,
            'percentage' => $this->service->percentage($enrollment),
            'streak' => $this->service->streak($enrollment),
        ]);
    }

    public function start(Request $request, ReadingPlan $plan): RedirectResponse
    {
        $enrollment = $this->service->start($request->user(), $plan);

        return to_route('user.reading-plans.progress', $enrollment);
    }

    public function check(Request $request, UserReadingPlan $enrollment, int $item): RedirectResponse
    {
        $this->service->check($request->user(), $enrollment, $item);

        return back();
    }
}
