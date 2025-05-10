@props([
    'name',
    'label' => '',
    'required' => false,
    'error' => null,
])

@php
    $fieldError = $error ?? ($errors->get($name) ?? []);
@endphp

<div class="flex items-center justify-between">
    <div class="flex items-center gap-1">
        <label for="{{ $name }}" class="block font-medium text-sm text-gray-700">
            {{ $label }}
        </label>
        @if ($required)
            <span class="text-red-500 text-xl leading-none">*</span>
        @endif
    </div>
    @if (!empty($fieldError))
        <div class="text-sm text-red-600 dark:text-red-400 space-y-1">
            {{ $fieldError[0] ?? '' }}
        </div>
    @endif
</div>
