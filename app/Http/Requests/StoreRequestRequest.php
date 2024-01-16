<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return $this->ride->detours_allowed ? [
            'pickup-address' => 'nullable',
            'pickup-city' => 'nullable|required_with:pickup-address',
            'pickup-state' => 'nullable|required_with:pickup-address',
            'pickup-country' => 'nullable|required_with:pickup-address',
            'pickup-latitude' => 'nullable|required_with:pickup-address|numeric',
            'pickup-longitude' => 'nullable|required_with:pickup-address|numeric',
            'dropoff-address' => 'nullable',
            'dropoff-city' => 'nullable|required_with:dropoff-address',
            'dropoff-state' => 'nullable|required_with:dropoff-address',
            'dropoff-country' => 'nullable|required_with:dropoff-address',
            'dropoff-latitude' => 'nullable|required_with:dropoff-address|numeric',
            'dropoff-longitude' => 'nullable|required_with:dropoff-address|numeric',
            'message' => 'nullable|string'
        ] : [
            'message' => 'nullable|string'
        ];
    }
}
