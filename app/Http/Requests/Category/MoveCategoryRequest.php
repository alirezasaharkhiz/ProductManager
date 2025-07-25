<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class MoveCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            // new_parent_id can be null to move to root
            'new_parent_id' => ['nullable', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'new_parent_id.exists' => 'The specified new parent category does not exist.',
        ];
    }
}
