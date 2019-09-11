<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata;

use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = new Client();
    }

    public function testGetMetadataByIdentifier(): void
    {
        $metadata = $this->client->getMetadataByIdentifier('nawarian-test');

        $this->assertEquals('nawarian-test', $metadata->identifier());
        $this->assertEquals('2019-02-19 20:00:38', $metadata->publicationDate()->format('Y-m-d H:i:s'));
    }
}
