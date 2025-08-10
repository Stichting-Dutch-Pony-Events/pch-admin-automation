<?php

namespace App\DataAccessLayer\EBoekHouden\Responses;

class RelationCreatedResponse
{
    public function __construct(
        public int    $id,
        public string $code
    ) {
    }
}
