<?php

namespace App\DataAccessLayer\EBoekHouden\Views;

use App\DataAccessLayer\EBoekHouden\Enum\RelationTypeEnum;

class RelationListItem
{
    public function __construct(
        public int              $id,
        public RelationTypeEnum $type,
        public string           $code,
    ) {
    }
}
