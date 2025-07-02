<?php

namespace App\Notifications;

use App\Models\ReturnRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class UserRequestedReturn extends Notification
{
    use Queueable;

    protected $returnRequest;

    public function __construct(ReturnRequest $returnRequest)
    {
        $this->returnRequest = $returnRequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'return',
            'title' => 'Permintaan Pengembalian Baru',
            'message' => 'User ' . $this->returnRequest->user->name . ' mengajukan pengembalian.',
            'return_request_id' => $this->returnRequest->id,
        ];
    }
}
