<?php

namespace App\View\Components\Buttons;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Form extends Component
{
    /** @var string */
    public $method;

    public function __construct(public ?string $action = null, string $method = 'POST')
    {
        $this->method = strtoupper($method);
    }

    public function render(): View
    {
        return view('components.buttons.form');
    }
}
