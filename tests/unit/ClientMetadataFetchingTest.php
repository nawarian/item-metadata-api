<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata;

use ArchiveOrg\ItemMetadata\Exceptions\ItemNotFoundException;
use ArchiveOrg\ItemMetadata\Item\Identifier;

final class ClientMetadataFetchingTest extends ClientTestCase
{
    public function testGetMetadataByIdentifier(): void
    {
        $this->forceFakeHttpClientResponseForUrl(
            'https://archive.org/metadata/nawarian-test/metadata',
            'GET',
            200,
            // phpcs:ignore Generic.Files.LineLength.TooLong
            '{"result":{"identifier":"nawarian-test","publicdate":"2019-02-19 20:00:38","title":"nawarian-test","mediatype":"data","collection":"opensource","uploader":"nawarian@gmail.com","addeddate":"2019-02-19 20:00:38","curation":"[curator]validator@archive.org[/curator][date]20190219200101[/date][comment]checked for malware[/comment]"}}'
        );

        $identifier = Identifier::newFromIdentifierString('nawarian-test');
        $metadata = $this->client->getMetadataByIdentifier($identifier);

        $this->assertEquals('nawarian-test', $metadata->identifier());
        $this->assertEquals('2019-02-19 20:00:38', $metadata->publicationDate()->format('Y-m-d H:i:s'));
    }

    public function testGetMetadataByIdentifierWhenItemNotFound(): void
    {
        $this->forceFakeHttpClientResponseForUrl(
            'https://archive.org/metadata/hopefully-inexistent-identifier/metadata',
            'GET',
            200,
            "Couldn't locate item 'hopefully-inexistent-identifier'"
        );

        $this->expectException(ItemNotFoundException::class);
        $this->expectExceptionMessage("Item 'hopefully-inexistent-identifier' not found.");

        $this->client->getMetadataByIdentifier(
            Identifier::newFromIdentifierString('hopefully-inexistent-identifier')
        );
    }
}
