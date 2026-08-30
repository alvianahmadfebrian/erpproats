<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'name',
        'category',
        'stock',
        'price',
        'vendor',
        'image_path',
    ];

    /**
     * Get computed status based on stock level.
     */
    public function getStatusAttribute(): string
    {
        if ($this->stock == 0) {
            return 'Out of Stock';
        }
        if ($this->stock <= 5) {
            return 'Low Stock';
        }
        if ($this->stock >= 40) {
            return 'Overstocked';
        }
        return 'Optimal';
    }
}
