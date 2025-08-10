<?php

namespace App\Util\Exceptions\Exception\Entity;

use App\Util\Exceptions\Exception\PublicException;
use Symfony\Component\HttpFoundation\Response;

class EntityNotFoundException extends PublicException
{
    public function getHttpStatusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }
}
