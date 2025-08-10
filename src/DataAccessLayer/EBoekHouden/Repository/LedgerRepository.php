<?php

namespace App\DataAccessLayer\EBoekHouden\Repository;

use App\DataAccessLayer\EBoekHouden\FilterParams\Enum\StringFilterType;
use App\DataAccessLayer\EBoekHouden\FilterParams\StringFilter;
use App\DataAccessLayer\EBoekHouden\Responses\LedgerListResponse;
use App\DataAccessLayer\EBoekHouden\Views\Ledger;
use App\Util\Exceptions\Exception\Common\InvalidApiResponseException;
use App\Util\Exceptions\Exception\Entity\EntityNotFoundException;

class LedgerRepository extends BaseRepository
{
    /**
     * @throws EntityNotFoundException
     * @throws InvalidApiResponseException
     */
    public function getLedgerByCode(string $code): ?Ledger
    {
        $ledgerResponse = $this->retrieveMany(
            'v1/ledgers',
            LedgerListResponse::class,
            [
                new StringFilter(
                    field: 'code',
                    value: $code,
                    type: StringFilterType::EQUAL
                )
            ]
        );

        if (!$ledgerResponse instanceof LedgerListResponse) {
            throw new InvalidApiResponseException('Invalid response type for ledger retrieval');
        }

        if ($ledgerResponse->count === 0) {
            throw new EntityNotFoundException('Ledger not found with code: '.$code);
        }

        return $ledgerResponse->items[0];
    }
}
