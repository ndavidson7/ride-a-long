<?php

namespace App\View\Components\Layouts;

class Main extends Layout
{
    protected function getBodyClasses(): string
    {
        return '';
    }

    protected function getHeader(): string
    {
        return 'headers.navbar';
    }
}
