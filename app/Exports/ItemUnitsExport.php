<?php

namespace App\Exports;

use App\Models\ItemUnit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemUnitsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ItemUnit::with(['item', 'warehouse'])->get()->map(function ($itemUnit) {
            return [
                'unit_code' => $itemUnit->unit_code,
                'merk' => $itemUnit->merk,
                'condition' => $itemUnit->condition,
                'item' => $itemUnit->item->name,
                'warehouse' => $itemUnit->warehouse->name,
                'status' => ucfirst($itemUnit->status),
                'quantity' => $itemUnit->quantity,
                'diperoleh_dari' => $itemUnit->diperoleh_dari,
                'diperoleh_tanggal' => $itemUnit->diperoleh_tanggal,
                'current_location' => $itemUnit->current_location ?? 'N/A',
                'notes' => $itemUnit->notes ?? 'N/A',
                'qr_image_url' => $itemUnit->qr_image ? url($itemUnit->qr_image) : 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Unit Code',
            'Merk',
            'Condition',
            'Item',
            'Warehouse',
            'Status',
            'Quantity',
            'Diperoleh Dari',
            'Diperoleh Tanggal',
            'Current Location',
            'Notes',
            'QR Image URL',
        ];
    }
}
