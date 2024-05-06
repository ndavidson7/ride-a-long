<?php

namespace App\Http\Requests;

use App\Models\Address;
use App\Traits\HasAddressInputs;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateRideRequest extends FormRequest
{
    use HasAddressInputs;

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
        return array_merge(Address::rules('origin'), Address::rules('destination'), [
            'start-time' => 'required|date|after_or_equal:' . now()->setTimezone('America/New_York')->format('Y-m-d\TH:i'),
            'seats' => 'required|numeric|min:1',
            'detours' => 'nullable|boolean',
            // 'pricing' => 'required|in:seat,mile',
            // 'price' => 'required|decimal:0,2|min:0',
            'description' => 'nullable|string',
        ]);
    }
}
