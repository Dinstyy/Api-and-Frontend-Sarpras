<?php

namespace App\Exports;

use App\Models\BorrowRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BorrowRequestsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return BorrowRequest::with(['user', 'handler', 'borrowDetails.itemUnit.item'])
            ->get()
            ->map(function ($request) {
                $items = $request->borrowDetails->map(fn($detail) => $detail->itemUnit->item->name . ' (' . $detail->quantity . ')')->implode(', ');
                return [
                    'id' => $request->id,
                    'tanggal_pinjam' => $request->borrow_date_expected,
                    'tanggal_kembali' => $request->return_date_expected,
                    'status' => ucfirst($request->status),
                    'item' => $items,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Peminjam',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Status',
            'Item',
        ];
    }
}
