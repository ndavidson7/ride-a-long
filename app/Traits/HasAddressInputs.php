<?php

namespace App\Traits;

trait HasAddressInputs
{
    public function getAddress($location): array
    {
        return [
            'street_address' => $this->input($location . '.street_address'),
            'city' => $this->input($location . '.city'),
            'state' => [
                'name' => $this->input($location . '.state_name'),
                'code' => $this->input($location . '.state_code'),
            ],
            'postal_code' => $this->input($location . '.postal_code'),
            'country' => [
                'name' => $this->input($location . '.country_name'),
                'code' => $this->input($location . '.country_code'),
            ],
            'latitude' => $this->input($location . '.latitude'),
            'longitude' => $this->input($location . '.longitude'),
        ];
    }
}
