<?php

namespace App\DataAccessLayer\Pretix\Repositories;

use App\DataAccessLayer\Pretix\PretixApiClient;

class PretixBaseRepository
{
    public function __construct(
        protected readonly PretixApiClient $pretixApiClient
    ) {
    }
}
