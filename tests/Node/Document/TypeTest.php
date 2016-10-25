<?php
declare(strict_types = 1);

namespace Tests\Innmind\XML\Node\Document;

use Innmind\XML\Node\Document\Type;

class TypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider cases
     */
    public function testInterface($name, $public, $system, $string)
    {
        $type = new Type($name, $public, $system);

        $this->assertSame($name, $type->name());
        $this->assertSame($public, $type->publicId());
        $this->assertSame($system, $type->systemId());
        $this->assertSame($string, (string) $type);
    }

    /**
     * @expectedException Innmind\XML\Exception\InvalidArgumentException
     */
    public function testThrowWhenEmptyName()
    {
        new Type('');
    }

    public function cases(): array
    {
        return [
            ['foo', '', '', '<!DOCTYPE foo>'],
            ['foo', 'bar', '', '<!DOCTYPE foo PUBLIC "bar">'],
            ['foo', 'bar', 'baz', '<!DOCTYPE foo PUBLIC "bar" "baz">'],
            ['foo', '', 'baz', '<!DOCTYPE foo "baz">'],
        ];
    }
}