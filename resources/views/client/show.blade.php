<x-frontend-layout>
    {{-- <x-slot name="toc">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Table of contents') }}
        </h2>
    </x-slot> --}}

    <div class="w-full px-4">
        <x-blogs.show :post="$post" />
    </div>
</x-frontend-layout>
