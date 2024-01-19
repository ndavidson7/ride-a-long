<?php

namespace App\View\Components\Inputs;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Floating extends Component
{
    public string $component;

    /**
     * Create a new component instance.
     * 
     * 
     */
    public function __construct(string $input)
    {
        $this->component = "inputs.{$input}";
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.inputs.floating');
    }
}
