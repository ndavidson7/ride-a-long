<?php

namespace App\View\Components;

use Closure;
use App\Models\Ride;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class RideCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Ride $ride,
        public array $without = [],
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ride-card');
    }
}
