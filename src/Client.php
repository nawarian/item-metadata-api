<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata;

use ArchiveOrg\ItemMetadata\Exceptions\ItemNotFoundException;
use ArchiveOrg\ItemMetadata\Factory\PsrRequestFactory;
use ArchiveOrg\ItemMetadata\Item\File;
use ArchiveOrg\ItemMetadata\Item\Identifier;
use ArchiveOrg\ItemMetadata\Item\Metadata;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
        $response = $this->fetchHttpResponse($identifier, $this->requestFactory->newMetadataRequest($identifier));

        $parsedResult = json_decode($response->getBody()->getContents(), true)['result'] ?? [];

        return new Metadata($parsedResult ?? []);
    }

    public function getFilesByIdentifier(Identifier $identifier): array
    {
        $response = $this->fetchHttpResponse($identifier, $this->requestFactory->newFilesRequest($identifier));

        $parsedResult = json_decode($response->getBody()->getContents(), true)['result'] ?? [];

        return array_map(function (array $file) {
            return File::createFromArray($file);
        }, $parsedResult);
    }

    private function fetchHttpResponse(Identifier $identifier, RequestInterface $request): ResponseInterface
    {
        $response = $this->httpClient->sendRequest($request);
        $rawResponse = $response->getBody()->getContents();

        if (strpos($rawResponse, "Couldn't locate item '{$identifier}'") !== false) {
            throw new ItemNotFoundException("Item '{$identifier}' not found.");
        }

        $response->getBody()->rewind();
        return $response;
    }
}
