<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GraceDatabaseNotification extends Notification
{
    public function __construct(private readonly string $title, private readonly string $message, private readonly bool $email = false) {}

    public function via(object $notifiable): array
    {
        return $this->email ? ['database', 'mail'] : ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->subject($this->title)->line($this->message);
    }

    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage(['title' => $this->title, 'message' => $this->message]);
    }
}
