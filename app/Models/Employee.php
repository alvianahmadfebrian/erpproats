<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'position',
        'contract_status',
        'salary',
        'leave_balance',
        'avatar',
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'leave_balance' => 'integer',
    ];
}
