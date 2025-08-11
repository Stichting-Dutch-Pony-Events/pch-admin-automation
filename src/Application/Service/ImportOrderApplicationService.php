<?php

namespace App\Application\Service;

use App\DataAccessLayer\EBoekHouden\Repository\MutationRepository;
use App\DataAccessLayer\Pretix\PretixApiClient;
use App\DataAccessLayer\Pretix\Repositories\InvoicesRepository;
use App\DataAccessLayer\Pretix\Repositories\OrderRepository;
use App\Util\Exceptions\Exception\Entity\EntityNotFoundException;

readonly class ImportOrderApplicationService
{
    public function __construct(
        private PretixApiClient $apiClient,
        private OrderRepository $orderRepository,
        private InvoicesRepository $invoicesRepository,
        private MutationRepository $mutationRepository
    ) {
    }

    public function importOrder(string $orderCode, ?string $event = null): void
    {
        if ($event) {
            $this->apiClient->setEvent($event);
        }

        $order = $this->orderRepository->getOrderByCode($orderCode);
        $invoices = $this->invoicesRepository->getInvoices($order->code);

        // If there are multiple invoices, we cannot import the order. Treasury has to do this manually.
        if (count($invoices) !== 1) {
            return;
        }

        try {
            $eboekhoudingInvoice = $this->mutationRepository->getSentInvoiceByNumber($invoices[0]->number);

            if ($eboekhoudingInvoice) {
                return;
            }
        } catch (EntityNotFoundException $e) {
        }

        try {
            $eboekHoudenInvoicePayment = $this->mutationRepository->getInvoicePaymentReceivedByNumber(
                $invoices[0]->number
            );
            if ($eboekHoudenInvoicePayment) {
                return;
            }
        } catch (EntityNotFoundException $e) {
        }
    }
}
