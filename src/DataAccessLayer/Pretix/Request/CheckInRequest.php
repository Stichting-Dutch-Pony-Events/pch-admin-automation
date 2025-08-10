<?php

namespace App\DataAccessLayer\Pretix\Request;

use App\DataAccessLayer\Pretix\Enum\CheckInType;
use Carbon\Carbon;
use DateTime;

class CheckInRequest
{
    public string $source_type = 'barcode';
    public CheckInType $type = CheckInType::ENTRY;
    public bool $force = false;
    public bool $ignore_unpaid = false;
    public string $nonce;


    public function __construct(
        public string $secret,
        public array  $lists,
    ) {
        $this->nonce    = uniqid('', true);
    }
}
