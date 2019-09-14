<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\GuzzleHttp;

use ArchiveOrg\ItemMetadata\Adapter\GuzzleHttp\HttpClientPsrAdapter;
use ArchiveOrg\ItemMetadata\Adapter\GuzzleHttp\PsrRequestFactory;
use ArchiveOrg\ItemMetadata\Client;
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
        $this->client = new Client($this->guzzleHttpClientAdapter, new PsrRequestFactory());
    }

    public function testClientWithGuzzleAdapter(): void
    {
        $metadata = $this->client->getMetadataByIdentifier(
            Identifier::newFromIdentifierString('nawarian-test')
        );

        $this->assertEquals('nawarian-test', $metadata->identifier());
    }
}
