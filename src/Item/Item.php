<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Item;

class Item
{
    private $metadata;

    private $files;

    public function __construct(Metadata $metadata, FileCollection $files)
    {
        $this->metadata = $metadata;
        $this->files = $files;
    }

    public function metadata(): Metadata
    {
        return $this->metadata;
    }

    public function files(): FileCollection
    {
        return $this->files;
    }

    public function filesCount(): int
    {
        return $this->files->count();
    }

    public function server(): string
    {
        return 'ia903000.us.archive.org';
    }

    public function d1(): string
    {
        return 'ia903000.us.archive.org';
    }

    public function d2(): string
    {
        return 'ia803000.us.archive.org';
    }

    public function dir(): string
    {
        return '/5/items/nawarian-test';
    }
}
