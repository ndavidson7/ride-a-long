@props(['ride' => null, 'request' => null])

<div {{ $attributes->class(['@container', 'relative', 'space-y-4', '@5xl:space-y-0']) }} x-data="map"
    @if ($request || $ride) x-init="
        @if ($request) request = {{ Js::from($request) }}; @endif
    @if ($ride) ride = {{ Js::from($ride) }}; @endif "  @endif
    @map:set-origin.window="origin = $event.detail" @map:set-destination.window="destination = $event.detail"
    @map:set-pickup.window="pickup = $event.detail" @map:set-dropoff.window="dropoff = $event.detail"
    x-intersect="onIntersect">

    {{-- Map container element --}}
    <div class="aspect-video rounded-lg" x-ref="map"></div>

    {{-- Route info --}}
    <div class="left-2.5 top-2.5 divide-y divide-[#ddd] overflow-hidden rounded-lg bg-white shadow-[0_0_0_2px_rgba(0,0,0,.1)] @5xl:absolute"
        x-ref="route">
        <div class="flex flex-wrap items-center gap-3 p-3">
            <div class="space-x-1">
                <x-fas-route class="size-5 inline-block text-neutral-600" />
                <span class="align-middle text-base font-medium text-neutral-600" x-text="totalDistance"></span>
            </div>
            <div class="space-x-1">
                <x-fas-clock class="size-5 inline-block text-neutral-600" />
                <span class="align-middle text-base font-medium text-neutral-600" x-text="totalDuration"></span>
            </div>
        </div>
        <ol>
            <template x-for="(stop, index) in route">
                <li class="relative grid cursor-pointer grid-cols-[auto_1fr_auto] items-center gap-3 p-3 text-sm font-medium before:absolute before:bottom-0 before:left-[23px] before:top-0 before:z-0 before:border-x before:border-dashed before:border-blue-400 hover:bg-blue-100"
                    :class="index === 0 ? 'before:!top-1/2' : index === route.length - 1 ?
                        'before:!bottom-1/2' : ''"
                    @click="flyToMarker(index)">
                    <div class="size-6 relative z-10 grid place-items-center rounded-full bg-blue-700 text-white"
                        x-text="index + 1"></div>
                    <div>
                        <div x-text="stop?.address"></div>
                        <template x-if="stop?.dropoffs?.length">
                            <div class="text-xs font-normal"
                                x-text="`Dropoff ${stop?.dropoffs.map(user => user.name).join(', ')}`"></div>
                        </template>
                        <template x-if="stop?.pickups?.length">
                            <div class="text-xs font-normal"
                                x-text="`Pickup ${stop?.pickups.map(user => user.name).join(', ')}`"></div>
                        </template>
                    </div>
                    <div class="text-xs font-normal" x-text="stop?.duration ?? ''">
                    </div>
                </li>
            </template>
        </ol>
    </div>

</div>
