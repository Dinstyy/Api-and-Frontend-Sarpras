<?php

namespace App\Observers;

use App\Models\ItemUnit;

class ItemUnitObserver
{
    public function created(ItemUnit $unit)
    {
        logActivity('create item unit', 'Menambahkan unit: ' . $unit->unit_code);
    }

    public function updated(ItemUnit $unit)
    {
        logActivity('update item unit', 'Mengubah unit: ' . $unit->unit_code);
    }

    public function deleted(ItemUnit $unit)
    {
        logActivity('delete item unit', 'Menghapus unit: ' . $unit->unit_code);
    }
}
