<?php

namespace App\DataAccessLayer\EBoekHouden\Responses;

use App\DataAccessLayer\EBoekHouden\Views\MutationListItem;
use Symfony\Component\Serializer\Attribute\Context;

class MutationListResponse
{
    /**
     * @param MutationListItem[] $items
     * @param int $count
     */
    public function __construct(
        #[Context(['type' => 'array<' . MutationListItem::class . '>'])]
        public array $items,
        public int   $count
    ) {
    }
}
