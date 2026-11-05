<?php

namespace App\Http\Requests\Proposal;

use Illuminate\Foundation\Http\FormRequest;

class StoreProposalAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('proposals', 'update') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,jpg,jpeg,png,xlsx'],
        ];
    }
}