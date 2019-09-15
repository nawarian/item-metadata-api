<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Item;

use DateTimeImmutable;
use DateTimeInterface;

final class File
{
    private $name;

    private $source;

    private $lastModified;

    private $size;

    private $format;

    private $md5;

    private $crc32;

    private $sha1;

    public function name(): string
    {
        return $this->name;
    }

    public function source(): string
    {
        return $this->source;
    }

    public function lastModified(): ?DateTimeInterface
    {
        return $this->lastModified;
    }

    public function size(): ?int
    {
        return $this->size;
    }

    public function format(): string
    {
        return $this->format;
    }

    public function md5(): string
    {
        return $this->md5;
    }

    public function crc32(): ?string
    {
        return $this->crc32;
    }

    public function sha1(): ?string
    {
        return $this->sha1;
    }

    public static function createFromArray(array $file): self
    {
        $lastModified = null;
        if (array_key_exists('mtime', $file)) {
            $lastModified = DateTimeImmutable::createFromFormat('U', $file['mtime']);
        }

        $size = null;
        if (array_key_exists('size', $file)) {
            $size = (int) $file['size'];
        }

        $instance = new self();

        $instance->name = $file['name'];
        $instance->source = $file['source'];
        $instance->format = $file['format'];
        $instance->md5 = $file['md5'];
        $instance->crc32 = $file['crc32'] ?? null;
        $instance->sha1 = $file['sha1'] ?? null;
        $instance->lastModified = $lastModified;
        $instance->size = $size;

        return $instance;
    }
}
