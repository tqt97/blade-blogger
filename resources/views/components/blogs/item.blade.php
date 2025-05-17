@props(['post'])

<div
    class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden border border-gray-100">
    <div class="p-6">
        <h2 class="text-xl font-semibold mb-3 line-clamp-2 min-h-[3.5rem]">
            <a href="{{ route('posts.show', $post) }}">
                {{ $post->title}}
            </a>
        </h2>

        <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
            <div class="flex items-center gap-1">
                <x-icons.tag />
                <span>{{ $post->category->name }}</span>
            </div>
            <div class="flex items-center gap-1">
                <x-icons.message />
                <span>1</span>
            </div>
            <div class="flex items-center gap-1">
                <x-icons.calendar />
                <span>{{ $post->created_at->diffForHumans() }}</span>
            </div>
        </div>

        <p class="text-gray-600 mb-4 line-clamp-2">
            {!! $post->content !!}
        </p>
        <div class="flex justify-center gap-2">
            <button
                class='group inline-flex w-full justify-center gap-2 items-center px-4 py-[12px] hover:bg-gray-800 border rounded-md font-semibold text-sm text-gray-800 hover:text-gray-50  transition ease-in-out duration-150'
                onClick="window.location.href='{{ route('posts.show', $post) }}'">
                Read More <x-icons.arrow-up-right
                    class="group-hover:translate-x-1 transition ease-in-out duration-150 group-hover:rotate-45" />
            </button>
        </div>

    </div>
</div>
