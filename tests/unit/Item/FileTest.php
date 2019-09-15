<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Item;

use PHPUnit\Framework\TestCase;

final class FileTest extends TestCase
{
    public function testCreateFromArrayWithBasicArrayData(): void
    {
        $basicArrayData = [
            'name' => 'nawarian-test_files.xml',
            'source' => 'original',
            'format' => 'Metadata',
            'md5' => 'f811fdb046b418e447831e2c84b28d7e',
        ];

        $file = File::createFromArray($basicArrayData);

        $this->assertEquals('nawarian-test_files.xml', $file->name());
        $this->assertEquals('original', $file->source());
        $this->assertEquals('Metadata', $file->format());
        $this->assertEquals('f811fdb046b418e447831e2c84b28d7e', $file->md5());

        $this->assertNull($file->lastModified());
        $this->assertNull($file->size());
        $this->assertNull($file->crc32());
        $this->assertNull($file->sha1());
    }

    public function testCreateFromArrayTransformSizeIntoInteger(): void
    {
        $basicArrayDataWithSize = [
            'name' => 'nawarian-test_files.xml',
            'source' => 'original',
            'format' => 'Metadata',
            'md5' => 'f811fdb046b418e447831e2c84b28d7e',
            'size' => '465',
        ];

        $file = File::createFromArray($basicArrayDataWithSize);

        $this->assertSame(465, $file->size());
    }

    public function testCreateFromArrayTransformMtimeIntoDateTimeInterface(): void
    {
        $basicArrayDataWithMtime = [
            'name' => 'nawarian-test_files.xml',
            'source' => 'original',
            'format' => 'Metadata',
            'md5' => 'f811fdb046b418e447831e2c84b28d7e',
            'mtime' => '1550606461',
        ];

        $file = File::createFromArray($basicArrayDataWithMtime);

        $this->assertEquals('2019-02-19 20:01:01', $file->lastModified()->format('Y-m-d H:i:s'));
    }
}
