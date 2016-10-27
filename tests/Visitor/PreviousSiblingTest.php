<?php
declare(strict_types = 1);

namespace Tests\Innmind\Xml\Visitor;

use Innmind\Xml\{
    Visitor\PreviousSibling,
    Reader\Reader,
    Element\Element,
    Translator\NodeTranslator,
    Translator\NodeTranslators
};
use Innmind\Filesystem\Stream\StringStream;

class PreviousSiblingTest extends \PHPUnit_Framework_TestCase
{
    private $reader;

    public function setUp()
    {
        $this->reader = new Reader(
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        );
    }

    public function testInterface()
    {
        $xml = <<<XML
<div><foo /><baz /><bar /></div>
XML;
        $tree = $this->reader->read(
            new StringStream($xml)
        );
        $div = $tree
            ->children()
            ->get(0);
        $bar = $div
            ->children()
            ->get(2);
        $baz = $div
            ->children()
            ->get(1);

        $this->assertSame(
            $baz,
            (new PreviousSibling($bar))($tree)
        );
    }

    /**
     * @expectedException Innmind\Xml\Exception\NoPreviousSiblingException
     */
    public function testThrowWhenNoPreviousSibling()
    {
        $xml = <<<XML
<div><foo /><baz /><bar /></div>
XML;
        $tree = $this->reader->read(
            new StringStream($xml)
        );
        $div = $tree
            ->children()
            ->get(0);
        $foo = $div
            ->children()
            ->get(0);

        (new PreviousSibling($foo))($tree);
    }
}