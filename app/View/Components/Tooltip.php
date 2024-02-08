<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Tooltip extends Component
{
    public function __construct(
        public string $text,
        public string $position = 'top',
        public bool $arrow = true,
        public bool $visible = false
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tooltip');
    }
}
