<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'service_type',
        'status',
        'amount',
        'reference_id',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function visa()
    {
        return $this->morphOne(Visa::class, 'transactionable');
    }

    public function booking()
    {
        return $this->morphOne(Booking::class, 'transactionable');
    }
}
