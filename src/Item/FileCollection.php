<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Item;

use Ramsey\Collection\AbstractCollection;

class FileCollection extends AbstractCollection
{
    public function getType(): string
    {
        return File::class;
    }
}
