<?php

namespace App\Notifications;

use App\Models\BorrowRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BorrowRejected extends Notification
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
            'type' => 'borrow_rejected',
            'title' => 'Permintaan Peminjaman Ditolak',
            'message' => 'Permintaan peminjaman Anda ditolak.',
            'borrow_request_id' => $this->borrowRequest->id,
        ];
    }
}
