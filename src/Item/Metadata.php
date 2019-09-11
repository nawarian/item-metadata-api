<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Item;

use DateTimeImmutable;
use DateTimeInterface;
use OutOfBoundsException;

class Metadata
{
    private $metadata = [];

    public function __construct(array $metadata)
    {
        $this->metadata = $metadata;
    }

    public function publicationDate(): DateTimeInterface
    {
        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->publicdate);
    }

    public function __get($name)
    {
        return $this->__call($name, null);
    }

    public function __call($name, $arguments)
    {
        if (true === method_exists($this, $name)) {
            return call_user_func([$this, $name]);
        }

        if (true === array_key_exists($name, $this->metadata)) {
            return $this->metadata[$name];
        }

        throw new OutOfBoundsException("Metadata key '{$name}' is not available.");
    }
}
