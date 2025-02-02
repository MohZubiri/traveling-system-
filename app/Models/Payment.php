<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'amount',
        'payment_date',
        'status',
        'payment_method',
        'transaction_id',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function canRefund()
    {
        return $this->status === 'completed' && 
               $this->payment_date->addDays(30)->isFuture() &&
               !$this->refunded;
    }

    public function markAsRefunded()
    {
        $this->update([
            'status' => 'refunded',
            'refunded' => true,
            'refund_date' => now()
        ]);
    }

    public static function generateReference()
    {
        $prefix = 'PAY';
        $random = strtoupper(substr(uniqid(), -6));
        $count = static::count() + 1;
        return $prefix . str_pad($count, 6, '0', STR_PAD_LEFT) . $random;
    }
}
