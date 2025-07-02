<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_request_id',
        'user_id',
        'handle_by',
        'status',
        'notes',
        'rejection_reason',
    ];

    protected $casts = [
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function borrowRequest()
    {
        return $this->belongsTo(BorrowRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handle_by');
    }

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class);
    }
}
