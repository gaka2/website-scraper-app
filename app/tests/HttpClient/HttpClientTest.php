<?php

declare(strict_types=1);

namespace App\Tests\HttpClient;

use App\Exception\HttpClientException;
use App\HttpClient\HttpClient;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @covers \App\HttpClient\HttpClient
 *
 * @author Karol Gancarczyk
 *
 */
class HttpClientTest extends TestCase
{
    protected HttpClientInterface $httpClientSymfony;

    private const WEBSITE_URL = 'https://httpstat.us/200';

    private const NON_EXISTING_WEBSITE_URL = 'https://httpstat.us/404';

    public function testGetWebsiteContent(): void
    {
        $websiteContent = <<<'HTML'
        <!DOCTYPE html>
        <html>
        <body>
        </body>
        </html>
        HTML;

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getContent')
            ->with(self::WEBSITE_URL)
            ->willReturn($websiteContent)
        ;

        $httpClientSymfony = $this->createStub(HttpClientInterface::class);
        $httpClientSymfony->method('request')
             ->willReturn($response)
        ;

        $httpClient = new HttpClient(self::WEBSITE_URL, $httpClientSymfony);

        $actualContent = $httpClient->getWebsiteContent();

        $this->assertEquals($websiteContent, $actualContent);
    }

    public function testGetWebsiteContentShouldThrowException(): void
    {
        $exception = $this->createStub(ExceptionInterface::class);

        $response = $this->createStub(ResponseInterface::class);
        $response->method('getContent')
            ->with(self::NON_EXISTING_WEBSITE_URL)
            ->willThrowException($exception)
        ;

        $httpClientSymfony = $this->createStub(HttpClientInterface::class);
        $httpClientSymfony->method('request')
             ->willReturn($response)
        ;

        $httpClient = new HttpClient(self::NON_EXISTING_WEBSITE_URL, $httpClientSymfony);

        $this->expectException(HttpClientException::class);

        $httpClient->getWebsiteContent();
    }
}
