<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'hr_profile_id', 'document_type', 'title', 'file_path',
        'original_file_name', 'mime_type', 'file_size', 'uploaded_by',
    ];

    public function profile()
    {
        return $this->belongsTo(HrProfile::class, 'hr_profile_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}