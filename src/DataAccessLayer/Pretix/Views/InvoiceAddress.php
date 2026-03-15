<?php

namespace App\DataAccessLayer\Pretix\Views;

use Carbon\Carbon;
use Transliterator;

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

    public function getRelationCode(): string
    {
        $transliterator = Transliterator::createFromRules(
            ':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;',
            Transliterator::FORWARD
        );

        $transliterate = static function (string $value) use ($transliterator): string {
            return $transliterator?->transliterate($value) ?? $value;
        };

        $relCode = substr(strtoupper(('PT' . $transliterate($this->country) . $transliterate($this->zipCode))), 0, 8);

        if ($this->company !== null && $this->company !== '') {
            $relCode .= '_' . substr($transliterate($this->company), 0, 6);
        } else {
            $nameParts = explode(' ', $transliterate($this->name));
            if (count($nameParts) > 1) {
                $relCode .= '_';
                $lastName = $nameParts[count($nameParts) - 1];
                $firstName = $nameParts[0];
                $relCode .= substr($lastName, 0, 3);
                $relCode .= substr($firstName, 0, 3);
            } else {
                $relCode .= '_' . substr($transliterate($this->name), 0, 6);
            }
        }

        return strtoupper(substr($relCode, 0, 15));
    }
}
