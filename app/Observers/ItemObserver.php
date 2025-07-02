<?php

namespace App\Observers;

use App\Models\Item;

class ItemObserver
{
    public function created(Item $item)
    {
        logActivity('create item', 'Membuat item: ' . $item->name);
    }

    public function updated(Item $item)
    {
        logActivity('update item', 'Mengubah item: ' . $item->name);
    }

    public function deleted(Item $item)
    {
        logActivity('delete item', 'Menghapus item: ' . $item->name);
    }
}
