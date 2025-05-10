@props([
    'name',
    'label' => '',
    'options' => [],
    'selected' => null,
    'placeholder' => '',
    'required' => false,
])

@if($label)
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
@endif
<select name="{{ $name }}" id="{{ $name }}"
    class="mt-2 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
@if($required) required @endif
>
@if($placeholder)
    <option value="">{{ $placeholder }}</option>
@endif
    @foreach ($options as $value => $label)
        <option value="{{ $value }}"
                {{ old($name, $selected) == $value ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach
</select>
