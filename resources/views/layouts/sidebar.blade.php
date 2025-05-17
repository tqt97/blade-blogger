<div class="rounded-lg hidden md:block w-1/4 sticky top-2 self-start">
    @isset($toc)
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $toc }}
            </div>
        </header>
    @endisset
    <div class="bg-white shadow-md px-6 py-3 rounded-lg">
        <h2 class="mb-2 my-1 font-semibold border-b pb-2">Categories</h2>
        <ul class="flex flex-wrap gap-1">
            @foreach ($categories as $category)
                <li class="px-2">
                    <a href="{{ route('home', ['category' => $category]) }}"
                        class="text-gray-600 hover:text-gray-900 capitalize hover:underline">
                        {{ $category->name }}
                        <span class="text-gray-800 text-sm">
                            ({{ $category->posts_count }})
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div class="bg-white shadow-md px-6 py-3 mt-5 rounded-lg">
        <h2 class="mb-2 my-1 font-semibold border-b pb-2">Tags</h2>
        <ul class="flex flex-wrap gap-1">
            @foreach ($tags as $tag)
                <li class="px-2">
                    <a href="{{ route('home', ['tag' => $tag]) }}"
                        class="text-gray-600 capitalize hover:underline hover:text-gray-900">
                        {{ $tag->name }}
                        <span class="text-gray-800 text-sm">
                            ({{ $tag->posts_count }})
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
