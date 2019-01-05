<?php
declare(strict_types = 1);

namespace Tests\Innmind\Xml\Translator\NodeTranslator;

use Innmind\Xml\{
    Translator\NodeTranslator\DocumentTranslator,
    Translator\NodeTranslator,
    Translator\Translator,
    Node\Document,
    Node,
    Element\SelfClosingElement,
};
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class DocumentTranslatorTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            NodeTranslator::class,
            new DocumentTranslator
        );
    }

    public function testTranslate()
    {
        $document = new \DOMDocument;
        $document->loadXML($xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html>
<foo/>
XML
        );

        $translator = new DocumentTranslator;
        $foo = new SelfClosingElement('foo');
        $node = $translator->translate(
            $document,
            new Translator(
                Map::of('int', NodeTranslator::class)
                    (
                        XML_ELEMENT_NODE,
                        new class($foo) implements NodeTranslator
                        {
                            private $foo;

                            public function __construct(Node $foo)
                            {
                                $this->foo = $foo;
                            }

                            public function translate(\DOMNode $node, Translator $translator): Node
                            {
                                return $this->foo;
                            }
                        }
                    )
            )
        );

        $this->assertInstanceOf(Document::class, $node);
        $this->assertSame($xml, (string) $node);
    }

    /**
     * @expectedException Innmind\Xml\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidNode()
    {
        (new DocumentTranslator)->translate(
            new \DOMNode,
            new Translator(
                new Map('int', NodeTranslator::class)
            )
        );
    }
}
