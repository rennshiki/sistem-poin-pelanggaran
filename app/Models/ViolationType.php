<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'points',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'points' => 'integer',
    ];

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'ringan' => 'Ringan',
            'sedang' => 'Sedang',
            'berat'  => 'Berat',
            default  => ucfirst($this->category),
        };
    }

    public function getCategoryColorAttribute(): string
    {
        return match ($this->category) {
            'ringan' => 'emerald',
            'sedang' => 'amber',
            'berat'  => 'rose',
            default  => 'gray',
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
