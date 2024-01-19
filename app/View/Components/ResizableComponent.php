<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

abstract class ResizableComponent extends Component
{
    const SIZES = ['sm', 'md', 'lg'];
    const DEFAULT_SIZE = 'md';

    public string $size;

    /**
     * Create a new component instance.
     * 
     * @param string $size The size of the input (sm, md, lg)
     */
    public function __construct(string $size = self::DEFAULT_SIZE)
    {
        $this->size = in_array($size, ['sm', 'md', 'lg']) ? $size : self::DEFAULT_SIZE;
    }
}
