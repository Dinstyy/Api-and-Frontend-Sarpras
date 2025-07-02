<?php

namespace App\Notifications;

use App\Models\BorrowRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class UserRequestedBorrow extends Notification
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
            'type' => 'borrow',
            'title' => 'Permintaan Peminjaman Baru',
            'message' => 'User ' . $this->borrowRequest->user->name . ' mengajukan peminjaman.',
            'borrow_request_id' => $this->borrowRequest->id,
        ];
    }
}
