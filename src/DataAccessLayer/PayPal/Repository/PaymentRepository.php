<?php

namespace App\DataAccessLayer\PayPal\Repository;

use App\DataAccessLayer\PayPal\Views\CapturedPayment;
use App\Util\Exceptions\Exception\Common\InvalidApiResponseException;

class PaymentRepository extends BaseRepository
{
    public function getPaymentByID(string $paymentID): CapturedPayment
    {
        $capturedPayment = $this->retrieveOne('v2/payments/captures/'.$paymentID, CapturedPayment::class);

        if (!$capturedPayment instanceof CapturedPayment) {
            throw new InvalidApiResponseException('Invalid response type for payment retrieval by ID.');
        }

        return $capturedPayment;
    }
}
