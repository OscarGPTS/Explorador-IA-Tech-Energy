<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
   use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'description',
        'content',
        'image',
        'news_type_id',
        'external_link',
        'image_url',
        'source',
        'is_scraped',
        'scraped_at',
    ];

    protected $casts = [
        'is_scraped' => 'boolean',
        'scraped_at' => 'datetime',
    ];

    public function type()
    {
        return $this->belongsTo(NewsType::class, 'news_type_id');
    }

    public function newsType()
    {
        return $this->belongsTo(NewsType::class, 'news_type_id');
    }

}
