<?php

namespace App\View\Components\Modals;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Enums\MapType;

class Map extends Component
{
    /**
     * Create a new component instance.
     * 
     * @param string $type 
     */
    public function __construct(
        public MapType $type
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modals.map');
    }
}
