<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id', 'title', 'file_path', 'original_file_name',
        'mime_type', 'file_size', 'uploaded_by',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}