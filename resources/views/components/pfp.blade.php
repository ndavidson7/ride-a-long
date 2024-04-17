@props(['user'])

@php
    $pfpUrl = $user->pfp_url;
    $attributes = $attributes->class(['rounded-full']);
@endphp

@if ($pfpUrl)
    <img src="{{ $pfpUrl }}" alt="{{ $user->name }}'s profile picture" {{ $attributes }}>
@else
    <x-fas-circle-user {{ $attributes->class(['bg-white', 'text-gray-400']) }} />
@endif
