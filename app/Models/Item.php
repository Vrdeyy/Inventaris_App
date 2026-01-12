<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category',
        'location',
        'placement_type',
        'quantity',
        'qty_baik',
        'qty_rusak',
        'qty_hilang',
        'condition',
        'description',
        'user_id',
    ];

    /**
     * Placement type labels
     */
    public static $placementTypes = [
        'dalam_ruang' => 'Dalam Ruang',
        'dalam_lemari' => 'Dalam Lemari',
    ];

    /**
     * Get placement type label
     */
    public function getPlacementLabelAttribute(): string
    {
        return self::$placementTypes[$this->placement_type] ?? $this->placement_type;
    }

    /**
     * Calculate and update condition based on qty breakdown
     */
    public function updateConditionFromBreakdown(): void
    {
        $total = $this->qty_baik + $this->qty_rusak + $this->qty_hilang;
        $this->quantity = $total;

        if ($total == 0) {
            $this->condition = 'baik';
        } elseif ($this->qty_hilang == $total) {
            $this->condition = 'hilang';
        } elseif ($this->qty_rusak == $total) {
            $this->condition = 'rusak';
        } elseif ($this->qty_baik == $total) {
            $this->condition = 'baik';
        } else {
            $this->condition = 'sebagian_rusak';
        }
    }

    /**
     * Get formatted condition breakdown
     */
    public function getConditionBreakdownAttribute(): string
    {
        $parts = [];
        if ($this->qty_baik > 0)
            $parts[] = "{$this->qty_baik} Baik";
        if ($this->qty_rusak > 0)
            $parts[] = "{$this->qty_rusak} Rusak";
        if ($this->qty_hilang > 0)
            $parts[] = "{$this->qty_hilang} Hilang";

        return count($parts) > 0 ? implode(' | ', $parts) : '0';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(ItemLog::class);
    }
}
