<?php

namespace App\DataAccessLayer\Pretix\Enum;

enum CheckInType: string
{
    case ENTRY = 'entry';
    case EXIT = 'exit';
}
