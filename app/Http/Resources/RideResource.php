<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'origin' => $this->origin,
            'destination' => $this->destination,
            'start_time' => $this->start_time,
            'seats_total' => $this->seats_total,
            'waypoints' => $this->waypoints,
            'description' => $this->description,
            'driver' => $this->driver,
            'passengers' => $this->whenLoaded('passengers'),
        ];
    }
}
