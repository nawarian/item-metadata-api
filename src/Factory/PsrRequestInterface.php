<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Factory;

use Psr\Http\Message\RequestInterface;

interface PsrRequestInterface
{
    public const METADATA_URL_PATTERN = 'https://archive.org/metadata/%s/metadata';

    /**
     * Builds a new RequestInterface object pointing to self::METADATA_URL_PATTERN and resolved with $identifier.
     *
     * @param string $identifier
     * @return RequestInterface
     */
    public function newMetadataRequest(string $identifier): RequestInterface;
}
