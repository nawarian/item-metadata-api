<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Adapter\GuzzleHttp;

use GuzzleHttp\ClientInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpClientPsrAdapterTest extends TestCase
{
    private $httpClientPsrAdapter;

    private $guzzleClient;

    protected function setUp(): void
    {
        $this->guzzleClient = Mockery::mock(ClientInterface::class);
        $this->httpClientPsrAdapter = new HttpClientPsrAdapter($this->guzzleClient);
    }

    public function testSendRequestCallsSyncGuzzleSendRequest(): void
    {
        $request = Mockery::mock(RequestInterface::class);
        $expectedResponse = Mockery::mock(ResponseInterface::class);

        $this->guzzleClient->shouldReceive('send')
            ->with($request)
            ->once()
            ->andReturn($expectedResponse);

        $actualResponse = $this->httpClientPsrAdapter->sendRequest($request);

        $this->assertSame($expectedResponse, $actualResponse);
    }
}
