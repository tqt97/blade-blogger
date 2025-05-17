<x-frontend-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @include('layouts.filter')

    <div class="md:col-span-2 col-span-1 w-full px-4">
        <div class="grid md:grid-cols-2 col-span-full gap-8">
            @foreach ($posts as $post)
                <x-blogs.item :post="$post" />
            @endforeach
        </div>
        <div class="my-5">
            {{ $posts->links() }}
        </div>
    </div>
</x-frontend-layout>
