<?php

namespace App\View\Components\Inputs;

use Closure;
use App\View\Components\ResizableComponent;
use Illuminate\Contracts\View\View;

class Input extends ResizableComponent
{
    public function __construct(public bool $validated = false, string $size = 'md')
    {
        parent::__construct($size);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.inputs.input');
    }
}
