<?php

namespace App\DataAccessLayer\Pretix\Views;

use Illuminate\Support\Carbon;

class Order
{
    public string $code;
    public string $status;
    public string $email;
    public bool $testMode;
    public ?Carbon $datetime;
    public ?Carbon $expires;
    public ?string $total;

    /** @var OrderPosition[] $positions */
    public ?array $positions;

    /** @var OrderPayment[] $payments */
    public ?array $payments;
    /** @var  OrderRefund[] $refunds */
    public ?array $refunds;
    public ?Carbon $lastModified;

    public ?InvoiceAddress $invoiceAddress;

    public function __construct($orderObj)
    {
        $this->code = $orderObj->code;
        $this->status = $orderObj->status;
        $this->testMode = $orderObj->testmode;
        $this->email = $orderObj->email;
        try {
            $this->datetime = Carbon::parse($orderObj->datetime);
        } catch (\Exception) {
            $this->datetime = null;
        }
        try {
            $this->expires = Carbon::parse($orderObj->expires);
        } catch (\Exception) {
            $this->expires = null;
        }
        $this->total = $orderObj->total;
        if (is_array($orderObj->payments)) {
            $this->payments = [];
            foreach ($orderObj->payments as $payment) {
                $this->payments[] = new OrderPayment($payment);
            }
        } else {
            $this->payments = null;
        }

        if (is_array($orderObj->refunds)) {
            $this->refunds = [];
            foreach ($orderObj->refunds as $refund) {
                $this->refunds[] = new OrderRefund($refund);
            }
        } else {
            $this->refunds = null;
        }

        if (is_array($orderObj->positions)) {
            $this->positions = [];
            foreach ($orderObj->positions as $position) {
                $this->positions[] = new OrderPosition($position);
            }
        }

        try {
            $this->lastModified = Carbon::parse($orderObj->last_modified);
        } catch (\Exception) {
            $this->lastModified = null;
        }
        $this->invoiceAddress = new InvoiceAddress($orderObj->invoice_address);
    }
}
