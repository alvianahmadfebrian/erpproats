<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'type',
        'description',
        'category',
        'amount',
        'date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date:Y-m-d',
    ];
}
