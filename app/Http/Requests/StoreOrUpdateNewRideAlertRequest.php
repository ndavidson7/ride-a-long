<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateNewRideAlertRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'origin-address' => 'required',
            'origin-city' => 'required',
            'origin-state' => 'required',
            'origin-country' => 'required',
            'origin-latitude' => 'required|numeric',
            'origin-longitude' => 'required|numeric',
            'origin-radius' => 'required|integer|min:0|max:100',
            'destination-address' => 'required',
            'destination-city' => 'required',
            'destination-state' => 'required',
            'destination-country' => 'required',
            'destination-latitude' => 'required|numeric',
            'destination-longitude' => 'required|numeric',
            'destination-radius' => 'required|integer|min:0|max:100',
            // 'strict' => 'required|boolean',
            'start-date' => 'required|date|after_or_equal:' . now()->setTimezone('America/New_York')->format('Y-m-d'),
            'end-date' => 'required|date|after:start_date',
        ];
    }
}
