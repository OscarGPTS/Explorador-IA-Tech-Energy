<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Employee extends Model
{
    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'extension',
        'position',
        'department',
        'location',
        'manager_email',
        'hire_date',
        'status',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'is_active' => 'boolean'
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->where('status', 'active');
    }

    public function scopeByDepartment(Builder $query, string $department): Builder
    {
        return $query->where('department', $department);
    }

    public function scopeByLocation(Builder $query, string $location): Builder
    {
        return $query->where('location', $location);
    }

    public function scopeSearch(Builder $query, string $searchTerm): Builder
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('first_name', 'like', "%{$searchTerm}%")
              ->orWhere('last_name', 'like', "%{$searchTerm}%")
              ->orWhere('email', 'like', "%{$searchTerm}%")
              ->orWhere('position', 'like', "%{$searchTerm}%")
              ->orWhere('department', 'like', "%{$searchTerm}%")
              ->orWhere('employee_id', 'like', "%{$searchTerm}%");
        });
    }

    // Relationships
    public function manager()
    {
        return $this->hasOne(Employee::class, 'email', 'manager_email');
    }

    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'manager_email', 'email');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active' => 'Activo',
            'inactive' => 'Inactivo',
            'on_leave' => 'En Licencia',
            default => 'Desconocido'
        };
    }

    // Helper methods
    public static function getDepartments(): array
    {
        return self::active()
            ->distinct()
            ->pluck('department')
            ->filter()
            ->sort()
            ->values()
            ->toArray();
    }

    public static function getLocations(): array
    {
        return self::active()
            ->distinct()
            ->pluck('location')
            ->filter()
            ->sort()
            ->values()
            ->toArray();
    }
}
