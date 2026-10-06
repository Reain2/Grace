<?php

namespace App\Http\Controllers;

use App\Models\ReadingPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminReadingPlanController extends Controller
{
    public function index(Request $request): View
    {
        $plans = ReadingPlan::with('items')->where('tradition_id', $request->user()->tradition_id)->latest()->paginate(10);

        return view('admin.reading-plans.index', compact('plans'));
    }

    public function update(Request $request, ReadingPlan $plan): RedirectResponse
    {
        abort_unless($plan->tradition_id === $request->user()->tradition_id, 403);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'source' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);
        $plan->update($data + ['version' => $plan->version + 1]);

        return back()->with('status', 'Rencana bacaan diperbarui.');
    }

    public function destroy(Request $request, ReadingPlan $plan): RedirectResponse
    {
        abort_unless($plan->tradition_id === $request->user()->tradition_id, 403);
        $plan->delete();

        return back()->with('status', 'Rencana bacaan dihapus.');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'source' => ['required', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.title' => ['required', 'string', 'max:255'],
            'items.*.reference' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data, $request): void {
            $plan = $request->user()->tradition->readingPlans()->create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'total_days' => count($data['items']),
                'source' => $data['source'],
                'created_by' => $request->user()->id,
            ]);
            foreach (array_values($data['items']) as $index => $item) {
                $plan->items()->create(['day_number' => $index + 1, ...$item]);
            }
        });

        return back()->with('status', 'Rencana bacaan dibuat.');
    }
}
