<?php

namespace App\Util\Exceptions\Exception\File;

use App\Util\Exceptions\Exception\PublicException;
use Symfony\Component\HttpFoundation\Response;

class FileNotFoundException extends PublicException
{
    public function getHttpStatusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }
}
