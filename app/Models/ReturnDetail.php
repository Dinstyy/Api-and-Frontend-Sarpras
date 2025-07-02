<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_request_id',
        'item_unit_id',
        'quantity',
        'condition',
        'photo',
        'notes',
    ];

    public function returnRequest()
    {
        return $this->belongsTo(ReturnRequest::class);
    }

    public function itemUnit()
    {
        return $this->belongsTo(ItemUnit::class);
    }
}
