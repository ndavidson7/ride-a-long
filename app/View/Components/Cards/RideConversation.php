<?php

namespace App\View\Components\Cards;

use Closure;
use App\Models\Ride;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class RideConversation extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Ride $ride,
        public array|string $messageWrappers,
        public array $users,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cards.ride-conversation');
    }
}
