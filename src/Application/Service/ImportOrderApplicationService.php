<?php

namespace App\Application\Service;

use App\DataAccessLayer\EBoekHouden\Enum\InExVatEnum;
use App\DataAccessLayer\EBoekHouden\Enum\MutationTypeEnum;
use App\DataAccessLayer\EBoekHouden\Enum\RelationTypeEnum;
use App\DataAccessLayer\EBoekHouden\Enum\VatCodeEnum;
use App\DataAccessLayer\EBoekHouden\Repository\LedgerRepository;
use App\DataAccessLayer\EBoekHouden\Repository\MutationRepository;
use App\DataAccessLayer\EBoekHouden\Repository\RelationRepository;
use App\DataAccessLayer\EBoekHouden\Request\MutationRequest;
use App\DataAccessLayer\EBoekHouden\Request\MutationRowRequest;
use App\DataAccessLayer\EBoekHouden\Request\RelationRequest;
use App\DataAccessLayer\EBoekHouden\Views\Relation;
use App\DataAccessLayer\Pretix\PretixApiClient;
use App\DataAccessLayer\Pretix\Repositories\EventRepository;
use App\DataAccessLayer\Pretix\Repositories\InvoicesRepository;
use App\DataAccessLayer\Pretix\Repositories\ItemRepository;
use App\DataAccessLayer\Pretix\Repositories\OrderRepository;
use App\DataAccessLayer\Pretix\Views\Event;
use App\DataAccessLayer\Pretix\Views\Invoice;
use App\DataAccessLayer\Pretix\Views\InvoiceLine;
use App\DataAccessLayer\Pretix\Views\Order;
use App\DataAccessLayer\Pretix\Views\OrderPayment;
use App\Util\Exceptions\Exception\Common\InvalidInputException;
use App\Util\Exceptions\Exception\Entity\EntityNotFoundException;

readonly class ImportOrderApplicationService
{
    public function __construct(
        private PretixApiClient                $apiClient,
        private OrderRepository                $orderRepository,
        private InvoicesRepository             $invoicesRepository,
        private EventRepository                $eventRepository,
        private MutationRepository             $mutationRepository,
        private RelationRepository             $relationRepository,
        private LedgerRepository               $ledgerRepository,
        private ItemRepository                 $itemRepository,
        private PaymentCostsApplicationService $paymentCostsApplicationService
    ) {
    }

    public function importOrder(string $orderCode, ?string $eventCode = 'PCH25'): void
    {
        if ($eventCode) {
            $this->apiClient->setEvent($eventCode);
        }

        $event = $this->eventRepository->getEventByCode($eventCode);
        $order = $this->orderRepository->getOrderByCode($orderCode);
        $invoices = $this->invoicesRepository->getInvoices($order->code);

        // If there are multiple invoices, we cannot import the order. Treasury has to do this manually.
        if (count($invoices) !== 1) {
            return;
        }

        $relation = $this->retrieveOrCreateRelation($order);

        $eboekhoudingInvoice = $this->mutationRepository->getSentInvoiceByNumber($invoices[0]->number);
        if ($eboekhoudingInvoice === null) {
            $this->createInvoiceMutation($order, $invoices[0], $event, $relation);
        }

        $confirmedPayments = array_filter($order->payments, fn($payment) => $payment->state === 'confirmed');
        if (count($confirmedPayments) !== 1) {
            return;
        }

        $eboekHoudenInvoicePayment = $this->mutationRepository->getInvoicePaymentReceivedByNumber(
            $invoices[0]->number
        );
        if ($eboekHoudenInvoicePayment === null) {
            $this->createInvoicePayment($order, $confirmedPayments[0], $invoices[0], $event, $relation);
        }

        $this->paymentCostsApplicationService->createPaymentCostMutation($order, $confirmedPayments[0]);
    }

    public function createInvoiceMutation(Order $order, Invoice $invoice, Event $event, Relation $relation): void
    {
        $debtorsLedger = $this->ledgerRepository->getDebtorLedger();

        $mutationRequest = new MutationRequest(
            type:          MutationTypeEnum::INVOICE_SENT,
            date:          $invoice->date?->format('Y-m-d') ?? date('Y-m-d'),
            ledgerId:      $debtorsLedger->id,
            inExVat:       InExVatEnum::INCLUDING,
            invoiceNumber: $invoice->number,
            entryNumber:   $order->code,
            termOfPayment: 14,
            description:   $event->getName() . ' - Order: ' . $order->code,
            relationId:    $relation->id
        );

        foreach ($invoice->lines as $line) {
            $mutationRow = $this->createMutationRow($line);
            if ($mutationRow !== null) {
                $mutationRequest->rows[] = $mutationRow;
            }
        }

        $this->mutationRepository->createMutation($mutationRequest);
    }

    public function createMutationRow(InvoiceLine $invoiceLine): ?MutationRowRequest
    {
        if ($invoiceLine->item) {
            $item = $this->itemRepository->getItemById($invoiceLine->item);

            $grootboek = property_exists($item->metaData, 'grootboek') ? $item->metaData->grootboek : null;
            $ledger = $grootboek
                ? $this->ledgerRepository->getLedgerByCode($grootboek)
                : $this->ledgerRepository->getDefaultLedger();
            if (!$ledger) {
                throw new EntityNotFoundException('Ledger not found for item: ' . $item->id);
            }

            $vatCode = VatCodeEnum::getSalesVatFromPercentage((int)$invoiceLine->taxRate);

            return new MutationRowRequest(
                ledgerId:    $ledger->id,
                vatCode:     $vatCode,
                amount:      $invoiceLine->grossValue,
                vatAmount:   $vatCode === VatCodeEnum::AFW ? ($invoiceLine->taxValue ?? 0.0) : null,
                description: str_replace('<br />', ' - ', $invoiceLine->description),
            );
        }

        if ($invoiceLine->feeType === 'payment') {
            $vatCode = VatCodeEnum::getSalesVatFromPercentage((int)$invoiceLine->taxRate);
            $ledger = $this->ledgerRepository->getPaypalCostsLedger();

            return new MutationRowRequest(
                ledgerId:    $ledger->id,
                vatCode:     $vatCode,
                amount:      $invoiceLine->grossValue,
                vatAmount:   $vatCode === VatCodeEnum::AFW ? ($invoiceLine->taxValue ?? 0.0) : null,
                description: $invoiceLine->description,
            );
        }

        return null; // Unsupported invoice line type
    }

    public function createInvoicePayment(
        Order        $order,
        OrderPayment $payment,
        Invoice      $invoice,
        Event        $event,
        Relation     $relation
    ): void {
        $ledger = $this->ledgerRepository->getDefaultLedger();
        if (str_contains($payment->provider, 'stripe')) {
            $ledger = $this->ledgerRepository->getStripeLedger();
        } elseif (str_contains($payment->provider, 'paypal')) {
            $ledger = $this->ledgerRepository->getPaypalLedger();
        } else {
            return; // Unsupported payment provider
        }

        $debtorLedger = $this->ledgerRepository->getDebtorLedger();

        $mutationRequest = new MutationRequest(
            type:                  MutationTypeEnum::INVOICE_PAYMENT_RECEIVED,
            date:                  $payment->paymentDate?->format('Y-m-d') ?? date('Y-m-d'),
            ledgerId:              $ledger->id,
            inExVat:               InExVatEnum::INCLUDING,
            invoiceNumber:         $invoice->number,
            entryNumber:           $order->code,
            description:           $event->getName(
                                   ) . ' - Payment for order: ' . $order->code . ' - ' . $payment->getPaymentId(),
            checkPaymentReference: true,
            paymentReference:      $payment->getPaymentId(),
            relationId:            $relation->id,
            rows:                  [
                                       new MutationRowRequest(
                                           ledgerId:      $debtorLedger->id,
                                           vatCode:       VatCodeEnum::GEEN,
                                           amount:        $payment->amount,
                                           description:   'Payment for Invoice ' . $invoice->number . ' - Order: ' . $order->code,
                                           invoiceNumber: $invoice->number,
                                           relationId:    $relation->id,
                                       )
                                   ]
        );

        $this->mutationRepository->createMutation($mutationRequest);
    }

    public function retrieveOrCreateRelation(Order $order): Relation
    {
        $relation =
            $this->relationRepository->retrieveByEmail($order->email)
            ?? $this->relationRepository->retrieveByCode($order->invoiceAddress?->getRelationCode());

        $invoiceAddress = $order->invoiceAddress;
        if (!$invoiceAddress) {
            throw new InvalidInputException('Invoice address is required to create a relation.');
        }

        $relationRequest = new RelationRequest(
            type:         $invoiceAddress->isBusiness ? RelationTypeEnum::BUSINESS : RelationTypeEnum::PRIVATE,
            code:         $invoiceAddress->getRelationCode(),
            name:         $invoiceAddress->name,
            address:      $invoiceAddress->street,
            postalCode:   $invoiceAddress->zipCode,
            city:         $invoiceAddress->city,
            country:      $invoiceAddress->country,
            emailAddress: $order->email,
        );

        if ($relation) {
            $relation = $this->relationRepository->patchRelation($relation, $relationRequest);
        } else {
            $relation = $this->relationRepository->createRelation($relationRequest);
        }

        return $relation;
    }
}
