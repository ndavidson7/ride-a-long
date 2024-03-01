<?php

namespace App\View\Components\Modals;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Modal extends Component
{
    public array $xData = ['open' => false];

    /**
     * Create a new component instance.
     */
    public function __construct(
        array $xData = [],
    ) {
        $this->xData = array_merge($this->xData, $xData);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modals.modal');
    }
}
