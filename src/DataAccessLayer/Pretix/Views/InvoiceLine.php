<?php

namespace App\DataAccessLayer\Pretix\Views;

use Carbon\Carbon;

class InvoiceLine
{
    public int $position;
    public ?string $description;
    public ?int $item;
    public ?int $variation;
    public ?int $subevent;
    public ?string $feeType;
    public ?string $feeInternalType;
    public ?Carbon $eventDateFrom;
    public ?Carbon $eventDateTo;
    public ?string $eventLocation;
    public ?string $attendeeName;
    public ?float $grossValue;
    public ?float $taxValue;
    public ?string $taxName;
    public ?float $taxRate;

    public function __construct($item)
    {
        $this->position        = $item->position;
        $this->description     = $item->description;
        $this->item            = $item->item;
        $this->variation       = $item->variation;
        $this->subevent        = $item->subevent;
        $this->feeType         = $item->fee_type;
        $this->feeInternalType = $item->fee_internal_type;
        try {
            $this->eventDateFrom = Carbon::parse($item->event_date_from);
        } catch (\Exception) {
            $this->eventDateFrom = null;
        }
        try {
            $this->eventDateTo = Carbon::parse($item->event_date_to);
        } catch (\Exception) {
            $this->eventDateTo = null;
        }
        $this->eventLocation = $item->event_location;
        $this->attendeeName  = $item->attendee_name;
        $this->grossValue    = (float)$item->gross_value;
        $this->taxValue      = (float)$item->tax_value;
        $this->taxName       = $item->tax_name;
        $this->taxRate       = (float)$item->tax_rate;
    }
}
