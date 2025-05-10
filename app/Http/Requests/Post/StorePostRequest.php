<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StorePostRequest extends FormRequest
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
            'slug' => ['required', 'string', 'alpha_dash', 'lowercase', 'max:255', 'unique:posts,slug'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'is_published' => ['required', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $slug = $this->input('slug');
        $slug = $slug ?: Str::slug($this->input('title'));
        $isPublished = $this->boolean('is_published');

        $this->merge([
            'slug' => $slug,
            'user_id' => auth()->id(),
            'is_published' => $isPublished,
            'published_at' => $isPublished ? ($this->input('published_at') ?? now()) : null,
        ]);
    }
}
