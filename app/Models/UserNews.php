<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNews extends Model
{
    protected $table = 'user_news';

    protected $fillable = [
        'user_id',
        'news_type_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function newsType()
    {
        return $this->belongsTo(NewsType::class, 'news_type_id');
    }
}
