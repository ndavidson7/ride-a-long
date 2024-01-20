<?php

namespace App\View\Components\Buttons;

use Closure;
use App\View\Components\ResizableComponent;
use Illuminate\Contracts\View\View;

class Button extends ResizableComponent
{
    public function __construct(public bool $withValidation = false, ?string $size = null)
    {
        parent::__construct($size);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.buttons.button');
    }
}
