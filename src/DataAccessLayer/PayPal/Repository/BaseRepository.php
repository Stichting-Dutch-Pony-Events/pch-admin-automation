<?php

namespace App\DataAccessLayer\PayPal\Repository;

use App\DataAccessLayer\PayPal\PayPalApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class BaseRepository
{
    protected SerializerInterface $serializer;

    public function __construct(
        protected PayPalApi $payPalApi,

    ) {
        $nameConverter = new CamelCaseToSnakeCaseNameConverter();
        $normalizer    = new ObjectNormalizer(null, $nameConverter);
        $encoder       = new JsonEncoder();

        $this->serializer = new Serializer([$normalizer], [$encoder]);
    }

    /**
     * @param  string  $url
     * @param  class-string  $viewClass
     * @return object
     * @throws ExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function retrieveOne(string $url, string $viewClass): object
    {
        $response = $this->payPalApi->request(Request::METHOD_GET, $url);

        return $this->serializer->deserialize($response->getContent(), $viewClass, 'json');
    }
}
