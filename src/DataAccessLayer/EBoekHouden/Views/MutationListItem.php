<?php

namespace App\DataAccessLayer\EBoekHouden\Views;

use App\DataAccessLayer\EBoekHouden\Enum\MutationTypeEnum;

class MutationListItem
{
    public function __construct(
        public int              $id,
        public MutationTypeEnum $type,
        public string           $date,
        public string           $invoiceNumber,
        public int              $ledgerId,
        public float            $amount,
        public string           $entryNumber,
    ) {
    }
}
