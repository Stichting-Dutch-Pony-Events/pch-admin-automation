<?php

namespace App\DataAccessLayer\Pretix\Views;

use Illuminate\Support\Carbon;

class OrderPayment
{
    public int $localId;
    public string $state;
    public string $amount;
    public ?Carbon $created;
    public ?Carbon $paymentDate;
    public string $provider;
    public ?object $details;

    public function __construct($paymentObj)
    {
        $this->localId = $paymentObj->local_id;
        $this->state = $paymentObj->state;
        $this->amount = $paymentObj->amount;

        try { $this->created = Carbon::parse($paymentObj->created); } catch (\Exception) { $this->created = null; }
        try { $this->paymentDate = Carbon::parse($paymentObj->payment_date); } catch (\Exception) { $this->paymentDate = null; }

        $this->provider = $paymentObj->provider;
        $this->details = $paymentObj->details;
    }

    public function getPaymentId() {
        if(str_contains($this->provider, 'stripe')) {
            if(property_exists($this->details, 'id'))
                return $this->details->id;
        } elseif (str_contains($this->provider, 'paypal')) {
            if(property_exists($this->details, 'payment_id'))
                return $this->details->payment_id;
        }
        return null;
    }
}
