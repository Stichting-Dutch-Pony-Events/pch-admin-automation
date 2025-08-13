<?php

namespace App\DataAccessLayer\EBoekHouden\Repository;

use App\DataAccessLayer\EBoekHouden\EBoekHoudenApi;
use App\DataAccessLayer\EBoekHouden\FilterParams\FilterParamInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class BaseRepository
{
    public function __construct(
        protected readonly SerializerInterface $serializer,
        protected readonly EBoekHoudenApi      $boekHoudenApi,
        protected readonly CacheInterface      $cache
    ) {
    }

    protected function retrieveMany(string $url, string $viewClass, array $params = []): array|object
    {
        $response = $this->boekHoudenApi->request(Request::METHOD_GET, $this->applyParamsToUrl($url, $params));

        return $this->serializer->deserialize($response->getContent(), $viewClass, 'json');
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
        $response = $this->boekHoudenApi->request(Request::METHOD_GET, $url);

        return $this->serializer->deserialize($response->getContent(), $viewClass, 'json');
    }

    protected function create(string $url, object $request, string $viewClass): object
    {
        $response = $this->boekHoudenApi->request(
            Request::METHOD_POST,
            $url,
            [
                'json' => $request,
            ]
        );

        return $this->serializer->deserialize($response->getContent(), $viewClass, 'json');
    }

    protected function patch(string $url, object $request, string $viewClass): bool
    {
        $response = $this->boekHoudenApi->request(
            Request::METHOD_PATCH,
            $url,
            [
                'json' => $request,
            ]
        );

        return $response->getStatusCode() > 200 && $response->getStatusCode() < 400;
    }

    /**
     * @param  string  $url
     * @param  FilterParamInterface[]  $params
     * @return string
     */
    private function applyParamsToUrl(string $url, array $params): string
    {
        if (empty($params)) {
            return $url;
        }

        $str = [];
        foreach ($params as $param) {
            $str[] = strval($param);
        }

        $queryStr = implode('&', $str);

        return $url.'?'.$queryStr;
    }
}
