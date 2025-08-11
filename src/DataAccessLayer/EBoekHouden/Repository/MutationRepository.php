<?php

namespace App\DataAccessLayer\EBoekHouden\Repository;

use App\DataAccessLayer\EBoekHouden\Enum\MutationTypeEnum;
use App\DataAccessLayer\EBoekHouden\FilterParams\Enum\NumberFilterType;
use App\DataAccessLayer\EBoekHouden\FilterParams\Enum\StringFilterType;
use App\DataAccessLayer\EBoekHouden\FilterParams\NumberFilter;
use App\DataAccessLayer\EBoekHouden\FilterParams\StringFilter;
use App\DataAccessLayer\EBoekHouden\Responses\MutationListResponse;
use App\DataAccessLayer\EBoekHouden\Views\Mutation;
use App\Util\Exceptions\Exception\Common\InvalidApiResponseException;
use App\Util\Exceptions\Exception\Entity\EntityNotFoundException;

class MutationRepository extends BaseRepository
{
    public function getSentInvoiceByNumber(string $invoiceNumber)
    {
        $response = $this->retrieveMany('v1/mutation', MutationListResponse::class, [
            new StringFilter(
                field: 'invoiceNumber',
                value: $invoiceNumber,
                type:  StringFilterType::EQUAL
            ),
            new NumberFilter(
                field: 'type',
                value: MutationTypeEnum::INVOICE_SENT->value,
                type:  NumberFilterType::EQUAL
            )
        ]);

        if (!$response instanceof MutationListResponse) {
            throw new InvalidApiResponseException('Expected MutationListResponse.');
        }

        if (count($response->items) === 0) {
            throw new EntityNotFoundException('No invoice found with the given number: ' . $invoiceNumber);
        }

        return $this->getMutationById($response->items[0]->id);
    }

    public function getInvoicePaymentReceivedByNumber(string $invoiceNumber)
    {
        $response = $this->retrieveMany('v1/mutation', MutationListResponse::class, [
            new StringFilter(
                field: 'invoiceNumber',
                value: $invoiceNumber,
                type:  StringFilterType::EQUAL
            ),
            new NumberFilter(
                field: 'type',
                value: MutationTypeEnum::INVOICE_PAYMENT_RECEIVED->value,
                type:  NumberFilterType::EQUAL
            )
        ]);

        if (!$response instanceof MutationListResponse) {
            throw new InvalidApiResponseException('Expected MutationListResponse.');
        }

        if (count($response->items) === 0) {
            throw new EntityNotFoundException('No payment received for invoice with number: ' . $invoiceNumber);
        }

        return $this->getMutationById($response->items[0]->id);
    }

    public function getMutationById(int $id): Mutation
    {
        $mutation = $this->retrieveOne('v1/mutation/' . $id, Mutation::class);

        if (!$mutation instanceof Mutation) {
            throw new InvalidApiResponseException('Expected Mutation.');
        }

        return $mutation;
    }
}
