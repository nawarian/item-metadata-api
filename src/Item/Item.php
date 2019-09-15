<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Item;

use RuntimeException;

class Item
{
    private $information;

    private $metadata;

    private $files;

    public function __construct(ItemInformation $information, Metadata $metadata, FileCollection $files)
    {
        $this->information = $information;
        $this->metadata = $metadata;
        $this->files = $files;
    }

    public function information(): ItemInformation
    {
        return $this->information;
    }

    public function metadata(): Metadata
    {
        return $this->metadata;
    }

    public function files(): FileCollection
    {
        return $this->files;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->information, $name)) {
            return call_user_func([$this->information, $name]);
        }

        throw new RuntimeException("Method '{$name}' doesn't exist.");
    }
}
