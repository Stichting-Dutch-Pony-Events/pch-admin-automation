<?php

namespace App\DataAccessLayer\EBoekHouden\Views;

class Ledger
{
    public function __construct(
        public int    $id,
        public string $code,
        public string $description,
    ) {
    }
}
