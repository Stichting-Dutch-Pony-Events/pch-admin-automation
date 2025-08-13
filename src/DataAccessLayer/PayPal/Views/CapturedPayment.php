<?php

namespace App\DataAccessLayer\PayPal\Views;

class CapturedPayment
{
    public function __construct(
        public string                    $id,
        public Amount                    $amount,
        public bool                      $finalCapture,
        public string                    $status,
        public SellerReceivableBreakdown $sellerReceivableBreakdown
    ) {
    }
}
