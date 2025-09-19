<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'user_chat';

    protected $fillable = [
        'user_id',
        'chat_id',
        'chatgroup_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    public function chatGroup()
    {
        return $this->belongsTo(ChatGroup::class, 'chatgroup_id');
    }
}
