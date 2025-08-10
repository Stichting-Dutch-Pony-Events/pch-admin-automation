<?php

namespace App\DataAccessLayer\EBoekHouden\FilterParams\Enum;

enum DateFilterType: string
{
    case EQUAL = '[eq]';
    case NOT_EQUAL = '[not_eq]';
    case LESS_THAN = '[lt]';
    case LESS_THAN_OR_EQUAL = '[lte]';
    case GREATER_THAN = '[gt]';
    case GREATER_THAN_OR_EQUAL = '[gte]';
    case RANGE = '[range]';
}
