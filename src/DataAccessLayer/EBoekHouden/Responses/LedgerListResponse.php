<?php

namespace App\DataAccessLayer\EBoekHouden\Responses;

use App\DataAccessLayer\EBoekHouden\Views\Ledger;
use Symfony\Component\Serializer\Attribute\Context;

class LedgerListResponse
{
    /**
     * @param  Ledger[]  $items
     * @param  int  $count
     */
    public function __construct(
        #[Context(['type' => 'array<'.Ledger::class.'>'])]
        public array $items,
        public int   $count
    ) {
    }
}
