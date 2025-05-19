@props(['user', 'size' => 6])

<img class="mr-2 w-{{ $size }} h-{{ $size }} rounded-full"
    src="{{ $user->avatar_url ?? 'https://flowbite.com/docs/images/people/profile-picture-4.jpg' }}"
    alt="{{ $user->name }}">
