<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laratrust\Models\LaratrustTeam; // si tu Laratrust usa Teams/Groups

class Group extends Model // o LaratrustTeam si quieres funciones Laratrust
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    // Relación con roles
    public function roles()
    {
        return $this->belongsToMany(\App\Models\Role::class, 'role_group'); // tabla pivote
    }

    // Relación con usuarios
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_group'); // tabla pivote
    }
}
