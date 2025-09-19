<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';

    protected $fillable = [
        'message',
        'emisor_id',
        'receiver',
        'chatgroup_id',
    ];

    public function emisor()
    {
        return $this->belongsTo(User::class, 'emisor_id');
    }

    public function chatGroup()
    {
        return $this->belongsTo(ChatGroup::class, 'chatgroup_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'chat_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_chat', 'chat_id', 'user_id')
                    ->withPivot('chatgroup_id')
                    ->withTimestamps();
    }
}
