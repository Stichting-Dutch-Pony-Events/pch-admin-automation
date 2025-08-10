<?php

namespace App\Util\Exceptions\Exception\Common;

use App\Util\Exceptions\Exception\PublicException;
use Symfony\Component\HttpFoundation\Response;

class InvalidInputException extends PublicException
{
    public function getHttpStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
