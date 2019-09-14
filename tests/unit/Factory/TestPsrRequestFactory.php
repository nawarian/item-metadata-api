<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Factory;

use ArchiveOrg\ItemMetadata\Item\Identifier;
use Mockery;
use Psr\Http\Message\RequestInterface;

class TestPsrRequestFactory implements PsrRequestFactory
{
    public function newMetadataRequest(Identifier $identifier): RequestInterface
    {
        $fakeRequest = Mockery::mock(RequestInterface::class);
        $fakeRequest->shouldReceive('getMethod')->andReturn('GET');

        $uri = "https://archive.org/metadata/{$identifier}/metadata";
        $fakeRequest->shouldReceive('getUri')->andReturn($uri);

        return $fakeRequest;
    }

    public function newFilesRequest(Identifier $identifier): RequestInterface
    {
        $fakeRequest = Mockery::mock(RequestInterface::class);
        $fakeRequest->shouldReceive('getMethod')->andReturn('GET');

        $uri = "https://archive.org/metadata/{$identifier}/files";
        $fakeRequest->shouldReceive('getUri')->andReturn($uri);

        return $fakeRequest;
    }
}
