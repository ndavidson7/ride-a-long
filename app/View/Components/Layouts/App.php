<?php

namespace App\View\Components\Layouts;

use Closure;
use Illuminate\Contracts\View\View;

class App extends Layout
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.app');
    }
}
