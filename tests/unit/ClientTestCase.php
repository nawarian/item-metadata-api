<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata;

use ArchiveOrg\ItemMetadata\Factory\TestPsrRequestFactory;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

abstract class ClientTestCase extends TestCase
{
    protected $client;

    protected $fakeHttpClient;

    protected $testPsrRequestFactory;

    protected function setUp(): void
    {
        $this->testPsrRequestFactory = new TestPsrRequestFactory();
        $this->fakeHttpClient = Mockery::mock(ClientInterface::class);

        $this->client = new Client($this->fakeHttpClient, $this->testPsrRequestFactory);
    }

    protected function createResponseObject(int $status, string $content = null): ResponseInterface
    {
        $fakeResponse = Mockery::mock(ResponseInterface::class);
        $fakeResponse->shouldReceive('getStatusCode')
            ->once()
            ->andReturn($status);

        if (null !== $content) {
            $fakeBody = Mockery::mock(StreamInterface::class);
            $fakeBody->shouldReceive('getContents')
                ->once()
                ->andReturn($content);

            $fakeBody->shouldReceive('rewind');

            $fakeResponse->shouldReceive('getBody')
                ->once()
                ->andReturn($fakeBody);
        }

        return $fakeResponse;
    }
}
