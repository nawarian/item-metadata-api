<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata;

use ArchiveOrg\ItemMetadata\Exceptions\ItemNotFoundException;
use ArchiveOrg\ItemMetadata\Factory\TestPsrRequestFactory;
use ArchiveOrg\ItemMetadata\Item\Identifier;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ClientMetadataFetchingTest extends TestCase
{
    private $client;

    private $fakeHttpClient;

    private $testPsrRequestFactory;

    protected function setUp(): void
    {
        $this->testPsrRequestFactory = new TestPsrRequestFactory();
        $this->fakeHttpClient = Mockery::mock(ClientInterface::class);

        $this->client = new Client($this->fakeHttpClient, $this->testPsrRequestFactory);
    }

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
        $this->fakeHttpClient->shouldReceive('sendRequest')
            ->with(Mockery::on(function (RequestInterface $request) {
                $expectedUri = 'https://archive.org/metadata/hopefully-inexistent-identifier/metadata';

                return $request->getMethod() === 'GET' && (string) $request->getUri() === $expectedUri;
            }))
            ->once()
            ->andReturn($this->createResponseObject(404));
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

    private function createRequestObject(string $method, string $uri): RequestInterface
    {
        $fakeRequest = Mockery::mock(RequestInterface::class);
        $fakeRequest->shouldReceive('getMethod')->andReturn($method);
        $fakeRequest->shouldReceive('getUri')->andReturn($uri);

        return $fakeRequest;
    }

    private function createResponseObject(int $status, string $content = null): ResponseInterface
    {
        $fakeResponse = Mockery::mock(ResponseInterface::class);
        $fakeResponse->shouldReceive('getStatusCode')
            ->once()
            ->andReturn($status);

        if (null !== $content) {
            $fakeBody = Mockery::mock(StreamInterface::class);
            $fakeBody->shouldReceive('getContents')
                ->once()
                ->andReturn($content);

            $fakeResponse->shouldReceive('getBody')
                ->once()
                ->andReturn($fakeBody);
        }

        return $fakeResponse;
    }
}
