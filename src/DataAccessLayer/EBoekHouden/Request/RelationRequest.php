<?php

namespace App\DataAccessLayer\EBoekHouden\Request;

use App\DataAccessLayer\EBoekHouden\Enum\RelationTypeEnum;

class RelationRequest
{
    public function __construct(
        public RelationTypeEnum $type,
        public string           $code,
        public string           $name,
        public string           $address,
        public string           $postalCode,
        public string           $city,
        public string           $country,
        public string           $emailAddress,
    ) {
        $this->emailAddress = strtolower($emailAddress);
        $this->code = substr($this->code, 0, 15);
    }
}
