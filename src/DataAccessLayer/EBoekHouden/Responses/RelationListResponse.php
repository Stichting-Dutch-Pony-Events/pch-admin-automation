<?php

namespace App\DataAccessLayer\EBoekHouden\Responses;

use App\DataAccessLayer\EBoekHouden\Views\Relation;
use Symfony\Component\Serializer\Attribute\Context;

class RelationListResponse
{
    /**
     * @param  Relation[]  $items
     * @param  int  $count
     */
    public function __construct(
        #[Context(['type' => 'array<'.Relation::class.'>'])]
        public array $items,
        public int   $count,
    ) {
    }
}
