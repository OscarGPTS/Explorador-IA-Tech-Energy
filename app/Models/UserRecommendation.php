<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRecommendation extends Model
{
    //use HasFactory;

    protected $table = 'user_recommendations';

    protected $fillable = [
        'user_id',
        'recommendation_id',
    ];

    // 🔗 Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recommendation()
    {
        return $this->belongsTo(Recommendation::class);
    }
}
