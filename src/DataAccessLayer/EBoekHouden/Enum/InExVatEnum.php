<?php

namespace App\DataAccessLayer\EBoekHouden\Enum;

enum InExVatEnum: string
{
    case INCLUDING = 'IN';
    case EXCLUDING = 'EX';
}
