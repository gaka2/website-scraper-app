<?php

declare(strict_types=1);

namespace App\HttpClient;

use App\Exception\HttpClientException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 *
 * @author Karol Gancarczyk
 *
 */
class HttpClient
{
    public function __construct(
        protected readonly string $websiteUrl,
        protected readonly HttpClientInterface $httpClient,
    ) {
    }

    public function getWebsiteContent(): string
    {
        try {
            /** @var ResponseInterface */
            $response = $this->httpClient->request(
                'GET',
                $this->websiteUrl,
            );

            return $response->getContent();
        } catch (ExceptionInterface $e) {
            throw new HttpClientException('Sending HTTP request failed. '. $e->getMessage(), 0, $e);
        }
    }
}
