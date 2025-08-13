<?php

namespace App\DataAccessLayer\EBoekHouden\Request;

use App\DataAccessLayer\EBoekHouden\Enum\InExVatEnum;
use App\DataAccessLayer\EBoekHouden\Enum\MutationTypeEnum;

class MutationRequest
{
    /**
     * @param  MutationTypeEnum  $type
     * @param  string  $date
     * @param  int  $ledgerId
     * @param  InExVatEnum  $inExVat
     * @param  string|null  $invoiceNumber
     * @param  string|null  $entryNumber
     * @param  int|null  $termOfPayment
     * @param  string|null  $description
     * @param  bool  $checkPaymentReference
     * @param  string|null  $paymentReference
     * @param  int|null  $relationId
     * @param  MutationRowRequest[]  $rows
     */
    public function __construct(
        public MutationTypeEnum $type,
        public string           $date,
        public int              $ledgerId,
        public InExVatEnum      $inExVat = InExVatEnum::INCLUDING,
        public ?string          $invoiceNumber = null,
        public ?string          $entryNumber = null,
        public ?int             $termOfPayment = null,
        public ?string          $description = null,
        public bool             $checkPaymentReference = false,
        public ?string          $paymentReference = null,
        public ?int             $relationId = null,
        public array            $rows = [],
    ) {
    }
}
