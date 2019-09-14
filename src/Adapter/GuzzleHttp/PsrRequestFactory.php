<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Adapter\GuzzleHttp;

use ArchiveOrg\ItemMetadata\Item\Identifier;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

class PsrRequestFactory implements \ArchiveOrg\ItemMetadata\Factory\PsrRequestFactory
{
    public function newMetadataRequest(Identifier $identifier): RequestInterface
    {
        return new Request(
            'GET',
            sprintf(self::METADATA_URL_PATTERN, (string) $identifier)
        );
    }
}
