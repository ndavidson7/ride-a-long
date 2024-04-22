@props(['ride'])

<div
    class="flex cursor-pointer flex-col items-center gap-3 rounded-lg border bg-white p-4 shadow-lg hover:bg-blue-100 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">

    {{-- Route and dates --}}
    <div class="flex w-full flex-col items-center gap-x-3 text-center sm:flex-row">

        {{-- Origin and departure --}}
        <div class="flex flex-col-reverse items-center justify-center sm:flex-col">
            <h2 class="text-wrap font-bold sm:text-lg md:text-xl">
                {{ $ride->origin->city }}, {{ $ride->origin->state->code }}
            </h2>
            <span class="text-sm font-medium text-gray-700 md:text-base" x-data
                x-text="dayjs({{ Js::from($ride->start_time) }}).format('ddd, MMM D, h:mm A')"></span>
        </div>

        {{-- Divider, duration, and stops --}}
        <div class="flex w-full flex-1 items-center justify-center gap-x-2">
            <hr class="w-1/3 flex-1 border-t-2 border-dotted border-gray-400">

            <div class="flex items-center justify-center gap-x-2 sm:flex-col">
                <span class="text-xs font-medium md:text-sm">Xh Ym</span>
                <x-fas-car-side class="size-6 text-gray-400" />
                <span class="text-xs font-medium md:text-sm">
                    @if ($ride->detours_allowed)
                        {{ $ride->waypoints->count() }} {{ $ride->waypoints->count() == 1 ? 'stop' : 'stops' }}
                    @else
                        Direct
                    @endif
                </span>
            </div>

            <hr class="flex-1 border-t-2 border-dotted border-gray-400">
        </div>

        {{-- Destination and ETA --}}
        <div class="flex flex-col items-center justify-center">
            <h2 class="text-wrap font-bold sm:text-lg md:text-xl">
                {{ $ride->destination->city }}, {{ $ride->destination->state->code }}
            </h2>
            <span class="text-sm font-medium text-gray-700 md:text-base">ETA</span>
        </div>

    </div>

    {{-- <p class="truncate text-sm text-gray-700">{{ $ride->description }}</p> --}}

    {{-- Driver and ride info pills --}}
    <div class="flex w-full flex-col-reverse items-center gap-3 sm:flex-row sm:justify-between">

        {{-- Driver --}}
        <x-anchors.user :user="$ride->driver" size="sm" />

        {{-- Pills --}}
        <div class="flex flex-wrap items-center justify-center gap-1">
            <x-pill class="gap-1 bg-blue-300 px-2.5 py-1 text-xs font-semibold">
                {{ $ride->seats_total - $ride->seats_open }} passengers
            </x-pill>

            @if ($ride->seats_open > 0)
                <x-pill @class([
                    'gap-1',
                    'bg-blue-300' => $ride->seats_open > 2,
                    'bg-yellow-300' => $ride->seats_open <= 2,
                    'px-2.5',
                    'py-1',
                    'text-xs',
                    'font-semibold',
                ])>
                    {{ $ride->seats_open }} {{ $ride->seats_open == 1 ? 'seat' : 'seats' }} left
                </x-pill>
            @else
                <x-pill class="gap-1 bg-red-300 px-2.5 py-1 text-xs font-semibold">
                    Full
                </x-pill>
            @endif

            @if ($ride->detours_allowed)
                <x-pill class="gap-1 bg-blue-300 px-2.5 py-1 text-xs font-semibold">
                    <x-fas-arrows-turn-to-dots class="size-3" /> Detours
                </x-pill>
            @endif

            @switch($ride->user_relation)
                @case('driver')
                    <x-pill class="gap-1 bg-green-300 px-2.5 py-1 text-xs font-semibold">
                        <x-fas-car-side class="size-4" /> Driving
                    </x-pill>
                @break

                @case('requester')
                    <x-pill class="gap-1 bg-yellow-300 px-2.5 py-1 text-xs font-semibold">
                        <x-fas-hourglass-half class="size-3" /> Requested
                    </x-pill>
                @break

                @case('passenger')
                    <x-pill class="gap-1 bg-green-300 px-2.5 py-1 text-xs font-semibold">
                        <x-fas-car-side class="size-4" /> Riding
                    </x-pill>
                @break
            @endswitch
        </div>

    </div>

</div>
