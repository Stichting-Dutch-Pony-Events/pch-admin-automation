<?php

namespace App\DataAccessLayer\EBoekHouden\Responses;

use App\DataAccessLayer\EBoekHouden\Views\RelationListItem;
use Symfony\Component\Serializer\Attribute\Context;

class RelationListResponse
{
    /**
     * @param  RelationListItem[]  $items
     * @param  int  $count
     */
    public function __construct(
        #[Context(['type' => 'array<'.RelationListItem::class.'>'])]
        public array $items,
        public int   $count,
    ) {
    }
}
