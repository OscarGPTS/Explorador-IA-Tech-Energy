<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
     use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'content',
        'image',
        'recommendation_type_id',
    ];

    // 🔗 Relaciones
    public function type()
    {
        return $this->belongsTo(RecommendationType::class, 'recommendation_type_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_recommendations', 'recommendation_id', 'user_id')
                    ->withTimestamps();
    }
}
