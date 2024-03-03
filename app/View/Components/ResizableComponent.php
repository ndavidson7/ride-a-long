<?php

namespace App\View\Components;

use Illuminate\View\Component;

abstract class ResizableComponent extends Component
{
    private static array $sizes = ['sm', 'md', 'lg'];

    public string $size;

    /**
     * Create a new component instance.
     * 
     * @param string $size The size of the input (sm, md, lg)
     */
    public function __construct(string $size)
    {
        if (!in_array($size, self::$sizes)) {
            throw new \InvalidArgumentException("Invalid size: $size. Valid sizes are: " . implode(', ', self::$sizes));
        }

        $this->size = $size;
    }
}
