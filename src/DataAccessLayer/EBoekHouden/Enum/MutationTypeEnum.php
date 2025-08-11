<?php

namespace App\DataAccessLayer\EBoekHouden\Enum;

enum MutationTypeEnum: int
{
    case INVOICE_RECEIVED = 1;
    case INVOICE_SENT = 2;
    case INVOICE_PAYMENT_RECEIVED = 3;
    case INVOICE_PAYMENT_SENT = 4;
    case MONEY_RECEIVED = 5;
    case MONEY_SENT = 6;
    case MEMORIAL = 7;
}
