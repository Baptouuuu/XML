<?php
declare(strict_types = 1);

namespace Tests\Innmind\Xml\Translator\NodeTranslator\Visitor;

use Innmind\Xml\{
    Translator\NodeTranslator\Visitor\Children,
    Translator\Translator,
    Translator\NodeTranslators,
    Node,
};
use Innmind\Immutable\Sequence;
use PHPUnit\Framework\TestCase;

class ChildrenTest extends TestCase
{
    public function testNoChildren()
    {
        $document = new \DOMDocument;
        $document->loadXML('<root></root>');

        $children = (new Children(
            new Translator(
                NodeTranslators::defaults()
            )
        ))($document->childNodes->item(0));

        $this->assertInstanceOf(Sequence::class, $children);
        $this->assertSame(Node::class, $children->type());
        $this->assertCount(0, $children);
    }

    public function testChildren()
    {
        $document = new \DOMDocument;
        $document->loadXML('<root><foo/><bar/></root>');

        $children = (new Children(
            new Translator(
                NodeTranslators::defaults()
            )
        ))($document->childNodes->item(0));

        $this->assertInstanceOf(Sequence::class, $children);
        $this->assertSame(Node::class, $children->type());
        $this->assertCount(2, $children);
    }
}
