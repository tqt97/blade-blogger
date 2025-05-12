<?php

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreTagRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:tags'],
            'slug' => ['required', 'string', 'alpha_dash', 'lowercase', 'max:255', 'unique:tags'],
        ];
    }

    /**
     * Prepare the request data for validation.
     *
     * This method is called right before the request data is validated.
     * It allows you to modify the request data before it's validated.
     *
     * In this case, we're converting the "is active" field to a boolean.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->input('name')),
        ]);
    }
}
