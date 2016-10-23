<?php
declare(strict_types = 1);

namespace Tests\Innmind\XML\Node\Document;

use Innmind\XML\Node\Document\Encoding;

class EncodingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider cases
     */
    public function testInterface($string)
    {
        $encoding = new Encoding($string);

        $this->assertSame($string, (string) $encoding);
    }

    /**
     * @expectedException Innmind\XML\Exception\InvalidArgumentException
     * @dataProvider invalid
     */
    public function testThrowWhenInvalidName($name)
    {
        new Encoding($name);
    }

    public function cases(): array
    {
        return [
            ['unicode-1-1'],
            ['iso-8859-5'],
            ['Shift_JIS'],
            ['ISO_8859-9:1989'],
            ['NF_Z_62-010_(1973)'],
        ];
    }

    public function invalid(): array
    {
        return [
            ['@'],
            ['bar+suffix'],
            ['foo/bar;q=0.8, level=1'],
        ];
    }
}
