<?php
declare(strict_types = 1);

namespace Innmind\Xml\Node\Document;

use Innmind\Xml\Exception\InvalidArgumentException;
use Innmind\Immutable\Str;

final class Encoding
{
    private $string;

    public function __construct(string $string)
    {
        if (!Str::of($string)->matches('~^[a-zA-Z0-9\-_:\(\)]+$~')) {
            throw new InvalidArgumentException;
        }

        $this->string = $string;
    }

    public function __toString(): string
    {
        return $this->string;
    }
}
