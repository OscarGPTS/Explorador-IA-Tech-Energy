<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CompanyLocation extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'address',
        'city',
        'state_province',
        'country',
        'postal_code',
        'phone',
        'fax',
        'contact_person',
        'contact_email',
        'opening_time',
        'closing_time',
        'operating_days',
        'capacity',
        'parking_spaces',
        'facilities',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'operating_days' => 'array',
        'facilities' => 'array',
        'is_active' => 'boolean',
        'opening_time' => 'datetime:H:i',
        'closing_time' => 'datetime:H:i'
    ];

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeByCity(Builder $query, string $city): Builder
    {
        return $query->where('city', $city);
    }

    public function scopeSearch(Builder $query, string $searchTerm): Builder
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', "%{$searchTerm}%")
              ->orWhere('code', 'like', "%{$searchTerm}%")
              ->orWhere('city', 'like', "%{$searchTerm}%")
              ->orWhere('address', 'like', "%{$searchTerm}%");
        });
    }

    // Accessors
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'headquarters' => 'Oficina Principal',
            'branch' => 'Sucursal',
            'warehouse' => 'Almacén',
            'datacenter' => 'Centro de Datos',
            'remote' => 'Remoto',
            default => 'Otro'
        };
    }

    public function getFullAddressAttribute(): string
    {
        $parts = [$this->address, $this->city];
        
        if ($this->state_province) {
            $parts[] = $this->state_province;
        }
        
        if ($this->postal_code) {
            $parts[] = $this->postal_code;
        }
        
        $parts[] = $this->country;
        
        return implode(', ', array_filter($parts));
    }

    public function getOperatingHoursAttribute(): string
    {
        if (!$this->opening_time || !$this->closing_time) {
            return 'No especificado';
        }
        
        return $this->opening_time->format('H:i') . ' - ' . $this->closing_time->format('H:i');
    }

    // Helper methods
    public static function getTypes(): array
    {
        return [
            'headquarters' => 'Oficina Principal',
            'branch' => 'Sucursal',
            'warehouse' => 'Almacén',
            'datacenter' => 'Centro de Datos',
            'remote' => 'Remoto'
        ];
    }

    public function isOpenNow(): bool
    {
        if (!$this->opening_time || !$this->closing_time || !$this->operating_days) {
            return false;
        }

        $now = now();
        $currentDay = strtolower($now->format('l'));
        
        if (!in_array($currentDay, $this->operating_days)) {
            return false;
        }

        $currentTime = $now->format('H:i');
        return $currentTime >= $this->opening_time->format('H:i') && 
               $currentTime <= $this->closing_time->format('H:i');
    }
}
