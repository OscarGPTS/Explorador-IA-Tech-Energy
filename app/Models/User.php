<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'google_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function newsTypes()
    {
        return $this->belongsToMany(NewsType::class, 'user_news', 'user_id', 'news_type_id')
                    ->withTimestamps();
    }
    
    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function recommendationTypes()
    {
        return $this->belongsToMany(RecommendationType::class, 'user_recommendations', 'user_id', 'recommendation_type_id')
                    ->withTimestamps();
    }
}
