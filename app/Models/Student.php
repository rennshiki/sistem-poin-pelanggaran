<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'class',
        'major',
        'parent_phone',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function violations(): HasMany
    {
        return $this->hasMany(Violation::class);
    }

    /**
     * Total poin pelanggaran yang terkumpul.
     */
    public function getTotalPointsAttribute(): int
    {
        return $this->violations()->sum('points');
    }

    /**
     * Persentase poin dari batas maksimum (100).
     */
    public function getPointsPercentageAttribute(): float
    {
        return min(100, ($this->total_points / 100) * 100);
    }

    /**
     * Warna bar berdasarkan akumulasi poin.
     */
    public function getPointsColorAttribute(): string
    {
        $pts = $this->total_points;
        if ($pts >= 75) return 'rose';
        if ($pts >= 50) return 'amber';
        if ($pts >= 25) return 'orange';
        return 'emerald';
    }
}
