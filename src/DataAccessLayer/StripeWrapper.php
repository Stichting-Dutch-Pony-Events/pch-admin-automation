<?php

namespace App\DataAccessLayer;

use Stripe\StripeClient;

class StripeWrapper
{
    private ?StripeClient $stripe = null;

    public function __construct(private readonly string $stripeApiKey)
    {
    }

    public function getStripeClient(): StripeClient
    {
        if ($this->stripe === null) {
            $this->stripe = new StripeClient($this->stripeApiKey);
        }

        return $this->stripe;
    }
}
