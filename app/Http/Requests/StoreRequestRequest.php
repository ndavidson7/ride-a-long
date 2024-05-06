<?php

namespace App\Http\Requests;

use App\Models\Address;
use App\Traits\HasAddressInputs;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequestRequest extends FormRequest
{
    use HasAddressInputs;

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
        $messageRule = ['message' => 'nullable|string'];

        return $this->ride->detours_allowed
            ? array_merge(Address::rules('pickup', false), Address::rules('dropoff', false), $messageRule)
            : $messageRule;
    }
}
