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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'start-time' => 'required|date|after:now',
            'seats' => 'required|numeric|min:1',
            'origin-address' => 'required',
            'origin-city' => 'required',
            'origin-state' => 'required',
            'origin-country' => 'required',
            'origin-latitude' => 'required|numeric',
            'origin-longitude' => 'required|numeric',
            'destination-address' => 'required',
            'destination-city' => 'required',
            'destination-state' => 'required',
            'destination-country' => 'required',
            'destination-latitude' => 'required|numeric',
            'destination-longitude' => 'required|numeric',
            // 'pricing' => 'required|in:seat,mile',
            // 'price' => 'required|decimal:0,2|min:0',
            'description' => 'nullable|string',
        ];
    }
}
