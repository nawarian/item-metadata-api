<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata;

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
        return DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $this->metadata['publicdate']);
    }

    public function __call($name, $arguments)
    {
        if (true === array_key_exists($name, $this->metadata)) {
            return $this->metadata[$name];
        }

        throw new OutOfBoundsException("Metadata key '{$name}' is not available.");
    }
}
