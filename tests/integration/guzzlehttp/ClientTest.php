<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\GuzzleHttp;

use ArchiveOrg\ItemMetadata\Adapter\GuzzleHttp\HttpClientPsrAdapter;
use ArchiveOrg\ItemMetadata\Adapter\GuzzleHttp\PsrRequestFactory;
use ArchiveOrg\ItemMetadata\Client;
use ArchiveOrg\ItemMetadata\Exceptions\ItemNotFoundException;
use ArchiveOrg\ItemMetadata\Item\Identifier;
use GuzzleHttp\Client as GuzzleHttpClient;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

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
    public function testGetItemByIdentifierWithGuzzleAdapter(): void
    {
        $item = $this->client->getItemByIdentifier(Identifier::newFromIdentifierString('nawarian-test'));

        $this->assertSame('nawarian-test', $item->metadata()->identifier());
        $this->assertSame('/5/items/nawarian-test', $item->dir());
        $this->assertSame($item->filesCount(), $item->files()->count());

        // I'm not very happy with the check sbelow
        $this->assertContains($item->server(), ['ia803000.us.archive.org', 'ia903000.us.archive.org']);
        $this->assertSame(['ia803000.us.archive.org', 'ia903000.us.archive.org'], $item->workableServers());
        $this->assertSame('ia903000.us.archive.org', $item->d1());
        $this->assertSame('ia803000.us.archive.org', $item->d2());
    }

    public function testGetItemByIdentifierNotFoundWithGuzzleAdapter(): void
    {
        $hopefullyInexistentId = Uuid::uuid4()->toString();
        $this->expectException(ItemNotFoundException::class);
        $this->expectExceptionMessage("Item '{$hopefullyInexistentId}' not found.");

        $this->client->getItemByIdentifier(Identifier::newFromIdentifierString($hopefullyInexistentId));
    }

    public function testGetMetadataByIdentifierWithGuzzleAdapter(): void
    {
        $metadata = $this->client->getMetadataByIdentifier(
            Identifier::newFromIdentifierString('nawarian-test')
        );

        $this->assertEquals('nawarian-test', $metadata->identifier());
    }

    public function testGetMetadataByIdentifierNotFoundWithGuzzleAdapter(): void
    {
        $hopefullyInexistentId = Uuid::uuid4()->toString();
        $this->expectException(ItemNotFoundException::class);
        $this->expectExceptionMessage("Item '{$hopefullyInexistentId}' not found.");

        $this->client->getMetadataByIdentifier(Identifier::newFromIdentifierString($hopefullyInexistentId));
    }

    public function testGetFilesByIdentifierWithGuzzleAdapter(): void
    {
        $files = $this->client->getFilesByIdentifier(Identifier::newFromIdentifierString('nawarian-test'));

        $metadataXmlFile = null;
        foreach ($files as $file) {
            if ($file->name() === 'nawarian-test_meta.xml') {
                $metadataXmlFile = $file;
            }
        }

        $this->assertEquals('original', $metadataXmlFile->source());
    }

    public function testGetFilesByIdentifierNotFoundWithGuzzleAdapter(): void
    {
        $hopefullyInexistentId = Uuid::uuid4()->toString();
        $this->expectException(ItemNotFoundException::class);
        $this->expectExceptionMessage("Item '{$hopefullyInexistentId}' not found.");

        $this->client->getFilesByIdentifier(Identifier::newFromIdentifierString($hopefullyInexistentId));
    }
}
