<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata;

use ArchiveOrg\ItemMetadata\Exceptions\ItemNotFoundException;

class Client
{
    private const METADATA_URL = 'https://archive.org/metadata/%s/metadata';

    public function getMetadataByIdentifier(string $identifier): Metadata
    {
        $metadata = file_get_contents(sprintf(self::METADATA_URL, $identifier));
        $parsedResult = json_decode($metadata, true) ?? [];

        if (true === array_key_exists('error', $parsedResult)) {
            throw new ItemNotFoundException("Item '{$identifier}' not found.");
        }

        return new Metadata($parsedResult['result'] ?? []);
    }
}
