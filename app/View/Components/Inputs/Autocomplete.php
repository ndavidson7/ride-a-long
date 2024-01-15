<?php

namespace App\View\Components\Inputs;

use Closure;
use App\Models\Address;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Autocomplete extends Component
{
    /**
     * Create a new component instance.
     * 
     * @param ?Address $address The address model (to provide values to edit views)
     */
    public function __construct(
        public ?Address $address = null,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.inputs.autocomplete');
    }
}
