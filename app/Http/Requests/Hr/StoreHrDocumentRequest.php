<?php

namespace App\Http\Requests\Hr;

use Illuminate\Foundation\Http\FormRequest;

class StoreHrDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAction('hr', 'documents.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'document_type' => ['required', 'in:contract,id,certificate,cv,appointment_letter,other'],
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,jpg,jpeg,png'],
        ];
    }
}