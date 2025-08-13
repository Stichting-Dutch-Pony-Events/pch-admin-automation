<?php

namespace App\DataAccessLayer\PayPal\Views;

class SellerReceivableBreakdown
{
    public function __construct(
        public Amount $grossAmount,
        public Amount $paypalFee,
        public Amount $netAmount,
    ) {
    }
}
