<?php

namespace App\DataAccessLayer\Pretix\Views;

class CheckInList
{
    private int $id;
    private string $name;
    private bool $allProducts;
    private array $productIds;
    private int $positionCount;
    private int $checkinCount;
    private bool $includePending;
    private bool $allowMultipleEntries;
    private bool $allowEntryAfterExit;
    private bool $addonMatch;
    private bool $ignoreInStatistics;
    private bool $considerTicketsUsed;

    public function __construct(object $item)
    {
        $this->id                   = $item->id;
        $this->name                 = $item->name;
        $this->allProducts          = $item->all_products;
        $this->productIds           = $item->limit_products;
        $this->positionCount        = $item->position_count;
        $this->checkinCount         = $item->checkin_count;
        $this->includePending       = $item->include_pending;
        $this->allowMultipleEntries = $item->allow_multiple_entries;
        $this->allowEntryAfterExit  = $item->allow_entry_after_exit;
        $this->addonMatch           = $item->addon_match;
        $this->ignoreInStatistics   = $item->ignore_in_statistics;
        $this->considerTicketsUsed  = $item->consider_tickets_used;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isAllProducts(): bool
    {
        return $this->allProducts;
    }

    /**
     * @return array
     */
    public function getProductIds(): array
    {
        return $this->productIds;
    }

    /**
     * @return int
     */
    public function getPositionCount(): int
    {
        return $this->positionCount;
    }

    /**
     * @return int
     */
    public function getCheckinCount(): int
    {
        return $this->checkinCount;
    }

    /**
     * @return bool
     */
    public function isIncludePending(): bool
    {
        return $this->includePending;
    }

    /**
     * @return bool
     */
    public function isAllowMultipleEntries(): bool
    {
        return $this->allowMultipleEntries;
    }

    /**
     * @return bool
     */
    public function isAllowEntryAfterExit(): bool
    {
        return $this->allowEntryAfterExit;
    }

    /**
     * @return bool
     */
    public function isAddonMatch(): bool
    {
        return $this->addonMatch;
    }

    /**
     * @return bool
     */
    public function isIgnoreInStatistics(): bool
    {
        return $this->ignoreInStatistics;
    }

    /**
     * @return bool
     */
    public function isConsiderTicketsUsed(): bool
    {
        return $this->considerTicketsUsed;
    }
}
