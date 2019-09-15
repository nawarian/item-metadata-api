<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Adapter\GuzzleHttp;

use ArchiveOrg\ItemMetadata\Item\Identifier;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

final class PsrRequestFactory implements \ArchiveOrg\ItemMetadata\Factory\PsrRequestFactory
{
    public function newItemRequest(Identifier $identifier): RequestInterface
    {
        return $this->buildGetRequestBasedOnPattern(self::ITEM_URL_PATTERN, $identifier);
    }

    public function newMetadataRequest(Identifier $identifier): RequestInterface
    {
        return $this->buildGetRequestBasedOnPattern(self::METADATA_URL_PATTERN, $identifier);
    }

    public function newFilesRequest(Identifier $identifier): RequestInterface
    {
        return $this->buildGetRequestBasedOnPattern(self::FILES_URL_PATTERN, $identifier);
    }

    private function buildGetRequestBasedOnPattern(string $urlPattern, Identifier $identifier): RequestInterface
    {
        return new Request('GET', sprintf($urlPattern, (string) $identifier));
    }
}
