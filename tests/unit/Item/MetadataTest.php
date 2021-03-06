<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Item;

use DateTimeImmutable;
use InvalidArgumentException;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

final class MetadataTest extends TestCase
{
    public function testConstructorFailsWithoutIdentifier(): void
    {
        $this->expectExceptionMessage('Invalid Metadata: No identifier provided.');
        $this->expectException(InvalidArgumentException::class);

        new Metadata([]);
    }

    public function testPublicationDateFormatsDateProperly(): void
    {
        $metadata = new Metadata(['identifier' => 'metadata-test', 'publicdate' => '2019-01-01 10:20:15']);

        $this->assertEquals(
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-01 10:20:15'),
            $metadata->publicationDate()
        );

        $this->assertEquals(
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-01-01 10:20:15'),
            $metadata->publicationDate
        );
    }

    public function testMagicAccessToMetadata(): void
    {
        $metadata = new Metadata(['identifier' => 'metadata-test', 'myMetadata' => 'nawarian']);

        $this->assertEquals('nawarian', $metadata->myMetadata());
        $this->assertEquals('nawarian', $metadata->myMetadata);
    }

    public function testMagicAccessToMetadataThrowsExceptionWhenMetadataNotFound(): void
    {
        $metadata = new Metadata(['identifier' => 'metadata-test']);

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Metadata key \'myMetadata\' is not available.');

        $metadata->myMetadata();
    }
}
