<?php

namespace App\DataAccessLayer\Pretix\Views;

use Carbon\Carbon;

class OrderPositionCheckin
{
    private int $id;
    private int $checkinListId;
    private ?Carbon $checkinTime;
    private string $type;
    private ?int $gateId;
    private ?int $deviceId;
    private bool $autoCheckedIn;

    public function __construct(object $item)
    {
        $this->id            = $item->id;
        $this->checkinListId = $item->list;
        $this->checkinTime   = Carbon::parse($item->datetime);
        $this->type          = $item->type;
        $this->gateId        = $item->gate ?? null;
        $this->deviceId      = $item->device ?? null;
        $this->autoCheckedIn = $item->auto_checked_in;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCheckinListId(): int
    {
        return $this->checkinListId;
    }

    /**
     * @return Carbon|null
     */
    public function getCheckinTime(): ?Carbon
    {
        return $this->checkinTime;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int|null
     */
    public function getGateId(): ?int
    {
        return $this->gateId;
    }

    /**
     * @return int|null
     */
    public function getDeviceId(): ?int
    {
        return $this->deviceId;
    }

    /**
     * @return bool
     */
    public function isAutoCheckedIn(): bool
    {
        return $this->autoCheckedIn;
    }
}
