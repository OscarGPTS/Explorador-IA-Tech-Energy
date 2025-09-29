<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $table = 'recommendations';

    protected $fillable = [
        'title',
        'description',
        'content',
        'image',
        'recommendation_type_id',
        'external_link',
        'image_url',
        'source',
        'sub_area',
        'is_scraped',
        'scraped_at',
    ];

    protected $casts = [
        'is_scraped' => 'boolean',
        'scraped_at' => 'datetime',
    ];

    public function type()
    {
        return $this->belongsTo(RecommendationType::class, 'recommendation_type_id');
    }

    public function recommendationType()
    {
        return $this->belongsTo(RecommendationType::class, 'recommendation_type_id');
    }
}
