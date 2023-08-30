<?php

namespace App\Enums;

enum MapType: string
{
    case Preview = 'preview';
    case Info = 'info';
    case Request = 'request';
    case Posted = 'posted';
    case Joined = 'joined';
}
