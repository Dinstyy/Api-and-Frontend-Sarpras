<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemsExport implements FromCollection, WithHeadings
{
    public function collection()
{
        return Item::with('category')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'type' => $item->type === 'consumable' ? 'Consumable' : 'Non-Consumable',
                'category' => $item->category ? $item->category->name : '-',
                'description' => $item->description ?? '-',
                'image' => $item->image ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Type',
            'Category',
            'Description',
            'Image',
        ];
    }
}
