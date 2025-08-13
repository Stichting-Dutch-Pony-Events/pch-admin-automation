<?php

namespace App\DataAccessLayer\EBoekHouden\Repository;

use App\DataAccessLayer\EBoekHouden\Enum\MutationTypeEnum;
use App\DataAccessLayer\EBoekHouden\FilterParams\Enum\NumberFilterType;
use App\DataAccessLayer\EBoekHouden\FilterParams\Enum\StringFilterType;
use App\DataAccessLayer\EBoekHouden\FilterParams\NumberFilter;
use App\DataAccessLayer\EBoekHouden\FilterParams\StringFilter;
use App\DataAccessLayer\EBoekHouden\Request\MutationRequest;
use App\DataAccessLayer\EBoekHouden\Responses\MutationCreatedResponse;
use App\DataAccessLayer\EBoekHouden\Responses\MutationListResponse;
use App\DataAccessLayer\EBoekHouden\Views\Mutation;
use App\Util\Exceptions\Exception\Common\InvalidApiResponseException;

class MutationRepository extends BaseRepository
{
    public function getSentInvoiceByNumber(string $invoiceNumber): ?Mutation
    {
        $response = $this->retrieveMany('v1/mutation', MutationListResponse::class, [
            new StringFilter(
                field: 'invoiceNumber',
                value: $invoiceNumber,
                type: StringFilterType::EQUAL
            ),
            new NumberFilter(
                field: 'type',
                value: MutationTypeEnum::INVOICE_SENT->value,
                type: NumberFilterType::EQUAL
            )
        ]);

        if (!$response instanceof MutationListResponse) {
            throw new InvalidApiResponseException('Expected MutationListResponse.');
        }

        if (count($response->items) === 0) {
            return null;
        }

        return $this->getMutationById($response->items[0]->id);
    }

    public function getInvoicePaymentReceivedByNumber(string $invoiceNumber): ?Mutation
    {
        $response = $this->retrieveMany('v1/mutation', MutationListResponse::class, [
            new NumberFilter(
                field: 'type',
                value: MutationTypeEnum::INVOICE_PAYMENT_RECEIVED->value,
                type: NumberFilterType::EQUAL
            ),
            new StringFilter(
                field: 'invoiceNumber',
                value: $invoiceNumber,
                type: StringFilterType::EQUAL
            ),
        ]);

        if (!$response instanceof MutationListResponse) {
            throw new InvalidApiResponseException('Expected MutationListResponse.');
        }

        if (count($response->items) === 0) {
            return null;
        }

        return $this->getMutationById($response->items[0]->id);
    }

    public function getByPaymentId(string $paymentID): ?Mutation
    {
        $response = $this->retrieveMany('v1/mutation', MutationListResponse::class, [
            new StringFilter(
                field: 'description',
                value: '%25'.$paymentID.'%25',
                type: StringFilterType::LIKE
            ),
            new NumberFilter(
                field: 'type',
                value: MutationTypeEnum::MONEY_SENT->value,
                type: NumberFilterType::EQUAL
            )
        ]);

        if (!$response instanceof MutationListResponse) {
            throw new InvalidApiResponseException('Expected MutationListResponse.');
        }

        if (count($response->items) === 0) {
            return null;
        }

        return $this->getMutationById($response->items[0]->id);
    }

    public function createMutation(MutationRequest $mutationRequest): Mutation
    {
        $mutationResponse = $this->create('v1/mutation', $mutationRequest, MutationCreatedResponse::class);

        if (!$mutationResponse instanceof MutationCreatedResponse) {
            throw new InvalidApiResponseException('Expected MutationCreatedResponse.');
        }

        return $this->getMutationById($mutationResponse->id);
    }

    public function getMutationById(int $id): Mutation
    {
        $mutation = $this->retrieveOne('v1/mutation/'.$id, Mutation::class);

        if (!$mutation instanceof Mutation) {
            throw new InvalidApiResponseException('Expected Mutation.');
        }

        return $mutation;
    }
}
