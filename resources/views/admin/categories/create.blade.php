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
                        <h2 class="text-xl font-bold mb-4">{{ __('category.pages.create') }}</h2>
                        <a href="{{ route('admin.categories.index') }}"
                            class="px-4 py-[10px] text-sm bg-gray-800 hover:bg-gray-900 text-white rounded-md dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                            ← {{ __('common.back') }}
                        </a>
                    </div>

                    <div class="bg-white border rounded-lg px-8 py-6 mx-auto my-8">
                        <form method="POST" action="{{ route('admin.categories.store') }}">
                            @csrf
                            <div class="flex gap-4 items-center">
                                <div class="mb-4 w-full">
                                    <x-forms.label name="name" :label="__('category.form.name')" required />
                                    <x-text-input id="name" class="mt-1 w-full" type="text" name="name"
                                        value="{{ old('name') }}" required />
                                </div>
                                <div class="mb-4 w-full">
                                    <x-forms.label name="slug" :label="__('category.form.slug')" />
                                    <x-text-input id="slug" class="mt-1 w-full" type="text" name="slug"
                                        value="{{ old('slug') }}" />
                                </div>
                            </div>

                            <div class="flex gap-4 items-center">
                                <div class="mb-4 w-full">
                                    <x-forms.label name="parent_id" :label="__('category.form.parent')" />
                                    <select name="parent_id" id="parent_id"
                                        class="mt-2 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">{{ __('category.form.select_parent') }}</option>
                                        @foreach ($categoryOptions as $category)
                                            <option value="{{ $category->id }}"
                                                {{ (int) old('parent_id') === $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4 w-full">
                                    <x-forms.label name="position" :label="__('category.form.position')" />
                                    <x-text-input id="position" class="mt-1 w-full" type="number" min="0"
                                        name="position" value="{{ old('position') ?? 0 }}" />
                                </div>
                            </div>

                            <div class="flex gap-4 items-center">
                                <div class="mb-4 w-1/2">
                                    <x-forms.label name="is_active" :label="__('category.form.is_active')" />
                                    <x-forms.checkbox name="is_active" />
                                </div>
                            </div>
                            <div>
                                <x-primary-button type="submit">{{ __('common.save') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
