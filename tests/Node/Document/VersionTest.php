<?php
declare(strict_types = 1);

namespace Tests\Innmind\XML\Node\Document;

use Innmind\XML\Node\Document\Version;

class VersionTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $version = new Version(2, 1);

        $this->assertSame(2, $version->major());
        $this->assertSame(1, $version->minor());
        $this->assertSame('2.1', (string) $version);

        $this->assertSame('1.0', (string) new Version(1));
    }

    /**
     * @expectedException Innmind\XML\Exception\InvalidArgumentException
     */
    public function testThrowWhenMajorTooLow()
    {
        new Version(-1);
    }

    /**
     * @expectedException Innmind\XML\Exception\InvalidArgumentException
     */
    public function testThrowWhenMinorTooLow()
    {
        new Version(1, -1);
    }
}