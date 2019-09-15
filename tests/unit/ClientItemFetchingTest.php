<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata;

use ArchiveOrg\ItemMetadata\Exceptions\ItemNotFoundException;
use ArchiveOrg\ItemMetadata\Item\Identifier;
use Mockery;
use Psr\Http\Message\RequestInterface;

class ClientItemFetchingTest extends ClientTestCase
{
    private function givenGetItemByIdentifierReceivesNawarianTestAsIdentifier(): void
    {
        // phpcs:ignore Generic.Files.LineLength.TooLong
        $jsonResponse = '{"created":1568580606,"d1":"ia903000.us.archive.org","d2":"ia803000.us.archive.org","dir":"/5/items/nawarian-test","files":[{"name":"archive_388_data.tar.gz","source":"original","mtime":"1561016986","size":"35380676","md5":"29e3cea56f0b26df41d2e28fce15b7a7","crc32":"b5d19656","sha1":"d3d6b5d8241e083f49b38b2b879a2ea29e46d872","format":"GZIP"},{"name":"archive_389_data.tar.gz","source":"original","mtime":"1561017238","size":"35380645","md5":"b7436d47b26e2937cfadccd2c13812bd","crc32":"ea3690ff","sha1":"422c34a5f262396d4de1a5b630dfe78822e50c5e","format":"GZIP"},{"name":"archive_394_data.tar.gz","source":"original","mtime":"1561019500","size":"118","md5":"52abf60e8ab177a7caea2fd812f94742","crc32":"393d4f8b","sha1":"eafb0d3239d3ad0b84441622942dd8f22b69b94b","format":"GZIP"},{"name":"archive_397_data.tar.gz","source":"original","mtime":"1561019761","size":"118","md5":"ec294a560b5f646a77c3e8f1c1b9a89c","crc32":"753f85a2","sha1":"d671e84cb2bc14f57b9636f5de7a2995baee0a35","format":"GZIP"},{"name":"archive_422_data.tar.gz","source":"original","mtime":"1562424665","size":"274","md5":"db748bb55302314495ac5752c6e7ac69","crc32":"a6815794","sha1":"63ac7a827fc75fda59f220406ad4cd59da9011bb","format":"GZIP"},{"name":"archive_423_data.tar.gz","source":"original","mtime":"1562429781","size":"274","md5":"0173d3fa323029a3b8aed793e69b6646","crc32":"0a0466b6","sha1":"e17b3f5684ada9d18a39e06a61ad883d819d74f6","format":"GZIP"},{"name":"archive_484_data.tar.gz","source":"original","mtime":"1567293637","size":"6844308","md5":"b3aef702a233c6de10ab6dcb9fcb8e2f","crc32":"a5a6e867","sha1":"9d3683a83572bfe8c3906aba81d2f6a09d642818","format":"GZIP"},{"name":"nawarian-test_archive.torrent","source":"metadata","btih":"112d1609ad458c93ff08d3c8561759c38084702d","mtime":"1568503807","size":"5656","md5":"2693ac517df1dfde0a4ea1edff4df6cb","crc32":"0dbe33ca","sha1":"66f62dc9f4e47b401392b58bfb1dab6e74a3fa66","format":"Archive BitTorrent"},{"name":"nawarian-test_files.xml","source":"original","format":"Metadata","md5":"7a342bc992f9318d3a5847c865219753"},{"name":"nawarian-test_meta.sqlite","source":"original","mtime":"1568503807","size":"27648","format":"Metadata","md5":"a6231cfe933c5bfb5acd487b8537ac2b","crc32":"b1395d0f","sha1":"b243b0622c892fc28d1f7f9bab9dcbf0733061c0"},{"name":"nawarian-test_meta.xml","source":"original","mtime":"1550606461","size":"465","format":"Metadata","md5":"6286b0fd0282c9f24208d1f70fee97ef","crc32":"7c27ba27","sha1":"7d5dadf8eb74f24960239fdb059b1a616db36856"}],"files_count":11,"item_size":77640182,"metadata":{"identifier":"nawarian-test","publicdate":"2019-02-19 20:00:38","title":"nawarian-test","mediatype":"data","collection":"opensource","uploader":"nawarian@gmail.com","addeddate":"2019-02-19 20:00:38","curation":"[curator]validator@archive.org[/curator][date]20190219200101[/date][comment]checked for malware[/comment]"},"server":"ia803000.us.archive.org","uniq":52771327,"workable_servers":["ia803000.us.archive.org","ia903000.us.archive.org"]}';

        $this->fakeHttpClient->shouldReceive('sendRequest')
            ->with(Mockery::on(function (RequestInterface $request) {
                return $request->getMethod() === 'GET' &&
                    (string) $request->getUri() === 'https://archive.org/metadata/nawarian-test';
            }))
            ->once()
            ->andReturn($this->createResponseObject(200, $jsonResponse));
    }

    public function testGetItemByIdentifier(): void
    {
        $this->givenGetItemByIdentifierReceivesNawarianTestAsIdentifier();

        $identifier = Identifier::newFromIdentifierString('nawarian-test');
        $item = $this->client->getItemByIdentifier($identifier);

        $metadata = $item->metadata();

        $this->assertEquals('nawarian-test', $metadata->identifier());
        $this->assertEquals('2019-02-19 20:00:38', $metadata->publicationDate()->format('Y-m-d H:i:s'));
        $this->assertSame(11, $item->filesCount());
        $this->assertSame(11, $item->files()->count());
    }

    private function givenGetItemByIdentifierReceivesHopefullyInexistentIdentifierAsIdentifier(): void
    {
        $content = "Couldn't locate item 'hopefully-inexistent-identifier'";
        $this->fakeHttpClient->shouldReceive('sendRequest')
            ->with(Mockery::on(function (RequestInterface $request) {
                $expectedUri = 'https://archive.org/metadata/hopefully-inexistent-identifier';

                return $request->getMethod() === 'GET' && (string) $request->getUri() === $expectedUri;
            }))
            ->once()
            ->andReturn($this->createResponseObject(200, $content));
    }

    public function testGetItemByIdentifierWhenItemNotFound(): void
    {
        $this->givenGetItemByIdentifierReceivesHopefullyInexistentIdentifierAsIdentifier();

        $this->expectException(ItemNotFoundException::class);
        $this->expectExceptionMessage("Item 'hopefully-inexistent-identifier' not found.");

        $this->client->getItemByIdentifier(
            Identifier::newFromIdentifierString('hopefully-inexistent-identifier')
        );
    }
}
