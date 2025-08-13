<?php

namespace App\DataAccessLayer\EBoekHouden\Request;

use App\DataAccessLayer\EBoekHouden\Enum\VatCodeEnum;

class MutationRowRequest
{
    public function __construct(
        public int         $ledgerId,
        public VatCodeEnum $vatCode,
        public float       $amount,
        public ?float      $vatAmount = null,
        public ?string     $description = null,
        public ?string     $invoiceNumber = null,
        public ?int        $relationId = null,
    ) {
    }
}
