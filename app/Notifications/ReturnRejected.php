<?php

namespace App\Notifications;

use App\Models\ReturnRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReturnRejected extends Notification
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
            'type' => 'return_rejected',
            'title' => 'Pengembalian Ditolak',
            'message' => 'Permintaan pengembalian Anda ditolak.',
            'return_request_id' => $this->returnRequest->id,
        ];
    }
}
