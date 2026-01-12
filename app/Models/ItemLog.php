<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemLog extends Model
{
    use HasFactory;

    public $timestamps = false; // We managed created_at manually via migration default, but Eloquent expects timestamps by default usually. Let's enable timestamps = false or just keep it simple. Migration has only created_at.

    protected $fillable = [
        'item_id',
        'user_id',
        'action',
        'old_condition',
        'new_condition',
        'old_quantity',
        'new_quantity',
        'description',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
