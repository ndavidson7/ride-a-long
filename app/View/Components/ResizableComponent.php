<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

abstract class ResizableComponent extends Component
{
    private static array $sizes = ['sm', 'md', 'lg'];
    private static string $defaultSize = 'md';

    public string $size;

    /**
     * Create a new component instance.
     * 
     * @param string $size The size of the input (sm, md, lg)
     */
    public function __construct(?string $size = null)
    {
        $this->size = in_array($size, self::$sizes) ? $size : self::$defaultSize;
    }
}
