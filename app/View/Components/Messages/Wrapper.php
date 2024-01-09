<?php

namespace App\View\Components\Messages;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Wrapper extends Component
{
    public array $sender;
    public string $datetime;
    public array $messageChain;

    /**
     * Create a new component instance.
     */
    public function __construct(array $messageWrapper)
    {
        $this->sender = $messageWrapper['sender'];
        $this->datetime = $messageWrapper['datetime'];
        $this->messageChain = $messageWrapper['message_chain'];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.messages.wrapper');
    }
}
