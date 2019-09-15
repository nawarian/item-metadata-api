<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata;

use ArchiveOrg\ItemMetadata\Exceptions\ItemNotFoundException;
use ArchiveOrg\ItemMetadata\Item\Identifier;
use Mockery;
use Psr\Http\Message\RequestInterface;

final class ClientMetadataFetchingTest extends ClientTestCase
{
    private function givenGetMetadataByIdentifierReceivesNawarianTestAsIdentifier(): void
    {
        // phpcs:ignore Generic.Files.LineLength.TooLong
        $jsonResponse = '{"result":{"identifier":"nawarian-test","publicdate":"2019-02-19 20:00:38","title":"nawarian-test","mediatype":"data","collection":"opensource","uploader":"nawarian@gmail.com","addeddate":"2019-02-19 20:00:38","curation":"[curator]validator@archive.org[/curator][date]20190219200101[/date][comment]checked for malware[/comment]"}}';

        $this->fakeHttpClient->shouldReceive('sendRequest')
            ->with(Mockery::on(function (RequestInterface $request) {
                return $request->getMethod() === 'GET' &&
                    (string) $request->getUri() === 'https://archive.org/metadata/nawarian-test/metadata';
            }))
            ->once()
            ->andReturn($this->createResponseObject(200, $jsonResponse));
    }

    public function testGetMetadataByIdentifier(): void
    {
        $this->givenGetMetadataByIdentifierReceivesNawarianTestAsIdentifier();

        $identifier = Identifier::newFromIdentifierString('nawarian-test');
        $metadata = $this->client->getMetadataByIdentifier($identifier);

        $this->assertEquals('nawarian-test', $metadata->identifier());
        $this->assertEquals('2019-02-19 20:00:38', $metadata->publicationDate()->format('Y-m-d H:i:s'));
    }

    private function givenGetMetadataByIdentifierReceivesHopefullyInexistentIdentifierAsIdentifier(): void
    {
        $content = "Couldn't locate item 'hopefully-inexistent-identifier'";
        $this->fakeHttpClient->shouldReceive('sendRequest')
            ->with(Mockery::on(function (RequestInterface $request) {
                $expectedUri = 'https://archive.org/metadata/hopefully-inexistent-identifier/metadata';

                return $request->getMethod() === 'GET' && (string) $request->getUri() === $expectedUri;
            }))
            ->once()
            ->andReturn($this->createResponseObject(200, $content));
    }

    public function testGetMetadataByIdentifierWhenItemNotFound(): void
    {
        $this->givenGetMetadataByIdentifierReceivesHopefullyInexistentIdentifierAsIdentifier();

        $this->expectException(ItemNotFoundException::class);
        $this->expectExceptionMessage("Item 'hopefully-inexistent-identifier' not found.");

        $this->client->getMetadataByIdentifier(
            Identifier::newFromIdentifierString('hopefully-inexistent-identifier')
        );
    }
}
