<?php

namespace App\Util\Exceptions\Exception\Common;

use App\Util\Exceptions\Exception\PublicException;
use Symfony\Component\HttpFoundation\Response;

class InvalidApiResponseException extends PublicException
{
    public function getHttpStatusCode(): int
    {
        return Response::HTTP_EXPECTATION_FAILED;
    }
}
