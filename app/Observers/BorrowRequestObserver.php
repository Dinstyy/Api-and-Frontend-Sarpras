<?php

namespace App\Observers;

use App\Models\BorrowRequest;

class BorrowRequestObserver
{
    public function created(BorrowRequest $request)
    {
        logActivity('create borrow request', 'Pengajuan peminjaman oleh user ID: ' . $request->user_id);
    }

    public function updated(BorrowRequest $request)
    {
        logActivity('update borrow request', 'Pembaruan status peminjaman ID: ' . $request->id . ' menjadi ' . $request->status);
    }

    public function deleted(BorrowRequest $request)
    {
        logActivity('delete borrow request', 'Menghapus pengajuan peminjaman ID: ' . $request->id);
    }
}
