<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TempEmployee extends Model
{
    protected $table = 'temp_employees';

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
        'is_active',
        'user_id',
        'data_imported_at',
        'import_source',
        'last_sync_at'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'is_active' => 'boolean',
        'data_imported_at' => 'datetime',
        'last_sync_at' => 'datetime',
    ];

    /**
     * Relación con el usuario del sistema (si tiene cuenta empresarial y acceso via Google Auth)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para empleados activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'active');
    }

    /**
     * Scope para empleados con acceso al sistema (tienen user_id)
     */
    public function scopeWithSystemAccess($query)
    {
        return $query->whereNotNull('user_id');
    }

    /**
     * Scope para empleados sin acceso al sistema (no tienen user_id)
     */
    public function scopeWithoutSystemAccess($query)
    {
        return $query->whereNull('user_id');
    }

    /**
     * Scope para buscar por departamento
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('employee_id', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('position', 'like', "%{$search}%")
              ->orWhere('department', 'like', "%{$search}%");
        });
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Verificar si el empleado tiene acceso al sistema
     */
    public function hasSystemAccess()
    {
        return !is_null($this->user_id);
    }

    /**
     * Vincular empleado con usuario del sistema
     */
    public function linkToUser($userId)
    {
        $this->update([
            'user_id' => $userId,
            'last_sync_at' => now()
        ]);
    }

    /**
     * Obtener todos los departamentos únicos
     */
    public static function getAllDepartments()
    {
        return self::where('is_active', true)
                   ->distinct()
                   ->pluck('department')
                   ->sort()
                   ->values();
    }

    /**
     * Obtener todas las posiciones únicas
     */
    public static function getAllPositions()
    {
        return self::where('is_active', true)
                   ->distinct()
                   ->pluck('position')
                   ->sort()
                   ->values();
    }
}
