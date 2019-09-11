<?php

namespace ArchiveOrg\ItemMetadata;

use DateTimeInterface;

class Client
{
    public function getMetadataByIdentifier(string $identifier): object
    {
        return new class {
            public function getIdentifier(): string
            {
                return 'nawarian-test';
            }

            public function getPublicationDate(): DateTimeInterface
            {
                return new \DateTime('2019-02-19 20:00:38');
            }
        };
    }
}
