<?php

namespace App\DataAccessLayer\Pretix\Views;

use Illuminate\Support\Carbon;

class Invoice
{
    public string $number;
    public ?string $order;
    public ?bool $isCancellation;
    public ?string $invoiceFromName;
    public ?string $invoiceFromAddress;
    public ?string $invoiceFromZip;
    public ?string $invoiceFromCity;
    public ?string $invoiceFromCountry;
    public ?string $invoiceFromVatId;

    public ?string $invoiceToAddress;
    public ?string $invoiceToCompany;
    public ?string $invoiceToName;
    public ?string $invoiceToStreet;
    public ?string $invoiceToZip;
    public ?string $invoiceToCity;
    public ?string $invoiceToState;
    public ?string $invoiceToCountry;
    public ?string $invoiceToVatId;
    public ?string $invoiceToBeneficiary;

    public ?string $customField;
    public ?Carbon $date;
    public ?string $refers;
    public ?string $locale;
    public ?string $introductoryText;
    public ?string $additionalText;
    public ?string $paymentProviderText;
    public ?string $footerText;

    /** @var InvoiceLine[] $lines */
    public ?array $lines;

    public ?string $foreignCurrencyDisplay;
    public ?string $foreignCurrencyRate;
    public ?Carbon $foreignCurrencyRateDate;
    public ?string $internalReference;

    public function __construct($item)
    {
        $this->number = $item->number;
        $this->order = $item->order;
        $this->isCancellation = $item->is_cancellation;
        $this->invoiceFromName = $item->invoice_from_name;
        $this->invoiceFromAddress = $item->invoice_from;
        $this->invoiceFromZip = $item->invoice_from_zipcode;
        $this->invoiceFromCity = $item->invoice_from_city;
        $this->invoiceFromCountry = $item->invoice_from_country;
        $this->invoiceFromVatId = $item->invoice_from_vat_id;

        $this->invoiceToAddress = $item->invoice_to;
        $this->invoiceToCompany = $item->invoice_to_company;
        $this->invoiceToName = $item->invoice_to_name;
        $this->invoiceToStreet = $item->invoice_to_street;
        $this->invoiceToZip = $item->invoice_to_zipcode;
        $this->invoiceToCity = $item->invoice_to_city;
        $this->invoiceToState = $item->invoice_to_state;
        $this->invoiceToCountry = $item->invoice_to_country;
        $this->invoiceToVatId = $item->invoice_to_vat_id;
        $this->invoiceToBeneficiary = $item->invoice_to_beneficiary;

        $this->customField = $item->custom_field;

        try {
            $this->date = Carbon::parse($item->date);
        } catch (\Exception) {
            $this->date = null;
        }

        $this->refers = $item->refers;
        $this->locale = $item->locale;
        $this->introductoryText = $item->introductory_text;
        $this->additionalText = $item->additional_text;
        $this->paymentProviderText = $item->payment_provider_text;
        $this->footerText = $item->footer_text;

        if(is_array($item->lines)) {
            $this->lines = [];
            foreach ($item->lines as $line) {
                $this->lines[] = new InvoiceLine($line);
            }
        } else {
            $this->lines = null;
        }

        $this->foreignCurrencyDisplay = $item->foreign_currency_display;
        $this->foreignCurrencyRate = $item->foreign_currency_rate;
        try {
            $this->foreignCurrencyRateDate = Carbon::parse($item->foreign_currency_rate_date);
        } catch (\Exception) {
            $this->foreignCurrencyRateDate = null;
        }

        $this->internalReference = $item->internal_reference;
    }
}
