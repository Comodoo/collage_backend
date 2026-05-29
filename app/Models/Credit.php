<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'value',
        'description',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:1',
        'is_active' => 'boolean',
    ];
}
