<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata;

class Client
{
    private const METADATA_URL = 'https://archive.org/metadata/%s/metadata';

    public function getMetadataByIdentifier(string $identifier): Metadata
    {
        $metadata = file_get_contents(sprintf(self::METADATA_URL, $identifier));
        $parsedResult = json_decode($metadata, true)['result'] ?? [];

        return new Metadata($parsedResult);
    }
}
