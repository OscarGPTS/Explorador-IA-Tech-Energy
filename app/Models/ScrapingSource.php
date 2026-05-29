<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScrapingSource extends Model
{
    use HasFactory;

    protected $table = 'scraping_sources';

    protected $fillable = [
        'name',
        'module',
        'feed_type',
        'url',
        'type_id',
        'sub_area',
        'selectors',
        'max_items',
        'is_active',
        'last_run_at',
        'last_status',
        'last_items',
        'last_error',
    ];

    protected $casts = [
        'selectors'   => 'array',
        'is_active'   => 'boolean',
        'last_run_at' => 'datetime',
        'max_items'   => 'integer',
        'last_items'  => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNews($query)
    {
        return $query->where('module', 'news');
    }

    public function scopeRecommendations($query)
    {
        return $query->where('module', 'recommendations');
    }

    /**
     * Nombre del tipo (área) destino, resuelto según el módulo.
     */
    public function getTypeNameAttribute(): ?string
    {
        if (!$this->type_id) {
            return null;
        }

        if ($this->module === 'news') {
            return optional(NewsType::find($this->type_id))->name;
        }

        return optional(RecommendationType::find($this->type_id))->name;
    }
}
