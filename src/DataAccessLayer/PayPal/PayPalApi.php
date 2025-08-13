<?php

namespace App\DataAccessLayer\PayPal;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class PayPalApi implements HttpClientInterface
{
    private const PAYPAL_API_URL = 'https://api-m.paypal.com/';

    public function __construct(
        private SerializerInterface $serializer,
        private HttpClientInterface $httpClient,
        private readonly string     $paypalClientId,
        private readonly string     $paypalSecret
    ) {
        $this->authenticate();
    }

    private function authenticate(): void
    {
        $response = $this->httpClient->request(
            Request::METHOD_POST,
            self::PAYPAL_API_URL.'v1/oauth2/token',
            [
                'auth_basic' => [$this->paypalClientId, $this->paypalSecret],
                'headers'    => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'body'       => [
                    'grant_type' => 'client_credentials',
                ],
            ]
        );

        $decoded = json_decode($response->getContent());
        if (property_exists($decoded, 'access_token')) {
            $this->httpClient = $this->httpClient->withOptions([
                'base_uri' => self::PAYPAL_API_URL,
                'headers'  => [
                    'Authorization' => 'Bearer '.$decoded->access_token,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ]
            ]);
        }
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
            $this->httpClient,
            $this->paypalClientId,
            $this->paypalSecret
        );
    }
}
