<?php

namespace App\DataAccessLayer\EBoekHouden\Views;

use App\DataAccessLayer\EBoekHouden\Enum\InExVatEnum;
use App\DataAccessLayer\EBoekHouden\Enum\MutationTypeEnum;
use Carbon\Carbon;
use Symfony\Component\Serializer\Attribute\Context;

class Mutation
{
    /**
     * @param int $id
     * @param MutationTypeEnum $type
     * @param Carbon $date
     * @param int $ledgerId
     * @param InExVatEnum $inExVat
     * @param string|null $description
     * @param int|null $termOfPayment
     * @param string|null $invoiceNumber
     * @param string|null $entryNumber
     * @param int|null $relationId
     * @param MutationRow[] $rows
     * @param VatAmount[] $vat
     */
    public function __construct(
        public int              $id,
        public MutationTypeEnum $type,
        public Carbon           $date,
        public int              $ledgerId,
        public InExVatEnum      $inExVat,
        public ?string          $description = null,
        public ?int             $termOfPayment = null,
        public ?string          $invoiceNumber = null,
        public ?string          $entryNumber = null,
        public ?int             $relationId = null,
        #[Context(['type' => 'array<' . MutationRow::class . '>'])]
        public array            $rows = [],
        #[Context(['type' => 'array<' . VatAmount::class . '>'])]
        public array            $vat = [],
    ) {
    }
}
