<?php

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

        $this->assertEquals('nawarian-test', $metadata->getIdentifier());
        $this->assertEquals('2019-02-19 20:00:38', $metadata->getPublicationDate()->format('Y-m-d H:i:s'));
    }
}
