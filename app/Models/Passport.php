<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passport extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'passport_number',
        'status',
        'submission_date',
        'expiry_date',
        'issue_date',
        'pickup_date',
        'notes'
    ];

    protected $casts = [
        'submission_date' => 'date',
        'expiry_date' => 'date',
        'issue_date' => 'date',
        'pickup_date' => 'date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function isExpiringSoon()
    {
        return $this->expiry_date && $this->expiry_date->diffInMonths(now()) <= 6;
    }

    public function canModify()
    {
        return $this->status === 'pending';
    }
}
