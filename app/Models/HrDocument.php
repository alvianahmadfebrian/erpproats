<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrDocument extends Model
{
    use HasFactory;

    protected $table = 'hr_documents';

    protected $fillable = [
        'filename',
        'file_path',
        'file_size',
    ];
}
