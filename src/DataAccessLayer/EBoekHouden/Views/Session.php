<?php

namespace App\DataAccessLayer\EBoekHouden\Views;

class Session
{
    public function __construct(
        public string $token,
        public int    $expiresIn,
    ) {
    }
}
