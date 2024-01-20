<?php

namespace App\View\Components\Layouts;

use Illuminate\View\Component;

abstract class Layout extends Component
{
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
}
