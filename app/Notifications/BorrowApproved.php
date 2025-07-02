<?php

namespace App\Notifications;

use App\Models\BorrowRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class BorrowApproved extends Notification
{
    use Queueable;

    protected $borrowRequest;

    public function __construct(BorrowRequest $borrowRequest)
    {
        $this->borrowRequest = $borrowRequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'borrow_approval',
            'title' => 'Permintaan Peminjaman Disetujui',
            'message' => 'Permintaan peminjaman Anda telah disetujui.',
            'borrow_request_id' => $this->borrowRequest->id,
        ];
    }
}
