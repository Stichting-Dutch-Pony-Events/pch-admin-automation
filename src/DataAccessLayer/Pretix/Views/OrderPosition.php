<?php

namespace App\DataAccessLayer\Pretix\Views;

use Illuminate\Support\Carbon;

class OrderPosition
{
    private int $id;
    private string $order;
    private int $positionId;
    private bool $cancelled;
    private int $itemId;
    private ?int $variationId;
    private string $price;
    private ?string $attendeeName;
    private ?array $attendeeNameParts;
    private ?string $attendeeEmail;
    private ?string $street;
    private ?string $zipcode;
    private ?string $city;
    private ?string $country;
    private ?int $voucherId;
    private string $taxRate;
    private string $taxValue;
    private ?int $taxRuleId;
    private string $secret;
    private ?string $addonTo;
    private ?int $discount;
    private ?array $blocked;
    private ?Carbon $validFrom;
    private ?Carbon $validUntil;

    /** @var OrderPositionCheckin[] */
    private array $checkins;

    /** @var OrderPositionAnswer[] */
    private array $answers;

    public function __construct(object $item)
    {
        $this->id                = $item->id;
        $this->order             = $item->order;
        $this->positionId        = $item->positionid;
        $this->cancelled         = $item->canceled ?? false;
        $this->itemId            = $item->item;
        $this->variationId       = $item->variation ?? null;
        $this->price             = $item->price;
        $this->attendeeName      = $item->attendee_name ?? null;
        $this->attendeeNameParts = empty($item->attendee_name_parts) ? null : (array) $item->attendee_name_parts;
        $this->attendeeEmail     = $item->attendee_email;
        $this->street            = $item->street ?? null;
        $this->zipcode           = $item->zipcode ?? null;
        $this->city              = $item->city ?? null;
        $this->country           = $item->country ?? null;
        $this->voucherId         = $item->voucher ?? null;
        $this->taxRate           = $item->tax_rate;
        $this->taxValue          = $item->tax_value;
        $this->taxRuleId         = $item->tax_tale ?? null;
        $this->secret            = $item->secret;
        $this->addonTo           = $item->addon_to ?? null;
        $this->discount          = $item->discount ?? null;
        $this->blocked           = $item->blocked ?? null;
        $this->validFrom         = isset($item->valid_from) ? Carbon::parse($item->valid_from) : null;
        $this->validUntil        = isset($item->valid_until) ? Carbon::parse($item->valid_until) : null;

        $this->checkins = [];
        if (isset($item->checkins) && is_array($item->checkins)) {
            foreach ($item->checkins as $checkin) {
                $this->checkins[] = new OrderPositionCheckin($checkin);
            }
        }

        $this->answers = [];
        if (isset($item->answers) && is_array($item->answers)) {
            foreach ($item->answers as $answer) {
                $this->answers[] = new OrderPositionAnswer($answer);
            }
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * @return int
     */
    public function getPositionId(): int
    {
        return $this->positionId;
    }

    /**
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->cancelled;
    }

    /**
     * @return int
     */
    public function getItemId(): int
    {
        return $this->itemId;
    }

    /**
     * @return int|null
     */
    public function getVariationId(): ?int
    {
        return $this->variationId;
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @return string|null
     */
    public function getAttendeeName(): ?string
    {
        return $this->attendeeName;
    }

    /**
     * @return array|null
     */
    public function getAttendeeNameParts(): ?array
    {
        return $this->attendeeNameParts;
    }

    public function getAttendeeNamePart(string $part): ?string
    {
        foreach ($this->attendeeNameParts as $key => $value) {
            if (str_contains(strtolower($key), strtolower($part))) {
                return $value;
            }
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getAttendeeEmail(): ?string
    {
        return $this->attendeeEmail;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @return string|null
     */
    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @return int|null
     */
    public function getVoucherId(): ?int
    {
        return $this->voucherId;
    }

    /**
     * @return string
     */
    public function getTaxRate(): string
    {
        return $this->taxRate;
    }

    /**
     * @return string
     */
    public function getTaxValue(): string
    {
        return $this->taxValue;
    }

    /**
     * @return int|null
     */
    public function getTaxRuleId(): ?int
    {
        return $this->taxRuleId;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @return string|null
     */
    public function getAddonTo(): ?string
    {
        return $this->addonTo;
    }

    /**
     * @return int|null
     */
    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    /**
     * @return array|null
     */
    public function getBlocked(): ?array
    {
        return $this->blocked;
    }

    /**
     * @return Carbon|null
     */
    public function getValidFrom(): ?Carbon
    {
        return $this->validFrom;
    }

    /**
     * @return Carbon|null
     */
    public function getValidUntil(): ?Carbon
    {
        return $this->validUntil;
    }

    /**
     * @return array
     */
    public function getCheckins(): array
    {
        return $this->checkins;
    }

    /**
     * @return array
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function getAnswer(string $identifier): ?string
    {
        foreach ($this->answers as $answer) {
            if (strtolower($answer->getQuestionIdentifier()) === strtolower($identifier)) {
                return $answer->getAnswer();
            }
        }

        return null;
    }
}
