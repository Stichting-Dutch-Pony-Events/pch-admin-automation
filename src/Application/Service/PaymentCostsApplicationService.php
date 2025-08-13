<?php

namespace App\Application\Service;

use App\DataAccessLayer\EBoekHouden\Enum\InExVatEnum;
use App\DataAccessLayer\EBoekHouden\Enum\MutationTypeEnum;
use App\DataAccessLayer\EBoekHouden\Enum\VatCodeEnum;
use App\DataAccessLayer\EBoekHouden\Repository\LedgerRepository;
use App\DataAccessLayer\EBoekHouden\Repository\MutationRepository;
use App\DataAccessLayer\EBoekHouden\Request\MutationRequest;
use App\DataAccessLayer\EBoekHouden\Request\MutationRowRequest;
use App\DataAccessLayer\PayPal\Repository\PaymentRepository;
use App\DataAccessLayer\Pretix\Views\Order;
use App\DataAccessLayer\Pretix\Views\OrderPayment;
use App\DataAccessLayer\StripeWrapper;
use Stripe\BalanceTransaction;
use Stripe\Charge;
use Stripe\Exception\ApiErrorException;

readonly class PaymentCostsApplicationService
{
    public function __construct(
        private LedgerRepository   $ledgerRepository,
        private StripeWrapper      $stripeWrapper,
        private PaymentRepository  $paymentRepository,
        private MutationRepository $mutationRepository
    ) {
    }

    public function createPaymentCostMutation(Order $order, OrderPayment $orderPayment): void
    {
        if (str_contains($orderPayment->provider, 'stripe')) {
            $this->createStripeCostsMutation($order, $orderPayment);
        }
        if (str_contains($orderPayment->provider, 'paypal')) {
            $this->createPayPalCostsMutation($order, $orderPayment);
        }
    }

    private function createStripeCostsMutation(Order $order, OrderPayment $orderPayment): void
    {
        $ledger      = $this->ledgerRepository->getStripeLedger();
        $costsLedger = $this->ledgerRepository->getStripeCostsLedger();

        $paymentId = $orderPayment->getPaymentId();
        if ($paymentId === null) {
            return;
        }

        $mutation = $this->mutationRepository->getByPaymentId($paymentId);
        if ($mutation !== null) {
            // If a mutation already exists for this payment, we do not create a new one.
            return;
        }

        try {
            $paymentIntent = $this->stripeWrapper->getStripeClient()->paymentIntents->retrieve(
                $paymentId,
                [
                    'expand' => ['latest_charge.balance_transaction'],
                ]
            );
        } catch (ApiErrorException $e) {
            return;
        }

        if ($paymentIntent->status !== 'succeeded') {
            // If the payment intent is not succeeded, we do not create a mutation.
            return;
        }

        $latestCharge = $paymentIntent->latest_charge;
        if (!$latestCharge instanceof Charge) {
            return;
        }

        $balanceTransaction = $latestCharge->balance_transaction;
        if (!$balanceTransaction instanceof BalanceTransaction) {
            return;
        }

        $mutationRequest = new MutationRequest(
            type: MutationTypeEnum::MONEY_SENT,
            date: $orderPayment->paymentDate?->format('Y-m-d') ?? date('Y-m-d'),
            ledgerId: $ledger->id,
            inExVat: InExVatEnum::EXCLUDING,
            entryNumber: $order->code,
            description: 'Stripe Payment Fee for order: '.$order->code.' - '.$paymentId,
            rows: [
                new MutationRowRequest(
                    ledgerId: $costsLedger->id,
                    vatCode: VatCodeEnum::GEEN,
                    amount: floatval($balanceTransaction->fee ?? 0.0) / 100,
                    description: 'Stripe costs for order '.$order->code.' - '.$paymentId,
                )
            ]
        );

        $this->mutationRepository->createMutation($mutationRequest);
    }

    private function createPayPalCostsMutation(Order $order, OrderPayment $orderPayment): void
    {
        $ledger      = $this->ledgerRepository->getPayPalLedger();
        $costsLedger = $this->ledgerRepository->getPayPalCostsLedger();

        $paymentId = $orderPayment->getPaymentId();
        if ($paymentId === null) {
            return;
        }

        $mutation = $this->mutationRepository->getByPaymentId($paymentId);
        if ($mutation !== null) {
            // If a mutation already exists for this payment, we do not create a new one.
            return;
        }

        $capturedPayment = $this->paymentRepository->getPaymentByID($paymentId);

        $mutationRequest = new MutationRequest(
            type: MutationTypeEnum::MONEY_SENT,
            date: $orderPayment->paymentDate?->format('Y-m-d') ?? date('Y-m-d'),
            ledgerId: $ledger->id,
            inExVat: InExVatEnum::EXCLUDING,
            entryNumber: $order->code,
            description: 'PayPal Payment Fee for order: '.$order->code.' - '.$paymentId,
            rows: [
                new MutationRowRequest(
                    ledgerId: $costsLedger->id,
                    vatCode: VatCodeEnum::GEEN,
                    amount: $capturedPayment->sellerReceivableBreakdown->paypalFee->value ?? 0.0,
                    description: 'PayPal Costs for order '.$order->code.' - '.$paymentId,
                )
            ]
        );

        $this->mutationRepository->createMutation($mutationRequest);
    }
}
