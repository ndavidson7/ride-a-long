@props(['ride'])

<x-modal.trigger data-ride="{{ $ride->append(['user_relation', 'related_model_id'])->toJson() }}" target="ride-info"
    @click="$dispatch('modal:update', { ride: JSON.parse($el.dataset.ride) });">
    <x-cards.ride.preview :$ride />
</x-modal.trigger>
