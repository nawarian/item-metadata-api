<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata;

use ArchiveOrg\ItemMetadata\Factory\TestPsrRequestFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

abstract class ClientTestCase extends TestCase
{
    protected $client;

    private $testPsrRequestFactory;

    private $fakeHttpClient;

    protected function setUp(): void
    {
        $this->testPsrRequestFactory = new TestPsrRequestFactory();
        $this->fakeHttpClient = Mockery::mock(ClientInterface::class);

        $this->client = new Client($this->fakeHttpClient, $this->testPsrRequestFactory);
    }

    protected function forceFakeHttpClientResponseForUrl(
        string $url,
        string $method,
        int $responseCode,
        string $responseBody
    ): void {
        $this->fakeHttpClient->shouldReceive('sendRequest')
            ->with(Mockery::on(function (RequestInterface $request) use ($url, $method) {
                return $request->getMethod() === $method && (string) $request->getUri() === $url;
            }))
            ->once()
            ->andReturn($this->createResponseObject($responseCode, $responseBody));
    }

    private function createResponseObject(int $status, string $content = null): ResponseInterface
    {
        $fakeResponse = Mockery::mock(ResponseInterface::class);
        $fakeResponse->shouldReceive('getStatusCode')
            ->once()
            ->andReturn($status);

        $fakeBody = Mockery::mock(StreamInterface::class);
        $fakeResponse->shouldReceive('getBody')
            ->once()
            ->andReturn($fakeBody);

        $fakeBody->shouldReceive('getContents')
            ->once()
            ->andReturn($content);

        $fakeBody->shouldReceive('rewind');

        return $fakeResponse;
    }
}
