<x-frontend-layout>
    <div class="w-full px-4">
        <x-blogs.show :post="$post" :comments="$comments" />
    </div>

    @push('js')
        @vite('resources/js/comment.js')
    @endpush
</x-frontend-layout>
