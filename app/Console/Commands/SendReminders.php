<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Notifications\GraceDatabaseNotification;
use Illuminate\Console\Command;
use Illuminate\Database\UniqueConstraintViolationException;

class SendReminders extends Command
{
    protected $signature = 'reminders:send {--at= : Local time in H:i for deterministic runs}';

    protected $description = 'Kirim pengingat database yang jatuh tempo';

    public function handle(): int
    {
        $now = now();
        $sent = 0;
        Reminder::with('user')->where('is_active', true)->eachById(function (Reminder $reminder) use ($now, &$sent): void {
            $local = $now->copy()->timezone($reminder->timezone);
            $time = $this->option('at') ?: $local->format('H:i');
            if (! in_array($local->dayOfWeekIso, $reminder->days, true) || $time !== substr($reminder->remind_at, 0, 5)) {
                return;
            }
            try {
                $reminder->deliveries()->create(['delivery_date' => $local->toDateString(), 'delivery_time' => $time, 'channel' => $reminder->channel, 'status' => 'sent']);
            } catch (UniqueConstraintViolationException) {
                return;
            }
            $reminder->user->notify(new GraceDatabaseNotification('Pengingat: '.$reminder->label, 'Waktunya menjalankan pengingatmu.', in_array($reminder->channel, ['email', 'both'], true)));
            $sent++;
        });
        $this->info("Reminders sent: {$sent}");

        return self::SUCCESS;
    }
}
