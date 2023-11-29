<?php

namespace App\View\Components\Cards\Ride;

use Closure;
use App\Models\Ride;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Preview extends Component
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
        return view('components.cards.ride.preview');
    }
}
