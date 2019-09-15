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

final class ClientTest extends TestCase
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
        $this->assertSame(1294034205, $item->uniq());
        $this->assertSame(date('Y-m-d'), $item->generatedAt()->format('Y-m-d'));
        $this->assertIsArray($item->workableServers());
        $this->assertNotCount(0, $item->workableServers());
        $this->assertContains($item->server(), $item->workableServers());
        $this->assertContains($item->d1(), $item->workableServers());
        $this->assertContains($item->d2(), $item->workableServers());

        // I'm not very happy with the checks below
        $this->assertSame(77640182, $item->itemSize());
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
