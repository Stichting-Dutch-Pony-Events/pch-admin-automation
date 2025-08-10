<?php

namespace App\DataAccessLayer\Pretix\Views;

use App\DataAccessLayer\Pretix\Enum\CheckInErrorReason;
use App\DataAccessLayer\Pretix\Enum\CheckInStatus;

class CheckIn
{
    private CheckInStatus $status;
    private ?CheckInErrorReason $errorReason;
    private ?string $reasonExplanation;
    private ?OrderPosition $orderPosition;
    private bool $requiresAttention;

    public function __construct(object $item)
    {
        $this->status = CheckInStatus::from($item->status);
        $this->errorReason = isset($item->reason) ? CheckInErrorReason::from($item->reason) : null;
        $this->reasonExplanation = $item->reason_explanation ?? null;
        $this->orderPosition = isset($item->position) ? new OrderPosition($item->position) : null;
        $this->requiresAttention = $item->require_attention;
    }

    public function getStatus(): CheckInStatus
    {
        return $this->status;
    }

    public function getErrorReason(): ?CheckInErrorReason
    {
        return $this->errorReason;
    }

    public function getReasonExplanation(): ?string
    {
        return $this->reasonExplanation;
    }

    public function getOrderPosition(): ?OrderPosition
    {
        return $this->orderPosition;
    }

    public function isRequiresAttention(): bool
    {
        return $this->requiresAttention;
    }
}
