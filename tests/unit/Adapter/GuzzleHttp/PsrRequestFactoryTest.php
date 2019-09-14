<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Adapter\GuzzleHttp;

use ArchiveOrg\ItemMetadata\Item\Identifier;
use PHPUnit\Framework\TestCase;

class PsrRequestFactoryTest extends TestCase
{
    public function testNewMetadataRequest(): void
    {
        $factory = new PsrRequestFactory();

        $request = $factory->newMetadataRequest(Identifier::newFromIdentifierString('nawarian-test'));

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('https://archive.org/metadata/nawarian-test/metadata', $request->getUri());
    }
}
