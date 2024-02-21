<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RideFilterRequest extends FormRequest
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
        return [
            'origin-city' => 'nullable|string',
            'origin-state' => 'nullable|string',
            'destination-city' => 'nullable|string',
            'destination-state' => 'nullable|string',
            'start-date' => 'nullable|date',
            'detours' => 'nullable|boolean',
            'my-rides' => 'nullable|boolean',
            // 'exclude-full' => 'nullable|boolean',
        ];
    }
}
