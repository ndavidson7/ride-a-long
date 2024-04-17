<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateRideRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by RidePolicy
        return true;
    }

    private function getAddress($location): array
    {
        return [
            'street_address' => $this->input($location . '-streetAddress'),
            'city' => $this->input($location . '-city'),
            'state_name' => $this->input($location . '-state'),
            'state_code' => $this->input($location . '-stateCode'),
            'postal_code' => $this->input($location . '-postalCode'),
            'country_name' => $this->input($location . '-country'),
            'country_code' => $this->input($location . '-countryCode'),
            'latitude' => $this->input($location . '-latitude'),
            'longitude' => $this->input($location . '-longitude'),
        ];
    }

    public function origin(): array
    {
        return $this->getAddress('origin');
    }

    public function destination(): array
    {
        return $this->getAddress('destination');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'start-time' => 'required|date|after_or_equal:' . now()->setTimezone('America/New_York')->format('Y-m-d\TH:i'),
            'seats' => 'required|numeric|min:1',
            'origin-streetAddress' => 'required|string',
            'origin-city' => 'required|string',
            'origin-state' => 'required|string',
            'origin-stateCode' => 'required|string',
            'origin-postalCode' => 'required|string',
            'origin-country' => 'required|string',
            'origin-countryCode' => 'required|string',
            'origin-latitude' => 'required|numeric',
            'origin-longitude' => 'required|numeric',
            'destination-streetAddress' => 'required|string',
            'destination-city' => 'required|string',
            'destination-state' => 'required|string',
            'destination-stateCode' => 'required|string',
            'destination-postalCode' => 'required|string',
            'destination-country' => 'required|string',
            'destination-countryCode' => 'required|string',
            'destination-latitude' => 'required|numeric',
            'destination-longitude' => 'required|numeric',
            'detours' => 'nullable|boolean',
            // 'pricing' => 'required|in:seat,mile',
            // 'price' => 'required|decimal:0,2|min:0',
            'description' => 'nullable|string',
        ];
    }
}
