<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'notes'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public static function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $year = date('Y');
        $month = date('m');
        $count = static::whereYear('created_at', $year)
                      ->whereMonth('created_at', $month)
                      ->count() + 1;
        
        return $prefix . $year . $month . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function calculateTotals()
    {
        $subtotal = $this->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        $tax = $subtotal * 0.15; // 15% VAT
        $total = $subtotal + $tax;

        $this->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total
        ]);
    }

    public function isPaid()
    {
        return $this->payment && $this->payment->status === 'completed';
    }

    public function isOverdue()
    {
        return !$this->isPaid() && $this->due_date && $this->due_date->isPast();
    }
}
