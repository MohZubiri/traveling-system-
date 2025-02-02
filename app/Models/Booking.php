<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'service_type',
        'date',
        'time',
        'location',
        'cost',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
        'cost' => 'decimal:2'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function canCancel()
    {
        // Can cancel if booking is more than 24 hours away
        return $this->date->gt(now()->addHours(24));
    }

    public function canModify()
    {
        // Can modify if booking is more than 48 hours away
        return $this->date->gt(now()->addHours(48));
    }
}
