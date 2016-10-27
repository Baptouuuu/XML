<?php
declare(strict_types = 1);

namespace Tests\Innmind\Xml\Visitor;

use Innmind\Xml\{
    Visitor\Text,
    Reader\Reader,
    Translator\NodeTranslator,
    Translator\NodeTranslators
};
use Innmind\Filesystem\Stream\StringStream;

class TextTest extends \PHPUnit_Framework_TestCase
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
<div>
    <h1>Hey</h1>
    <div>
        <foo />
        whatever
        <bar />
    </div>
    42
</div>
XML;
        $tree = $this->reader->read(
            new StringStream($xml)
        );

        $this->assertSame(
            "\n".
            '    Hey'."\n".
            '    '."\n".
            '        '."\n".
            '        whatever'."\n".
            '        '."\n".
            '    '."\n".
            '    42'."\n",
            (new Text)($tree)
        );
    }
}
