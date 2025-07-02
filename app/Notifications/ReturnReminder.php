<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\BorrowRequest;

class ReturnReminder extends Notification
{
    use Queueable;

    public $borrowRequest;

    public function __construct(BorrowRequest $borrowRequest)
    {
        $this->borrowRequest = $borrowRequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Pengingat Pengembalian Barang',
            'body' => 'Barang yang kamu pinjam akan jatuh tempo besok. Harap segera dikembalikan.',
            'link' => route('user.borrow.show', $this->borrowRequest->id),
        ];
    }
}
