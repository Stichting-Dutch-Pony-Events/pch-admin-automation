<?php

namespace App\DataAccessLayer\Pretix;

use App\Util\MimeUtils;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PretixApiClient
{
    public function __construct(
        private HttpClientInterface $client,
        string                      $baseUrl,
        string                      $apiKey,
        string                      $organiser,
        private string              $event
    ) {
        if (!str_ends_with($baseUrl, '/')) {
            $baseUrl .= '/';
        }
        $baseUrl .= 'api/v1/organizers/' . $organiser . '/';

        $this->client = $this->client->withOptions(
            [
                'base_uri' => $baseUrl,
                'headers'  => [
                    'Authorization' => 'Token ' . $apiKey,
                    'Accept'        => 'application/json'
                ]
            ]
        );
    }

    public function setEvent(string $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function addParametersToUrl(string $url, array $parameters): string
    {
        $i = 0;
        foreach ($parameters as $key => $value) {
            if ($i === 0) {
                $url .= '?';
            } else {
                $url .= '&';
            }
            $url .= $key . '=' . $value;
            $i++;
        }
        return $url;
    }

    public function retrieveAll(string $uri, array $parameters = [], bool $prependEvent = true): array
    {
        if ($parameters === null) {
            $parameters = [];
        }
        $parameters['page'] = 1;
        $nextPageExists = true;
        $results = [];
        while ($nextPageExists) {
            $nextPageExists = false;
            $url = $this->addParametersToUrl($uri, $parameters);

            if ($prependEvent) {
                $url = 'events/' . $this->event . '/' . $url;
            }

            $objects = json_decode($this->client->request(Request::METHOD_GET, $url)->getContent());
            if (property_exists($objects, 'next') && $objects->next !== null) {
                $parameters['page']++;
                $nextPageExists = true;
            }
            if (property_exists($objects, 'results') && is_array($objects->results)) {
                $results = [...$results, ...$objects->results];
            }
        }
        return $results;
    }

    public function retrieve(string $uri, bool $prependEvent = true): object
    {
        if ($prependEvent) {
            $uri = 'events/' . $this->event . '/' . $uri;
        }

        return json_decode($this->client->request(Request::METHOD_GET, $uri)->getContent());
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function retrieveRaw(string $uri, bool $prependEvent = true): ResponseInterface
    {
        if ($prependEvent) {
            $uri = 'events/' . $this->event . '/' . $uri;
        }

        return $this->client->request(Request::METHOD_GET, $uri);
    }

    public function downloadImage(string $url, string $path): string
    {
        $res = $this->client->request(Request::METHOD_GET, $url);
        $ext = MimeUtils::mime2ext($res->getHeaders()['content-type'][0]);
        $path .= '.' . $ext;
        file_put_contents($path, $res->getContent());
        return $path;
    }

    public function post(string $uri, object $data, bool $prependEvent = true): object
    {
        if ($prependEvent) {
            $uri = 'events/' . $this->event . '/' . $uri;
        }

        $response = null;
        try {
            $response = $this->client->request(Request::METHOD_POST, $uri, [
                'json' => $data
            ])->getContent();
        } catch (ClientException $e) {
            $response = $e->getResponse()->getContent(false);
        }

        return json_decode($response);
    }
}
