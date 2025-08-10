<?php

namespace App\DataAccessLayer\Pretix\Views;

use Illuminate\Support\Carbon;

class InvoiceAddress
{
    public ?Carbon $lastModified;
    public ?string $company;
    public bool $isBusiness;
    public ?string $name;
    public ?string $street;
    public ?string $zipCode;
    public ?string $city;
    public ?string $country;
    public ?string $state;
    public ?string $internalReference;
    public ?string $vatId;

    public function __construct($invAddObj)
    {
        try {
            $this->lastModified = Carbon::parse($invAddObj->lastModified);
        } catch (\Exception) {
            $this->lastModified = null;
        }
        $this->company = $invAddObj->company;
        $this->isBusiness = $invAddObj->is_business;
        $this->name = $invAddObj->name;
        $this->street = $invAddObj->street;
        $this->zipCode = $invAddObj->zipcode;
        $this->city = $invAddObj->city;
        $this->country = $invAddObj->country;
        $this->state = $invAddObj->state;
        $this->internalReference = $invAddObj->internal_reference;
        $this->vatId = $invAddObj->vat_id;
    }
}
