<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Item;

use DateTimeImmutable;
use DateTimeInterface;

class ItemInformation
{
    private $server;

    private $workableServers;

    private $d1;

    private $d2;

    private $dir;

    private $filesCount;

    private $uniq;

    private $itemSize;

    private $generatedAt;

    public function server(): string
    {
        return $this->server;
    }

    public function workableServers(): array
    {
        return $this->workableServers;
    }

    public function d1(): string
    {
        return $this->d1;
    }

    public function d2(): string
    {
        return $this->d2;
    }

    public function dir(): string
    {
        return $this->dir;
    }

    public function filesCount(): int
    {
        return $this->filesCount;
    }

    public function uniq(): int
    {
        return $this->uniq;
    }

    public function itemSize(): int
    {
        return $this->itemSize;
    }

    public function generatedAt(): DateTimeInterface
    {
        return $this->generatedAt;
    }

    public static function createFromArray(array $itemInformation): self
    {
        $instance = new self();
        $instance->server = $itemInformation['server'];
        $instance->workableServers = $itemInformation['workable_servers'];
        $instance->d1 = $itemInformation['d1'];
        $instance->d2 = $itemInformation['d2'];
        $instance->dir = $itemInformation['dir'];
        $instance->filesCount = $itemInformation['files_count'];
        $instance->uniq = $itemInformation['uniq'];
        $instance->itemSize = $itemInformation['item_size'];
        $instance->generatedAt = DateTimeImmutable::createFromFormat('U', (string) $itemInformation['created']);

        return $instance;
    }
}
