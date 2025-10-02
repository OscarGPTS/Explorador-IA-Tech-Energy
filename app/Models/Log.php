<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';

    protected $fillable = [
        'type',
        'message',
        'status_code',
        'user_id',
        'request_data',
        'response_data',
        'error_details',
        'stack_trace',
        'method',
        'url',
        'ip_address',
        'user_agent',
        'response_time',
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
        'error_details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
