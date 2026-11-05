<?php

namespace App\Http\Requests\Operations;

use Illuminate\Foundation\Http\FormRequest;

class OperationsDashboardRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && ($user->isOperations() || $user->isAdmin());
    }

    public function rules(): array
    {
        return [];
    }
}