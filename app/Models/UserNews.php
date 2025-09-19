<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNews extends Model
{
    use HasFactory;

    protected $table = 'user_news';

    protected $fillable = [
        'user_id',
        'new_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function news()
    {
        return $this->belongsTo(News::class, 'new_id');
    }
}
