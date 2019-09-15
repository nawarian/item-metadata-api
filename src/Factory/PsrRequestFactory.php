<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Factory;

use ArchiveOrg\ItemMetadata\Item\Identifier;
use Psr\Http\Message\RequestInterface;

interface PsrRequestFactory
{
    public const ITEM_URL_PATTERN = 'https://archive.org/metadata/%s';

    public const METADATA_URL_PATTERN = 'https://archive.org/metadata/%s/metadata';

    public const FILES_URL_PATTERN = 'https://archive.org/metadata/%s/files';

    /**
     * Builds a new RequestInterface object pointing to self::ITEM_URL_PATTERN and resolved with $identifier.
     */
    public function newItemRequest(Identifier $identifier): RequestInterface;

    /**
     * Builds a new RequestInterface object pointing to self::METADATA_URL_PATTERN and resolved with $identifier.
     */
    public function newMetadataRequest(Identifier $identifier): RequestInterface;

    /**
     * Buils a new RequestInterface object pointing to self::FILES_URL_PATTERN and resolved with $identifier.
     */
    public function newFilesRequest(Identifier $identifier): RequestInterface;
}
