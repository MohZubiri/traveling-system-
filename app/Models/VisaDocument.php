<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'visa_id',
        'document_name',
        'file_path',
        'original_name',
        'mime_type',
        'size'
    ];

    public function visa()
    {
        return $this->belongsTo(Visa::class);
    }
}
