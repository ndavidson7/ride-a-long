@props(['ride'])

<x-modal.trigger modal-id="ride-info" x-data="{
    rideId: {{ $ride->id }},
    userRelation: '{{ $ride->user_relation }}',
    relatedModelId: {{ $ride->related_model_id ?? 'null' }}
}"
    @click="$dispatch('modal:update', {
        id: modalId,
        args: {
            rideId: rideId,
            userRelation: userRelation,
            relatedModelId: relatedModelId
        }
    });">
    <x-cards.ride.preview :$ride />
</x-modal.trigger>
