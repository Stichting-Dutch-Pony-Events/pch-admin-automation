<?php

namespace App\DataAccessLayer\Pretix\Repositories;

use App\DataAccessLayer\Pretix\PretixApiClient;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class PretixBaseRepository
{
    public function __construct(
        protected readonly PretixApiClient     $pretixApiClient,
        protected readonly SerializerInterface $serializer,
        protected readonly CacheInterface      $cache
    ) {
    }
}
