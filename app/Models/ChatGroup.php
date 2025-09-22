<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatGroup extends Model
{
    use HasFactory;

    protected $table = 'chatgroup';

    protected $fillable = ['name'];

    public function chats()
    {
        return $this->hasMany(Chat::class, 'chatgroup_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_chat', 'chatgroup_id', 'user_id')
                    ->withPivot('chat_id')
                    ->withTimestamps();
    }
}
