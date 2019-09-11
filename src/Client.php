<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata;

use ArchiveOrg\ItemMetadata\Exceptions\ItemNotFoundException;
use ArchiveOrg\ItemMetadata\Factory\PsrRequestInterface;
use ArchiveOrg\ItemMetadata\Item\Identifier;
use ArchiveOrg\ItemMetadata\Item\Metadata;
use Psr\Http\Client\ClientInterface;

class Client
{
    private $httpClient;

    private $requestFactory;

    public function __construct(ClientInterface $httpClient, PsrRequestInterface $requestFactory)
    {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
    }

    public function getMetadataByIdentifier(Identifier $identifier): Metadata
    {
        $response = $this->httpClient->sendRequest($this->requestFactory->newMetadataRequest($identifier));

        if (404 === $response->getStatusCode()) {
            throw new ItemNotFoundException("Item '{$identifier}' not found.");
        }

        $parsedResult = json_decode($response->getBody()->getContents(), true)['result'] ?? [];

        return new Metadata($parsedResult ?? []);
    }
}
