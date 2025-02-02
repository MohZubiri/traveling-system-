<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'ticket_number',
        'issue_date',
        'status'
    ];

    protected $casts = [
        'issue_date' => 'datetime'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public static function generateTicketNumber()
    {
        $prefix = 'TKT';
        $random = strtoupper(substr(uniqid(), -6));
        $count = static::count() + 1;
        return $prefix . str_pad($count, 6, '0', STR_PAD_LEFT) . $random;
    }
}
