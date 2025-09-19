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
    ];

    public function type()
    {
        return $this->belongsTo(NewsType::class, 'news_type_id');
    }

}
