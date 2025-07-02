<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    protected $fillable = [
        'borrow_date_expected',
        'return_date_expected',
        'reason',
        'notes',
        'status',
        'user_id',
        'handle_by',
        'rejection_reason',
    ];

    protected $casts = [
        'status' => 'string',
        'borrow_date_expected' => 'datetime',
        'return_date_expected' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function borrowDetails()
    {
        return $this->hasMany(BorrowDetail::class, 'borrow_request_id');
    }

    public function returnRequests()
    {
        return $this->hasMany(ReturnRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handle_by');
    }
}
