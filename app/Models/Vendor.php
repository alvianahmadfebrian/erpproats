<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'contact',
        'address',
        'status',
        'procurement_cost',
    ];

    protected $casts = [
        'procurement_cost' => 'decimal:2',
    ];
}
