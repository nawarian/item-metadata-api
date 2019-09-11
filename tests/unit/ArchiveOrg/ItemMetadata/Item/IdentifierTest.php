<?php

declare(strict_types=1);

namespace ArchiveOrg\ItemMetadata\Item;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class IdentifierTest extends TestCase
{
    public function testNewFromIdentifierString(): void
    {
        $this->assertEquals('nawarian-test', Identifier::newFromIdentifierString('nawarian-test'));
    }

    /**
     * @dataProvider newFromIdentifierStringWithInvalidIdentifiersDataProvider
     */
    public function testNewFromIdentifierStringWithInvalidIdentifiers(string $invalidIdentifier, string $error): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($error);

        Identifier::newFromIdentifierString($invalidIdentifier);
    }

    public function newFromIdentifierStringWithInvalidIdentifiersDataProvider(): array
    {
        $alphaNumericError = <<<ERROR
Identifier must contain only alphanumeric characters (limited to ASCII), underscores (_), dashes (-), or periods (.).
ERROR;

        $firstCharacterAlphanumericError = <<<ERROR
First character of an identifier must be alphanumeric (e.g. it cannot start out with an underscore, dash, or period).
ERROR;

        $minLengthError = 'The minimum length on an identifier is 1 character.';
        $maxLengthError = 'The maximum length of an identifier is 100 characters.';

        return [
            ['', $minLengthError],
            ['fails:alphanumeric', $alphaNumericError],
            ['_first_alphanumeric', $firstCharacterAlphanumericError],
            [str_repeat('n', 101), $maxLengthError],
        ];
    }
}
