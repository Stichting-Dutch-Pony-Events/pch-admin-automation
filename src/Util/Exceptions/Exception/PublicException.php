<?php

namespace App\Util\Exceptions\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PublicException extends Exception implements CustomExceptionInterface
{
    public ParameterCollection $parameterCollection;

    public function __construct(
        string $message = "",
        ?ParameterCollection $parameterCollection = null,
        int $code = 0,
        Throwable|null $previous = null
    ) {
        $this->parameterCollection = $parameterCollection ?? new ParameterCollection();

        parent::__construct($message, $code, $previous);
    }

    public function getHttpStatusCode(): int
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    public function getData(): array
    {
        return $this->parameterCollection->toArray();
    }

    public final function isPublicException(): bool
    {
        return true;
    }
}
