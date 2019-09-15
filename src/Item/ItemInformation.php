<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Item;

class ItemInformation
{
    private $server;

    private $workableServers;

    private $d1;

    private $d2;

    private $dir;

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

    public static function createFromArray(array $itemInformation): self
    {
        $instance = new self();
        $instance->server = $itemInformation['server'];
        $instance->workableServers = $itemInformation['workable_servers'];
        $instance->d1 = $itemInformation['d1'];
        $instance->d2 = $itemInformation['d2'];
        $instance->dir = $itemInformation['dir'];

        return $instance;
    }
}
