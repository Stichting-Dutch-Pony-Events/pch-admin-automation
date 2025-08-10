<?php

namespace App\Util\Exceptions\Exception\Common;

use App\Util\Exceptions\Exception\LogCustomExceptionInterface;
use App\Util\Exceptions\Exception\PublicException;
use Symfony\Component\HttpFoundation\Response;

class OperationNotAllowedException extends PublicException implements LogCustomExceptionInterface
{
    public function getHttpStatusCode(): int
    {
        return Response::HTTP_FORBIDDEN;
    }
}
