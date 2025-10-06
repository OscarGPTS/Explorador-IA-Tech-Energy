<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechSupportProblem extends Model
{
    use HasFactory;

    protected $fillable = [
        'tech_support_category_id',
        'problem_key',
        'title',
        'description',
        'solution_title',
        'solution_content',
        'priority',
        'estimated_time',
        'sort_order',
        'is_active',
        'keywords'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'keywords' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(TechSupportCategory::class, 'tech_support_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    public function scopeByKey($query, $key)
    {
        return $query->where('problem_key', $key);
    }
}
