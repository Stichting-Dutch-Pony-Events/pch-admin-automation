<?php

namespace App\DataAccessLayer\PayPal\Views;

class Amount
{
    public function __construct(
        public string $currencyCode,
        public float  $value,
    ) {
    }
}
