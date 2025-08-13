<?php

namespace App\DataAccessLayer\EBoekHouden\Responses;

class MutationCreatedResponse
{
    public function __construct(
        public int $id
    ) {
    }
}
