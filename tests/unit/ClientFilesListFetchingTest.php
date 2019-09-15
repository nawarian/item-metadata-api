<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata;

use ArchiveOrg\ItemMetadata\Exceptions\ItemNotFoundException;
use ArchiveOrg\ItemMetadata\Factory\TestPsrRequestFactory;
use ArchiveOrg\ItemMetadata\Item\Identifier;
use DateTimeImmutable;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ClientFilesListFetchingTest extends TestCase
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

    private function givenGetFilesByIdentifierReceivesNawarianTestAsIdentifier(): void
    {
        // phpcs:ignore Generic.Files.LineLength.TooLong
        $jsonResponse = '{"result": [{"name": "nawarian-test_meta.xml", "source": "original", "mtime": "1550606461", "size": "465", "format": "Metadata", "md5": "6286b0fd0282c9f24208d1f70fee97ef", "crc32": "7c27ba27", "sha1": "7d5dadf8eb74f24960239fdb059b1a616db36856"}]}';

        $this->fakeHttpClient->shouldReceive('sendRequest')
            ->with(Mockery::on(function (RequestInterface $request) {
                return $request->getMethod() === 'GET' &&
                    (string) $request->getUri() === 'https://archive.org/metadata/nawarian-test/files';
            }))
            ->once()
            ->andReturn($this->createResponseObject(200, $jsonResponse));
    }

    public function testGetFilesByIdentifier(): void
    {
        $this->givenGetFilesByIdentifierReceivesNawarianTestAsIdentifier();

        $identifier = Identifier::newFromIdentifierString('nawarian-test');

        $files = $this->client->getFilesByIdentifier($identifier);
        $file = $files[0];

        $this->assertSame('nawarian-test_meta.xml', $file->name());
        $this->assertSame('original', $file->source());
        $this->assertEquals(new DateTimeImmutable('2019-02-19 20:01:01'), $file->lastModified());
        $this->assertSame(465, $file->size());
        $this->assertSame('Metadata', $file->format());
        $this->assertSame('6286b0fd0282c9f24208d1f70fee97ef', $file->md5());
        $this->assertSame('7c27ba27', $file->crc32());
        $this->assertSame('7d5dadf8eb74f24960239fdb059b1a616db36856', $file->sha1());
    }

    private function givenGetMetadataByIdentifierReceivesHopefullyInexistentIdentifierAsIdentifier(): void
    {
        $content = "Couldn't locate item 'hopefully-inexistent-identifier'";
        $this->fakeHttpClient->shouldReceive('sendRequest')
            ->with(Mockery::on(function (RequestInterface $request) {
                $expectedUri = 'https://archive.org/metadata/hopefully-inexistent-identifier/files';

                return $request->getMethod() === 'GET' && (string) $request->getUri() === $expectedUri;
            }))
            ->once()
            ->andReturn($this->createResponseObject(200, $content));
    }

    public function testGetFilesByIdentifierWhenItemNotFound(): void
    {
        $this->givenGetMetadataByIdentifierReceivesHopefullyInexistentIdentifierAsIdentifier();

        $this->expectException(ItemNotFoundException::class);
        $this->expectExceptionMessage("Item 'hopefully-inexistent-identifier' not found.");

        $this->client->getFilesByIdentifier(
            Identifier::newFromIdentifierString('hopefully-inexistent-identifier')
        );
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

            $fakeBody->shouldReceive('rewind');
        }

        return $fakeResponse;
    }
}
