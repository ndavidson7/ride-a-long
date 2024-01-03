<?php

namespace App\View\Components\Layouts;

class Splash extends Layout
{
    protected function getBodyClasses(): string
    {
        return 'splash';
    }

    protected function getHeader(): string
    {
        return 'headers.splash';
    }
}
