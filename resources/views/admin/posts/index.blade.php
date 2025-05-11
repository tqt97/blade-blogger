<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('common.list') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Header --}}
                    <div class="flex items-center justify-between">
                        <h2 class="font-bold text-xl">{{ __('post.pages.list') }}</h2>
                        <a href="{{ route('admin.posts.create') }}">
                            <x-primary-button>
                                <x-icons.plus class="mr-2 text-white" /> {{ __('common.create') }}
                            </x-primary-button>
                        </a>
                    </div>
                    <div class="flex mt-5 items-center justify-between">
                        {{-- Bulk delete --}}
                        <div class="flex items-center gap-1">
                            <form id="bulk-delete-form" method="POST" action="{{ route('admin.posts.bulk-delete') }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="ids" id="bulk-delete-ids">
                                <x-primary-button type="submit" aria-label="{{ __('Bulk delete') }}"
                                    id="bulk-delete-button" style="display: none;" class="text-white rounded-md transition
                                bg-red-500 hover:bg-red-600
                                disabled:opacity-0 disabled:cursor-not-allowed"
                                    onclick="return confirm('{{ __('Bulk delete') }}')">
                                    {{ __('common.bulk_delete') }}
                                </x-primary-button>
                            </form>
                            <div id="selected-count" class="text-gray-700" style="display: none;">
                                {{ __('common.select') }}
                                <strong id="selected-count-number"></strong>
                                {{ __('common.items') }}
                            </div>
                        </div>
                        {{-- Filter - Search --}}
                        <div class="flex items-center gap-1">
                            <form method="GET" action="{{ route('admin.posts.index') }}">
                                <select name="status" onchange="this.form.submit()" class="rounded-md">
                                    <option value="all">-- {{ __('common.all') }} --</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                        {{ __('common.active') }}
                                    </option>
                                    <option value="trashed" {{ request('status') === 'trashed' ? 'selected' : '' }}>
                                        {{ __('common.trashed') }}
                                    </option>
                                </select>
                            </form>

                            <form action="{{ route('admin.posts.index') }}" method="GET"
                                class="flex items-center gap-1">
                                <input type="search" id="search" class="w-full rounded-md py-[8px]" name="search"
                                    value="{{ request('search') }}" placeholder="{{ __('common.search_placeholder') }}"
                                    required autocomplete="search" />
                                <x-primary-button type="submit">
                                    <x-icons.search class="mr-1 text-white" /> {{ __('common.search') }}
                                </x-primary-button>
                            </form>
                            <a href="{{ route('admin.posts.index') }}" id="clear-filters-button" class="hidden">
                                <x-primary-button>
                                    <x-icons.x-mark class="mr-1 text-white" />{{ __('common.reset') }}
                                </x-primary-button>
                            </a>
                        </div>
                    </div>

                    <div class="shadow-md rounded-md mt-5 overflow-x-auto">
                        <table class="w-full table-striped">
                            <thead>
                                <tr class="bg-gray-800 text-white text-left">
                                    <th class="px-4 py-4 text-left">
                                        <input type="checkbox" name="check-all" id="check-all"
                                            class="rounded-md w-5 h-5 cursor-pointer">
                                    </th>
                                    <th class="px-4 py-4">{{ __('post.columns.title') }}</th>
                                    <th class="px-4 py-4">{{ __('post.columns.category_id') }}</th>
                                    <th class="px-4 py-4">{{ __('post.columns.user_id') }}</th>
                                    <th class="px-4 py-4 text-center">{{ __('post.columns.is_featured') }}</th>
                                    <th class="px-4 py-4 text-center">{{ __('post.columns.status') }}</th>
                                    <th class="px-4 py-4 text-center">{{ __('post.columns.published_at') }}</th>
                                    <th class="px-4 py-4 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($posts as $post)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <input type="checkbox" name="ids[]" id="{{ $post->id }}" value="{{ $post->id }}"
                                                class="checkbox-item rounded-md w-5 h-5 cursor-pointer">
                                        </td>
                                        <td class="px-4 py-3 flex items-center gap-2">
                                            <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                                                class="w-10 h-10 rounded-md border shadow">
                                            {{ $post->title }}
                                        </td>
                                        <td class="px-4 py-3">{{ $post->category->name }}</td>
                                        <td class="px-4 py-3">{{ $post->user->name }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($post->is_featured)
                                                <span class="bg-green-500 text-white py-1 px-2 rounded-full text-xs">
                                                    Yes
                                                </span>
                                            @else
                                                <span class="bg-red-500 text-white py-1 px-2 rounded-full text-xs">
                                                    No
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if ($post->trashed())
                                                <span class="bg-red-500 text-white py-1 px-2 rounded-full text-xs">
                                                    {{ __('common.trashed') }}
                                                </span>
                                            @else
                                                @if($post->is_published)
                                                    <span class="bg-green-500 text-white py-1 px-2 rounded-full text-xs">
                                                        {{ __('common.published') }}
                                                    </span>
                                                @else
                                                    <span class="bg-orange-500 text-white py-1 px-2 rounded-full text-xs">
                                                        {{ __('common.unpublished') }}
                                                    </span>
                                                @endif
                                            @endif

                                        </td>
                                        <td class="px-4 py-3">{{ $post->published_at ?? '-' }}</td>

                                        <td class="flex items-center justify-center gap-2 px-4 py-3">
                                            @if($post->trashed())
                                                <form action="{{ route('admin.posts.restore', $post) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="py-3 text-green-500 hover:text-green-700"
                                                        onclick="return confirm('{{ __('common.confirm_restore') }}')">
                                                        <x-icons.arrow-uturn-left />
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.posts.force-delete', $post) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="py-3 text-red-500 hover:text-red-700"
                                                        onclick="return confirm('{{ __('common.confirm_force_delete') }}')">
                                                        <x-icons.archive-box-x-mark />
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('admin.posts.edit', $post) }}"
                                                    class="text-blue-600 hover:text-blue-800">
                                                    <x-icons.pencil-square />
                                                </a>
                                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                                    onsubmit="return confirm('{{ __('common.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="py-3" aria-label="{{ __('common.delete') }}">
                                                        <x-icons.trash class="text-red-500 hover:text-red-700" />
                                                    </button>
                                                </form>
                                            @endif

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3">
                                            {{ __('common.no_data') }}
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                        <div class="px-3 py-4">
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        @vite('resources/js/admin/posts/index.js')
    @endpush
</x-app-layout>
