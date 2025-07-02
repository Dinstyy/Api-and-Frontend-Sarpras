<?php

namespace App\Observers;

use App\Models\ReturnRequest;

class ReturnRequestObserver
{
    public function created(ReturnRequest $request)
    {
        logActivity('create return request', 'Pengajuan pengembalian dari borrow ID: ' . $request->borrow_request_id);
    }

    public function updated(ReturnRequest $request)
    {
        logActivity('update return request', 'Pembaruan status pengembalian ID: ' . $request->id . ' menjadi ' . $request->status);
    }

    public function deleted(ReturnRequest $request)
    {
        logActivity('delete return request', 'Menghapus pengajuan pengembalian ID: ' . $request->id);
    }
}
