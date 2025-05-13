<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'alpha_dash', 'lowercase', 'max:255', Rule::unique('posts', 'slug')->ignore($this->post)],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'is_featured' => ['required', 'boolean'],
            'is_published' => ['required', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'tags' => 'array',
            'tags.*' => ['integer', 'exists:tags,id'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * This method is called after the request is validated. Here we can
     * manipulate the data before it is passed to the validator.
     */
    protected function prepareForValidation(): void
    {
        $isPublished = $this->boolean('is_published');
        $publishedAt = $this->input('published_at');

        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'is_published' => $isPublished,
            'published_at' => $isPublished
                ? ($publishedAt !== null ? $publishedAt : $this->route('post')->published_at)
                : null,
            'tags' => $this->input('tags') ?? [],
        ]);
    }
}
