<?php

namespace App\DataAccessLayer\Pretix\Enum;

enum CheckInStatus: string
{
    case OK = 'ok';
    case ERROR = 'error';
    case INCOMPLETE = 'incomplete';
}
