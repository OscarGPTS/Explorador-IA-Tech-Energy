<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRecommendation extends Model
{
    use HasFactory;

    protected $table = 'user_recommendations';

    protected $fillable = [
        'user_id',
        'recommendation_type_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recommendationType()
    {
        return $this->belongsTo(RecommendationType::class, 'recommendation_type_id');
    }
}
