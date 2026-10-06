<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventRsvp;
use App\Models\User;
use App\Notifications\GraceDatabaseNotification;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function rsvp(User $user, Event $event): EventRsvp
    {
        abort_unless($event->status === 'published' && ($event->is_interfaith || $event->tradition_id === $user->tradition_id), 403);

        return DB::transaction(function () use ($user, $event) {
            $event = Event::lockForUpdate()->findOrFail($event->id);
            $rsvp = EventRsvp::firstOrNew(['event_id' => $event->id, 'user_id' => $user->id]);
            if ($rsvp->exists && $rsvp->status !== 'cancelled') {
                return $rsvp;
            }
            $going = $event->rsvps()->where('status', 'going')->count();
            $rsvp->status = $event->quota === null || $going < $event->quota ? 'going' : 'waitlisted';
            $rsvp->save();
            $user->notify(new GraceDatabaseNotification($rsvp->status === 'going' ? 'RSVP dikonfirmasi' : 'Masuk daftar tunggu', $event->title));

            return $rsvp;
        });
    }

    public function cancel(User $user, EventRsvp $rsvp): void
    {
        abort_unless($rsvp->user_id === $user->id, 403);
        DB::transaction(function () use ($rsvp) {
            $wasGoing = $rsvp->status === 'going';
            $rsvp->update(['status' => 'cancelled']);
            $waitlisted = EventRsvp::where('event_id', $rsvp->event_id)->where('status', 'waitlisted')->orderBy('created_at')->first();
            if ($wasGoing && $waitlisted) {
                $waitlisted->update(['status' => 'going']);
            }
        });
    }
}
