<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrLeave extends Model
{
    use HasFactory;

    protected $table = 'hr_leaves';

    protected $fillable = [
        'employee_name',
        'leave_type',
        'duration',
        'status',
    ];
}
