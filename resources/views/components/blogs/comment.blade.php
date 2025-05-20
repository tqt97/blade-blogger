@props(['comment'])

<article id="comment-{{ $comment->id }}"
    class="group relative text-base py-3 bg-white border-gray-200 dark:border-gray-700 dark:bg-gray-900">
    <div class="flex justify-between items-center mb-2">
        <div class="flex items-center">
            <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">
                <x-users.avatar :user="$comment->user" size="6" />
                {{ $comment->user->name }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-400"><time>{{ $comment->created_at->diffForHumans() }}</time>
            </p>
        </div>
    </div>

    <div id="content-{{ $comment->id }}" class="text-gray-500 dark:text-gray-400">{{ $comment->content }}</div>

    <div class="flex items-center mt-1 flex-wrap gap-x-4 text-sm text-gray-500 dark:text-gray-400">
        <button type="button" data-action="reply" data-id="{{ $comment->id }}"
            class="flex items-center text-sm hover:underline dark:text-gray-400 font-medium">
            Reply
        </button>

        @if (auth()->id() === $comment->user_id || auth()->user()?->isAdmin())
            <button type="button" data-action="edit" data-id="{{ $comment->id }}" class="hover:underline">
                Edit
            </button>

            <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="delete-comment-form inline-block"
                data-comment-id="{{ $comment->id }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline">Delete</button>
            </form>

            <a href="#" class="hover:underline">Report</a>
        @endif
    </div>


    {{-- EDIT FORM --}}
    <form method="POST" id="edit-form-{{ $comment->id }}" class="mt-2 ajax-edit-form slide-toggle"
        action="{{ route('comments.update', $comment) }}" data-comment-id="{{ $comment->id }}"
        data-url="{{ route('comments.update', $comment) }}">
        @csrf
        @method('PATCH')
        <textarea name="content" rows="2" class="w-full border rounded p-2">{{ $comment->content }}</textarea>
        <div class="flex gap-2 mt-1">
            <button type="submit" data-action="edit" data-id="{{ $comment->id }}"
                class="inline-flex items-center px-2 py-[6px] bg-gray-800 hover:bg-gray-900 text-white text-xs rounded-md cursor-pointer">Update</button>
            <button type="button" data-comment-id="{{ $comment->id }}" class="cancel-edit-btn text-gray-600">
                Cancel
            </button>

        </div>
    </form>

    {{-- reply form --}}
    <form id="reply-form-{{ $comment->id }}" class="slide-toggle mt-2"
        action="{{ route('comments.reply', $comment->id) }}" method="POST">
        @csrf
        <input type="hidden" name="post_id" value="{{ $comment->post->id }}">
        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
        <textarea name="content" rows="2" placeholder="Reply to {{ $comment->user->name }}"
            class="w-full border rounded p-2"></textarea>
        <button type="submit"
            class="mt-1 inline-flex items-center px-3 py-[6px] bg-gray-800 hover:bg-gray-900 text-white text-sm rounded-md cursor-pointer">
            Reply
        </button>
        <button type="button" data-comment-id="{{ $comment->id }}" class="cancel-reply-btn text-gray-600">
            Cancel
        </button>
    </form>

    {{-- children --}}
    @if ($comment->children->count())
        <div class="replies-container pl-6 space-y-3 border-l border-gray-300 border-dotted children-comments">
            @foreach ($comment->children as $child)
                <x-blogs.comment :comment="$child" />
            @endforeach
        </div>
    @endif
</article>
