<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata;

use ArchiveOrg\ItemMetadata\Exceptions\ItemNotFoundException;
use ArchiveOrg\ItemMetadata\Factory\PsrRequestFactory;
use ArchiveOrg\ItemMetadata\Item\File;
use ArchiveOrg\ItemMetadata\Item\Identifier;
use ArchiveOrg\ItemMetadata\Item\Metadata;
use Psr\Http\Client\ClientInterface;

class Client
{
    private $httpClient;

    private $requestFactory;

    public function __construct(ClientInterface $httpClient, PsrRequestFactory $requestFactory)
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

    public function getFilesByIdentifier(Identifier $identifier): array
    {
        $response = $this->httpClient->sendRequest($this->requestFactory->newFilesRequest($identifier));

        if (404 === $response->getStatusCode()) {
            throw new ItemNotFoundException("Item '{$identifier}' not found.");
        }

        $parsedResult = json_decode($response->getBody()->getContents(), true)['result'] ?? [];

        return array_map(function (array $file) {
            return File::createFromArray($file);
        }, $parsedResult);
    }
}
