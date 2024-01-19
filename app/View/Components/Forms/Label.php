<?php

namespace App\View\Components\Forms;

use Illuminate\Support\Str;
use BladeUIKit\Components\Forms\Label as OriginalLabel;

class Label extends OriginalLabel
{
    // Overwrite component parent here...
    public function fallback(): string
    {
        return Str::ucfirst(str_replace(['_', '-'], ' ', $this->for));
    }
}
