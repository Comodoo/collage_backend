<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'type',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
        'status',
        'remarks',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
