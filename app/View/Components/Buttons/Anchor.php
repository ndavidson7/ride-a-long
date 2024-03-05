<?php

namespace App\View\Components\Buttons;

use Closure;
use App\View\Components\ResizableComponent;
use Illuminate\Contracts\View\View;

class Anchor extends ResizableComponent
{
    public function __construct(string $size = 'md')
    {
        parent::__construct($size);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.buttons.anchor');
    }
}
