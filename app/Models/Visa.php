<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visa extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'type',
        'status',
        'submission_date',
        'approval_date',
        'notes'
    ];

    protected $casts = [
        'submission_date' => 'datetime',
        'approval_date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function documents()
    {
        return $this->hasMany(VisaDocument::class);
    }

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
}
