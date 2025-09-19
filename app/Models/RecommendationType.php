<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationType extends Model
{
    use HasFactory;

    protected $table = 'recommendations_type';

    protected $fillable = [
        'name',
        'description',
    ];

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class, 'recommendation_type_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_recommendations', 'recommendation_type_id', 'user_id')
                    ->withTimestamps();
    }
}
