<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Destination extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'location',
        'image_url',
        'status',
        'featured',
        'rating',
        'total_reviews'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:1',
        'featured' => 'boolean',
        'status' => 'boolean',
        'total_reviews' => 'integer',
    ];

    /**
     * Scope a query to only include active destinations.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', true);
    }

    /**
     * Scope a query to only include featured destinations.
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('featured', true);
    }

    /**
     * Get the images for the destination.
     */
    public function images(): HasMany
    {
        return $this->hasMany(DestinationImage::class);
    }

    /**
     * Get the tours for the destination.
     */
    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }

    /**
     * Get the reviews for the destination.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
