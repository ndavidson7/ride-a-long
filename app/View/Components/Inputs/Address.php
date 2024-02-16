<?php

namespace App\View\Components\Inputs;

use Closure;
use App\Models\Address as AddressModel;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Address extends Component
{
    /**
     * Create a new component instance.
     * 
     * @param ?AddressModel $address The address model (to provide values to edit views)
     * @param string $country The country code (default: us)
     * @param int $limit The number of suggestions to display (default: 5)
     */
    public function __construct(
        public ?AddressModel $address = null,
        public string $country = 'us',
        public int $limit = 5,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.inputs.address');
    }
}
