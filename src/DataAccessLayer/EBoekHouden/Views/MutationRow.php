<?php

namespace App\DataAccessLayer\EBoekHouden\Views;

use App\DataAccessLayer\EBoekHouden\Enum\VatCodeEnum;

class MutationRow
{
    public function __construct(
        public int         $ledgerId,
        public VatCodeEnum $vatCode,
        public float       $amount,
        public ?string     $description = null,
        public ?string     $invoiceNumber = null,
        public ?int        $relationId = null,
        public ?int        $costCenterId = null,
    ) {
    }
}
