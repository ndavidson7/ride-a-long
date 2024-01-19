<?php

namespace App\View\Components\Layouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

abstract class Layout extends Component
{
    protected static string $header = '';

    /**
     * Create a new component instance.
     * 
     * @param string $title The page title
     * @param string[] $entries The vite entry points to load
     */
    public function __construct(
        public string $title,
        public array $entries = []
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.layout', [
            'header' => $this->getHeader(),
        ]);
    }

    protected static function getHeader(): string
    {
        return static::$header;
    }
}
