<?php

namespace App\DataAccessLayer\EBoekHouden\Views;

use App\DataAccessLayer\EBoekHouden\Enum\VatCodeEnum;

class VatAmount
{
    public function __construct(
        public VatCodeEnum $vatCode,
        public float       $amount
    ) {
    }
}
