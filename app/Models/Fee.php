<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'program_id',
        'applicable_semester',
        'semester_1_amount',
        'semester_2_amount',
        'total_amount',
        'currency',
        'description',
        'is_active',
    ];

    protected $casts = [
        'semester_1_amount' => 'decimal:2',
        'semester_2_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
