<?php

namespace App\Services;

use App\Models\ReadingPlan;
use App\Models\ReadingProgress;
use App\Models\User;
use App\Models\UserReadingPlan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReadingProgressService
{
    public function start(User $user, ReadingPlan $plan): UserReadingPlan
    {
        abort_unless($user->tradition_id === $plan->tradition_id, 403);

        return UserReadingPlan::firstOrCreate(
            ['user_id' => $user->id, 'reading_plan_id' => $plan->id],
            [
                'plan_version' => $plan->version ?? 1,
                'items_snapshot' => $plan->items()->orderBy('day_number')->get(['day_number', 'title', 'reference'])->toArray(),
                'started_on' => today(),
                'status' => 'active',
            ],
        );
    }

    public function check(User $user, UserReadingPlan $enrollment, int $itemId): ReadingProgress
    {
        abort_unless($enrollment->user_id === $user->id, 403);
        $item = $enrollment->plan->items()->findOrFail($itemId);

        return DB::transaction(fn () => ReadingProgress::firstOrCreate(
            ['user_reading_plan_id' => $enrollment->id, 'reading_plan_item_id' => $item->id],
            ['checked_on' => today()],
        ));
    }

    public function percentage(UserReadingPlan $enrollment): int
    {
        $total = $enrollment->plan->items()->count();

        return $total === 0 ? 0 : (int) round($enrollment->progress()->count() / $total * 100);
    }

    public function streak(UserReadingPlan $enrollment): int
    {
        $dates = $enrollment->progress()->orderByDesc('checked_on')->pluck('checked_on')->map(fn ($date) => Carbon::parse($date)->toDateString())->unique()->values();
        $streak = 0;
        $restUsed = false;
        $expected = today();

        foreach ($dates as $date) {
            if ($date === $expected->toDateString()) {
                $streak++;
                $expected = $expected->subDay();

                continue;
            }

            if (! $restUsed && $date === $expected->copy()->subDay()->toDateString()) {
                $restUsed = true;
                $expected = $expected->subDays(2);
                $streak++;

                continue;
            }

            break;
        }

        return $streak;
    }
}
