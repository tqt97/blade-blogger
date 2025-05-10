<div class="fixed top-24 left-1/2 transform -translate-x-1/2 z-50 space-y-2 w-full max-w-xs">
    @foreach (['success' => 'green', 'error' => 'red', 'warning' => 'yellow', 'info' => 'blue'] as $type => $color)
        @if (session($type))
            <div
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 3000)"
                x-show="show"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-3"
                class="flex items-center gap-2 relative px-4 py-3 rounded-md shadow-lg border border-{{ $color }}-300 bg-{{ $color }}-500 text-white"
            >
                {{-- Icon --}}
                <div class="shrink-0">
                    @switch($type)
                        @case('success')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            @break
                        @case('error')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            @break
                        @case('warning')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                            @break
                        @case('info')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                            @break
                    @endswitch
                </div>

                {{-- Message --}}
                <span class="block flex-1 text-sm font-medium">{{ session($type) }}</span>

                {{-- Close --}}
                {{-- <button
                    @click="show = false"
                    class="absolute top-1 right-2 text-white hover:text-{{ $color }}-900 text-lg leading-none"
                    aria-label="Close"
                >&times;</button> --}}
            </div>
        @endif
    @endforeach
</div>
