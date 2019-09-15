<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata;

use ArchiveOrg\ItemMetadata\Exceptions\ItemNotFoundException;
use ArchiveOrg\ItemMetadata\Factory\PsrRequestFactory;
use ArchiveOrg\ItemMetadata\Item\File;
use ArchiveOrg\ItemMetadata\Item\FileCollection;
use ArchiveOrg\ItemMetadata\Item\Identifier;
use ArchiveOrg\ItemMetadata\Item\Item;
use ArchiveOrg\ItemMetadata\Item\Metadata;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

final class Client
{
    private $httpClient;

    private $requestFactory;

    public function __construct(ClientInterface $httpClient, PsrRequestFactory $requestFactory)
    {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
    }

    public function getItemByIdentifier(Identifier $identifier): Item
    {
        $response = $this->httpClient->sendRequest($this->requestFactory->newItemRequest($identifier));
        $this->failIfErroredResponse($response, $identifier);

        return Item::createFromArray(json_decode($response->getBody()->getContents(), true) ?? []);
    }

    public function getMetadataByIdentifier(Identifier $identifier): Metadata
    {
        $response = $this->httpClient->sendRequest($this->requestFactory->newMetadataRequest($identifier));
        $this->failIfErroredResponse($response, $identifier);

        return new Metadata(json_decode($response->getBody()->getContents(), true)['result'] ?? []);
    }

    public function getFilesByIdentifier(Identifier $identifier): FileCollection
    {
        $response = $this->httpClient->sendRequest($this->requestFactory->newFilesRequest($identifier));
        $this->failIfErroredResponse($response, $identifier);

        $files = array_map(function (array $file) {
            return File::createFromArray($file);
        }, json_decode($response->getBody()->getContents(), true)['result'] ?? []);

        return new FileCollection($files);
    }

    private function failIfErroredResponse(ResponseInterface $response, Identifier $identifier): void
    {
        $rawResponse = $response->getBody()->getContents();

        if ('{}' === $rawResponse || strpos($rawResponse, "Couldn't locate item '{$identifier}'") !== false) {
            throw new ItemNotFoundException("Item '{$identifier}' not found.");
        }

        $response->getBody()->rewind();
    }
}
