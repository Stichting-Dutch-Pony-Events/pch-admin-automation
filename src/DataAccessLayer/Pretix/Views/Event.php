<?php

namespace App\DataAccessLayer\Pretix\Views;

class Event
{
    public function __construct(
        public array  $name,
        public string $slug,
        public bool   $live,
        public bool   $testmode,
        public string $currency,
    ) {
    }
}
