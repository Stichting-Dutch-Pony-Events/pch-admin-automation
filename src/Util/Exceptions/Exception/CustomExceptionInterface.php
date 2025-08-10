<?php

namespace App\Util\Exceptions\Exception;

interface CustomExceptionInterface
{
    public function getHttpStatusCode(): int;

    public function getData(): mixed;

    public function isPublicException(): bool;
}
