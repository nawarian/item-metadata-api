<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Factory;

use ArchiveOrg\ItemMetadata\Item\Identifier;
use Psr\Http\Message\RequestInterface;

interface PsrRequestFactory
{
    public const METADATA_URL_PATTERN = 'https://archive.org/metadata/%s/metadata';

    /**
     * Builds a new RequestInterface object pointing to self::METADATA_URL_PATTERN and resolved with $identifier.
     */
    public function newMetadataRequest(Identifier $identifier): RequestInterface;
}
