<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    protected $fillable = [
        'page_id',
        'title',
        'subtitle',
        'content',
        'image',
        'button_text',
        'button_url',
        'type',
        'order',
        'background_color',
        'text_color'
    ];

    protected $casts = [
        'order' => 'integer'
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }
}
