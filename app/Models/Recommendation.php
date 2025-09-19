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
    ];

    public function type()
    {
        return $this->belongsTo(RecommendationType::class, 'recommendation_type_id');
    }
}
