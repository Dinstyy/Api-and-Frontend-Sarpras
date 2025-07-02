<?php

namespace App\Notifications;

use App\Models\ReturnRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ReturnApproved extends Notification
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
            'type' => 'return_approval',
            'title' => 'Pengembalian Disetujui',
            'message' => 'Permintaan pengembalian Anda telah disetujui.',
            'return_request_id' => $this->returnRequest->id,
        ];
    }
}
