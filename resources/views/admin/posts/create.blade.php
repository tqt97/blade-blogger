<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('common.create') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold mb-4">{{ __('post.pages.create') }}</h2>
                        <a href="{{ route('admin.posts.index') }}"
                            class="px-4 py-[10px] text-sm bg-gray-800 hover:bg-gray-900 text-white rounded-md dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                            ← {{ __('common.back') }}
                        </a>
                    </div>

                    <div class="bg-white border rounded-lg px-8 py-6 mx-auto my-8">
                        <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="flex gap-4 items-center">
                                <div class="mb-4 w-full">
                                    <x-forms.label name="title" :label="__('post.form.title')" required />
                                    <x-text-input id="title" class="mt-1 w-full" type="text" name="title"
                                        value="{{ old('title') }}" required />
                                </div>
                                <div class="mb-4 w-full">
                                    <x-forms.label name="slug" :label="__('post.form.slug')" />
                                    <x-text-input id="slug" class="mt-1 w-full" type="text" name="slug"
                                        value="{{ old('slug') }}" />
                                </div>
                            </div>

                            <div class="flex gap-4 items-center">
                                <div class="mb-4 w-full">
                                    <x-forms.label name="category_id" :label="__('post.form.category_id')" required />
                                    <select name="category_id" id="category_id"
                                        class="mt-2 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">{{ __('post.form.select_category') }}</option>
                                        @foreach ($options as $category)
                                            <option value="{{ $category->id }}" {{ (int) old('category_id') === $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4 w-full">
                                    <x-forms.label name="image" :label="__('post.form.image')" />
                                    <input type="file" name="image" id="image">
                                </div>
                            </div>

                            <div class="flex gap-4 items-center">
                                <div class="mb-4 w-full">
                                    <x-forms.label name="excerpt" :label="__('post.form.excerpt')" />
                                    <x-text-input id="excerpt" class="mt-1 w-full" type="text" name="excerpt"
                                        value="{{ old('excerpt') }}" />
                                </div>
                            </div>
                            <div class="flex gap-4 items-center">
                                <div class="mb-4 w-full">
                                    <x-forms.label name="content" :label="__('post.form.content')" required />
                                    <textarea id="content" rows="5" class="mt-1 w-full rounded-md"
                                        name="content">{{ old('content') }}</textarea>
                                </div>
                            </div>
                            <div class="flex gap-4 items-center">
                                <div class="mb-4 w-1/2">
                                    <x-forms.label name="is_featured" :label="__('post.form.is_featured')" />
                                    <x-forms.checkbox name="is_featured" />
                                </div>
                                <div class="mb-4 w-1/2">
                                    <x-forms.label name="is_published" :label="__('post.form.is_published')" />
                                    <x-forms.checkbox name="is_published" />
                                </div>
                            </div>
                            <div class="flex gap-4 items-center mt-2">
                                <x-primary-button type="submit">{{ __('common.save') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
