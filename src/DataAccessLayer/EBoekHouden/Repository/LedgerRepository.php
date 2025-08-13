<?php

namespace App\DataAccessLayer\EBoekHouden\Repository;

use App\DataAccessLayer\EBoekHouden\EBoekHoudenApi;
use App\DataAccessLayer\EBoekHouden\FilterParams\Enum\StringFilterType;
use App\DataAccessLayer\EBoekHouden\FilterParams\StringFilter;
use App\DataAccessLayer\EBoekHouden\Responses\LedgerListResponse;
use App\DataAccessLayer\EBoekHouden\Views\Ledger;
use App\Util\Exceptions\Exception\Common\InvalidApiResponseException;
use App\Util\Exceptions\Exception\Entity\EntityNotFoundException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class LedgerRepository extends BaseRepository
{
    public function __construct(
        SerializerInterface $serializer,
        EBoekHoudenApi $boekHoudenApi,
        CacheInterface $cache,
        private readonly string $stripeLedger,
        private readonly string $paypalLedger,
        private readonly string $stripeCostsLedger,
        private readonly string $paypalCostsLedger,
        private readonly string $debtorsLedger,
        private readonly string $defaultLedger
    ) {
        parent::__construct($serializer, $boekHoudenApi, $cache);
    }

    /**
     * @throws EntityNotFoundException
     * @throws InvalidApiResponseException
     */
    public function getLedgerByCode(string $code): ?Ledger
    {
        return $this->cache->get('eboekhounden-ledger-'.$code, function (ItemInterface $item) use ($code): Ledger {
            $item->expiresAfter(86400);

            $ledgerResponse = $this->retrieveMany(
                'v1/ledger',
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
        });
    }

    public function getStripeLedger(): Ledger
    {
        return $this->getLedgerByCode($this->stripeLedger);
    }

    public function getPaypalLedger(): Ledger
    {
        return $this->getLedgerByCode($this->paypalLedger);
    }

    public function getStripeCostsLedger(): Ledger
    {
        return $this->getLedgerByCode($this->stripeCostsLedger);
    }

    public function getPaypalCostsLedger(): Ledger
    {
        return $this->getLedgerByCode($this->paypalCostsLedger);
    }

    public function getDebtorLedger(): Ledger
    {
        return $this->getLedgerByCode($this->debtorsLedger);
    }

    public function getDefaultLedger(): Ledger
    {
        return $this->getLedgerByCode($this->defaultLedger);
    }
}
