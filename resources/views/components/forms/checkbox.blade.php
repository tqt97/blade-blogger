@props([
    'name',
    'label' => '',
    'checked' => false,
])

@php
    $inputId = $attributes->get('id') ?? $name;
    $oldValue = old($name, $checked);
    $isChecked = $oldValue ? 'checked' : '';
@endphp

<input type="hidden" name="{{ $name }}" value="0">
<div class="flex items-center space-x-2">
    <input
        type="checkbox"
        name="{{ $name }}"
        id="{{ $inputId }}"
        value="1"
        {{ $isChecked }}
        {{ $attributes->class(['mt-2 w-5 h-5 rounded-md border-gray-300 focus:ring focus:ring-indigo-500']) }}
    >
    @if($label)
        <label for="{{ $inputId }}" class="text-sm text-gray-700 dark:text-gray-300">{{ $label }}</label>
    @endif
</div>
