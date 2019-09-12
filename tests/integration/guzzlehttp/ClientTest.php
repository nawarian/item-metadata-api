<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\GuzzleHttp;

use ArchiveOrg\ItemMetadata\Adapter\GuzzleHttp\HttpClientPsrAdapter;
use ArchiveOrg\ItemMetadata\Client;
use ArchiveOrg\ItemMetadata\Factory\TestPsrRequestFactory;
use ArchiveOrg\ItemMetadata\Item\Identifier;
use GuzzleHttp\Client as GuzzleHttpClient;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private $client;

    private $guzzleHttpClient;

    private $guzzleHttpClientAdapter;

    protected function setUp(): void
    {
        $this->guzzleHttpClient = new GuzzleHttpClient();
        $this->guzzleHttpClientAdapter = new HttpClientPsrAdapter($this->guzzleHttpClient);
        $this->client = new Client($this->guzzleHttpClientAdapter, new TestPsrRequestFactory());
    }

    public function testClientWithGuzzleAdapter(): void
    {
        $this->markTestSkipped('RequestFactory is not sufficient for this test.');

        $metadata = $this->client->getMetadataByIdentifier(
            Identifier::newFromIdentifierString('nawarian-test')
        );

        $this->assertEquals('nawarian-test', $metadata->identifier());
    }
}
