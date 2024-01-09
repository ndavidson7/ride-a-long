<?php

namespace App\View\Components\Messages;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Message extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public array $sender,
        public array $messageInfo
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.messages.message');
    }
}
