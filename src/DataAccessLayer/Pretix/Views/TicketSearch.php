<?php

namespace App\DataAccessLayer\Pretix\Views;

class TicketSearch
{
    private int $count;

    /** @var OrderPosition[] $results */
    private array $results;

    public function __construct(object $item)
    {
        $this->count = $item->count;

        $this->results = [];
        foreach ($item->results as $result) {
            $this->results[] = new OrderPosition($result);
        }
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getResults(): array
    {
        return $this->results;
    }
}
