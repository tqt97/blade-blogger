@props(['post'])

<div
    class="">
    <div class="p-6">
        <h2 class="text-xl md:text-3xl font-semibold mb-3">
            {{ $post->title}}
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

        <div class="text-gray-600 my-4">
            {!! $post->content !!}
        </div>
    </div>
</div>
