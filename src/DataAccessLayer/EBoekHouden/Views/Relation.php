<?php

namespace App\DataAccessLayer\EBoekHouden\Views;

use App\DataAccessLayer\EBoekHouden\Enum\RelationTypeEnum;

class Relation
{
    public function __construct(
        public int              $id,
        public RelationTypeEnum $relationType,
        public string           $code,
        public string           $name,
        public string           $address = '',
        public string           $postalCode = '',
        public string           $city = '',
        public string           $country = '',
        public string           $emailAddress = '',
    ) {
    }
}
