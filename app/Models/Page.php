<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_description',
        'meta_keywords',
        'is_published',
        'order'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'order' => 'integer'
    ];

    public function sections()
    {
        return $this->hasMany(PageSection::class);
    }

    public function getUrlAttribute()
    {
        return url($this->slug);
    }
}
