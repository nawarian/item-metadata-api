<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Item;


use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testGeneratedAtTransformsTimestampIntoDateTimeInterface(): void
    {
        $item = Item::createFromArray([
            'metadata' => [],
            'files' => [],
            'server' => '',
            'workable_servers' => [],
            'd1' => '',
            'd2' => '',
            'dir' => '',
            'files_count' => 0,
            'uniq' => 0,
            'item_size' => '',
            'created' => 1568531253,
        ]);

        $date = DateTimeImmutable::createFromFormat('U', '1568531253');
        $this->assertEquals($date, $item->generatedAt());
    }
}
