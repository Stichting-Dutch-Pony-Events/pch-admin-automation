<?php

namespace App\Application\Service;

use App\DataAccessLayer\Pretix\PretixApiClient;
use App\DataAccessLayer\Pretix\Repositories\OrderRepository;

readonly class ImportOrderApplicationService
{
    public function __construct(
        private PretixApiClient $apiClient,
        private OrderRepository $orderRepository
    ) {
    }

    public function importOrder(string $orderCode, ?string $event = null): void
    {
        if ($event) {
            $this->apiClient->setEvent($event);
        }

        $this->orderRepository->getOrderByCode($orderCode);
    }
}
