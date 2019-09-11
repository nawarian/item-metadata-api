<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata;

use ArchiveOrg\ItemMetadata\Exceptions\ItemNotFoundException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Client
{
    private const METADATA_URL = 'https://archive.org/metadata/%s/metadata';

    private $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getMetadataByIdentifier(string $identifier): Metadata
    {
        $response = $this->httpClient->sendRequest($this->createGetMetadataByIdentifierRequest($identifier));

        if (404 === $response->getStatusCode()) {
            throw new ItemNotFoundException("Item '{$identifier}' not found.");
        }

        $parsedResult = json_decode($response->getBody()->getContents(), true)['result'] ?? [];

        return new Metadata($parsedResult ?? []);
    }

    private function createGetMetadataByIdentifierRequest(string $identifier): RequestInterface
    {
        return new class($identifier) implements RequestInterface {
            private $identifier;

            public function __construct(string $identifier)
            {
                $this->identifier = $identifier;
            }

            public function getProtocolVersion()
            {
                // TODO: Implement getProtocolVersion() method.
            }

            public function withProtocolVersion($version)
            {
                // TODO: Implement withProtocolVersion() method.
            }

            public function getHeaders()
            {
                // TODO: Implement getHeaders() method.
            }

            public function hasHeader($name)
            {
                // TODO: Implement hasHeader() method.
            }

            public function getHeader($name)
            {
                // TODO: Implement getHeader() method.
            }

            public function getHeaderLine($name)
            {
                // TODO: Implement getHeaderLine() method.
            }

            public function withHeader($name, $value)
            {
                // TODO: Implement withHeader() method.
            }

            public function withAddedHeader($name, $value)
            {
                // TODO: Implement withAddedHeader() method.
            }

            public function withoutHeader($name)
            {
                // TODO: Implement withoutHeader() method.
            }

            public function getBody()
            {
                // TODO: Implement getBody() method.
            }

            public function withBody(StreamInterface $body)
            {
                // TODO: Implement withBody() method.
            }

            public function getRequestTarget()
            {
                // TODO: Implement getRequestTarget() method.
            }

            public function withRequestTarget($requestTarget)
            {
                // TODO: Implement withRequestTarget() method.
            }

            public function getMethod()
            {
                return 'GET';
            }

            public function withMethod($method)
            {
                // TODO: Implement withMethod() method.
            }

            public function getUri()
            {
                return new class($this->identifier) implements UriInterface {
                    private $identifier;

                    public function __construct(string $identifier)
                    {
                        $this->identifier = $identifier;
                    }

                    public function getScheme()
                    {
                        // TODO: Implement getScheme() method.
                    }

                    public function getAuthority()
                    {
                        // TODO: Implement getAuthority() method.
                    }

                    public function getUserInfo()
                    {
                        // TODO: Implement getUserInfo() method.
                    }

                    public function getHost()
                    {
                        // TODO: Implement getHost() method.
                    }

                    public function getPort()
                    {
                        // TODO: Implement getPort() method.
                    }

                    public function getPath()
                    {
                        // TODO: Implement getPath() method.
                    }

                    public function getQuery()
                    {
                        // TODO: Implement getQuery() method.
                    }

                    public function getFragment()
                    {
                        // TODO: Implement getFragment() method.
                    }

                    public function withScheme($scheme)
                    {
                        // TODO: Implement withScheme() method.
                    }

                    public function withUserInfo($user, $password = null)
                    {
                        // TODO: Implement withUserInfo() method.
                    }

                    public function withHost($host)
                    {
                        // TODO: Implement withHost() method.
                    }

                    public function withPort($port)
                    {
                        // TODO: Implement withPort() method.
                    }

                    public function withPath($path)
                    {
                        // TODO: Implement withPath() method.
                    }

                    public function withQuery($query)
                    {
                        // TODO: Implement withQuery() method.
                    }

                    public function withFragment($fragment)
                    {
                        // TODO: Implement withFragment() method.
                    }

                    public function __toString()
                    {
                        return sprintf('https://archive.org/metadata/%s/metadata', $this->identifier);
                    }
                };
            }

            public function withUri(UriInterface $uri, $preserveHost = false)
            {
                // TODO: Implement withUri() method.
            }
        };
    }
}
