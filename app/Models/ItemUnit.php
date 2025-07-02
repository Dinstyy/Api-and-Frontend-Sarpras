<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemUnit extends Model
{
    /** @use HasFactory<\Database\Factories\ItemUnitFactory> */
    use HasFactory;

    protected $fillable = [
        "unit_code",
        "merk",
        "condition",
        "notes",
        "diperoleh_dari",
        "diperoleh_tanggal",
        "status",
        "quantity",
        "qr_image",
        "item_id",
        "warehouse_id",
        "current_location"
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function borrowDetails()
    {
        return $this->hasMany(BorrowDetail::class);
    }

    public function returnDetails()
    {
        return $this->hasOne(ReturnDetail::class);
    }
}
