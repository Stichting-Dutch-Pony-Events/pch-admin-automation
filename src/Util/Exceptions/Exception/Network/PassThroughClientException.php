<?php

namespace App\Util\Exceptions\Exception\Network;

use App\Util\Exceptions\Exception\CustomExceptionInterface;
use App\Util\Exceptions\Exception\LogCustomExceptionInterface;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PassThroughClientException extends Exception implements CustomExceptionInterface, LogCustomExceptionInterface
{
    private function __construct(
        protected ResponseInterface $response,
        protected bool $isPublic = false,
        protected $message = "",
        protected $code = 0,
        protected $previous = null
    ) {
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }

    public static function createFromClientException(
        ClientExceptionInterface $clientException,
        bool $isPublic = false
    ): static {
        return new static(
            $clientException->getResponse(),
            $isPublic,
            $clientException->getMessage(),
            $clientException->getCode(),
            $clientException
        );
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getHttpStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getData(): string
    {
        try {
            $responseData =  $this->response->getContent(false);
        } catch (Exception) {
            $responseData = '';
        }

        return $responseData;
    }

    public function isPublicException(): bool
    {
        return $this->isPublic;
    }
}
