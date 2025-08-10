<?php

namespace App\DataAccessLayer\Pretix\Enum;

enum CheckInErrorReason: string
{
    case INVALID = 'invalid';
    case UNPAID = 'unpaid';
    case BLOCKED = 'blocked';
    case INVALID_TIME = 'invalid_time';
    case CANCELED = 'canceled';
    case ALREADY_REDEEMED = 'already_redeemed';
    case PRODUCT = 'product';
    case RULES = 'rules';
    case AMBIGUOUS = 'ambiguous';
    case REVOKED = 'revoked';
    case ERROR = 'error';
}
