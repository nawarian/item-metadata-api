<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Item;

use Webmozart\Assert\Assert;

class Identifier
{
    private $identifier;

    private function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    public function __toString(): string
    {
        return $this->identifier;
    }

    public static function newFromIdentifierString(string $identifier): self
    {
        Assert::minLength($identifier, 1, 'The minimum length on an identifier is 1 character.');
        Assert::maxLength($identifier, 100, 'The maximum length of an identifier is 100 characters.');
        Assert::regex(
            $identifier,
            '#^[\.a-zA-Z0-9_-]+$#',
            <<<ERROR
Identifier must contain only alphanumeric characters (limited to ASCII), underscores (_), dashes (-), or periods (.).
ERROR
        );
        Assert::alnum(
            $identifier[0],
            <<<ERROR
First character of an identifier must be alphanumeric (e.g. it cannot start out with an underscore, dash, or period).
ERROR
        );

        return new self($identifier);
    }
}
