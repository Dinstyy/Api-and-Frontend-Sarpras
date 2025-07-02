<?php

namespace App\Exports;

use App\Models\ReturnDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReturnRequestsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ReturnDetail::with(['itemUnit.item', 'returnRequest.borrowRequest.user'])->get()->map(function ($detail) {
            return [
                'ID' => $detail->id,
                'Peminjam' => $detail->returnRequest->borrowRequest->user->username ?? '-',
                'Nama Barang' => $detail->itemUnit->item->name ?? '-',
                'SKU Unit' => $detail->itemUnit->sku ?? '-',
                'Kondisi' => $detail->condition,
                'Foto' => $detail->photo ? asset('storage/' . $detail->photo) : '-',
                'Jumlah' => $detail->quantity,
                'Catatan' => $detail->notes ?? '-',
                'Tanggal Pengembalian' => $detail->created_at->format('Y-m-d H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Peminjam',
            'Nama Barang',
            'SKU Unit',
            'Kondisi',
            'Foto',
            'Jumlah',
            'Catatan',
            'Tanggal Pengembalian',
        ];
    }
}
