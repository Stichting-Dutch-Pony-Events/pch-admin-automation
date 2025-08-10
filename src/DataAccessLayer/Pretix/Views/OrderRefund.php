<?php

namespace App\DataAccessLayer\Pretix\Views;

use Illuminate\Support\Carbon;

class OrderRefund
{
    public int $localId;
    public string $state;
    public ?string $source;
    public string $amount;
    public ?Carbon $created;
    public ?Carbon $executionDate;
    public string $provider;
    public ?string $comment;

    public function __construct($paymentObj)
    {
        $this->localId = $paymentObj->local_id;
        $this->state = $paymentObj->state;
        $this->source = $paymentObj->source;
        $this->amount = $paymentObj->amount;

        try { $this->created = Carbon::parse($paymentObj->created); } catch (\Exception) { $this->created = null; }
        try { $this->executionDate = Carbon::parse($paymentObj->execution_date); } catch (\Exception) { $this->executionDate = null; }
        $this->comment = $paymentObj->comment;
        $this->provider = $paymentObj->provider;
    }
}
