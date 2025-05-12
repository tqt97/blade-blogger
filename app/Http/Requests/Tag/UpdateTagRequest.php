<?php

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateTagRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', Rule::unique('tags')->ignore($this->tag)],
            'slug' => ['required', 'string', 'alpha_dash', 'lowercase', 'max:255', Rule::unique('tags', 'slug')->ignore($this->tag)],
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
        $name = $this->input('name');
        $currentSlug = $this->tag->slug;

        if ($name !== $this->tag->name) {
            $slug = Str::slug($name);
        } else {
            $slug = $currentSlug;
        }

        $this->merge([
            'slug' => $slug,
        ]);
    }
}
