<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CompanyDocument extends Model
{
    protected $fillable = [
        'title',
        'document_code',
        'type',
        'category',
        'description',
        'file_path',
        'external_url',
        'file_type',
        'file_size',
        'version',
        'effective_date',
        'expiry_date',
        'owner_email',
        'department',
        'access_level',
        'tags',
        'summary',
        'download_count',
        'last_reviewed',
        'is_active'
    ];

    protected $casts = [
        'tags' => 'array',
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'last_reviewed' => 'datetime',
        'is_active' => 'boolean',
        'file_size' => 'integer',
        'download_count' => 'integer'
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

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    public function scopeByDepartment(Builder $query, string $department): Builder
    {
        return $query->where('department', $department);
    }

    public function scopeByAccessLevel(Builder $query, string $accessLevel): Builder
    {
        return $query->where('access_level', $accessLevel);
    }

    public function scopeSearch(Builder $query, string $searchTerm): Builder
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%")
              ->orWhere('description', 'like', "%{$searchTerm}%")
              ->orWhere('summary', 'like', "%{$searchTerm}%")
              ->orWhere('document_code', 'like', "%{$searchTerm}%");
        });
    }

    public function scopeExpiringSoon(Builder $query, int $days = 30): Builder
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
                    ->where('expiry_date', '>=', now());
    }

    // Accessors
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'policy' => 'Política',
            'procedure' => 'Procedimiento',
            'manual' => 'Manual',
            'form' => 'Formulario',
            'template' => 'Plantilla',
            'guide' => 'Guía',
            'other' => 'Otro',
            default => 'Desconocido'
        };
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'hr' => 'Recursos Humanos',
            'it' => 'Tecnología',
            'finance' => 'Finanzas',
            'operations' => 'Operaciones',
            'legal' => 'Legal',
            'marketing' => 'Marketing',
            'general' => 'General',
            default => 'Otro'
        };
    }

    public function getAccessLevelLabelAttribute(): string
    {
        return match($this->access_level) {
            'public' => 'Público',
            'internal' => 'Interno',
            'confidential' => 'Confidencial',
            default => 'Desconocido'
        };
    }

    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) return 'N/A';
        
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->expiry_date && 
               $this->expiry_date->isFuture() && 
               $this->expiry_date->lte(now()->addDays(30));
    }

    // Helper methods
    public static function getTypes(): array
    {
        return [
            'policy' => 'Política',
            'procedure' => 'Procedimiento',
            'manual' => 'Manual',
            'form' => 'Formulario',
            'template' => 'Plantilla',
            'guide' => 'Guía',
            'other' => 'Otro'
        ];
    }

    public static function getCategories(): array
    {
        return [
            'hr' => 'Recursos Humanos',
            'it' => 'Tecnología',
            'finance' => 'Finanzas',
            'operations' => 'Operaciones',
            'legal' => 'Legal',
            'marketing' => 'Marketing',
            'general' => 'General'
        ];
    }

    public static function getAccessLevels(): array
    {
        return [
            'public' => 'Público',
            'internal' => 'Interno',
            'confidential' => 'Confidencial'
        ];
    }

    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }
}
