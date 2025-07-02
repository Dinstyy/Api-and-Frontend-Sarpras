<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ManualNotification extends Notification
{
    use Queueable;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->data['title'] ?? 'Notifikasi',
            'message' => $this->data['message'] ?? '',
            'related_type' => $this->data['related_type'] ?? null,
            'related_id' => $this->data['related_id'] ?? null,
            'related_url' => $this->data['related_url'] ?? null,
        ];
    }
}
