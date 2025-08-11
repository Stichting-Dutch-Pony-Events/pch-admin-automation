<?php

namespace App\DataAccessLayer\EBoekHouden\Views;

use App\DataAccessLayer\EBoekHouden\Enum\MutationTypeEnum;
use Carbon\Carbon;

class MutationListItem
{
    public function __construct(
        public int              $id,
        public MutationTypeEnum $type,
        public Carbon           $date,
        public string           $invoiceNumber,
        public int              $ledgerId,
        public float            $amount,
        public string           $entryNumber,
    ) {
    }
}
