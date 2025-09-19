<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
     use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'content',
        'image',
        'new_type_id',
    ];


    public function type()
    {
        return $this->belongsTo(NewsType::class, 'new_type_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_news', 'new_id', 'user_id')
                    ->withTimestamps();
    }
}
