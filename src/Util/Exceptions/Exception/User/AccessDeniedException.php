<?php

namespace App\Util\Exceptions\Exception\User;

use App\Util\Exceptions\Exception\PublicException;
use Symfony\Component\HttpFoundation\Response;

class AccessDeniedException extends PublicException
{
    public function getHttpStatusCode(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }
}
