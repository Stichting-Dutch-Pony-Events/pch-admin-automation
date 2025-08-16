<?php

namespace App\DataAccessLayer\EBoekHouden;

use App\DataAccessLayer\EBoekHouden\Views\Session;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class EBoekHoudenApi implements HttpClientInterface
{
    public const EB_API_BASE_URL = 'https://api.e-boekhouden.nl/';

    private ?Session $session = null;

    public function __construct(
        private SerializerInterface $serializer,
        private HttpClientInterface $httpClient,
        private string              $apiToken
    ) {
        $this->authenticate();

        $this->httpClient = $this->httpClient->withOptions(
            [
                'base_uri' => self::EB_API_BASE_URL,
                'headers'  => [
                    'Authorization' => $this->session->token,
                ]
            ]
        );
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function authenticate(): void
    {
        try {
            $response = $this->request(
                Request::METHOD_POST,
                self::EB_API_BASE_URL . 'v1/session',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept'       => 'application/json',
                    ],
                    'json'    => [
                        'accessToken' => $this->apiToken,
                        'source'      => 'AdminApi',
                    ]
                ]
            );
        } catch (ClientException $e) {
            var_dump($e->getMessage());
        }

        $this->session = $this->serializer->deserialize($response->getContent(), Session::class, 'json');
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        return $this->httpClient->request($method, $url, $options);
    }

    public function stream(iterable|ResponseInterface $responses, ?float $timeout = null): ResponseStreamInterface
    {
        return $this->httpClient->stream($responses, $timeout);
    }

    public function withOptions(array $options): static
    {
        return new static(
            $this->serializer,
            $this->httpClient->withOptions($options),
            $this->apiToken
        );
    }
}
