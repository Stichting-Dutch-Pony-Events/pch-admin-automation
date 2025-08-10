<?php

namespace App\DataAccessLayer\EBoekHouden\FilterParams\Enum;

enum StringFilterType: string
{
    case EQUAL = '[eq]';
    case NOT_EQUAL = '[not_eq]';
    case LIKE = '[like]';
    case NOT_LIKE = '[not_like]';
}
