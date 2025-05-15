<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class BulkDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $ids = array_filter(array_map('trim', explode(',', (string) $this->ids)));
        $this->merge([
            'ids' => array_map('intval', $ids),
        ]);
    }
}
