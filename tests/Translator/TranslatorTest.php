<?php
declare(strict_types = 1);

namespace Tests\Innmind\Xml\Translator;

use Innmind\Xml\{
    Translator\Translator,
    Translator\NodeTranslators,
    Translator\NodeTranslator,
    Element\Element,
    Element\SelfClosingElement,
    Node\Document,
    Node\Text,
    Node\CharacterData,
    Node\Comment,
};
use Innmind\Immutable\Map;
use PHPUnit\Framework\TestCase;

class TranslatorTest extends TestCase
{
    private $translator;

    public function setUp()
    {
        $this->translator = new Translator(
            NodeTranslators::defaults()
        );
    }

    public function testTranslate()
    {
        $document = new \DOMDocument;
        $document->loadXML($xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<foo bar="baz">
    <foobar/>
    <div>
        <![CDATA[whatever]]>
    </div>
    <!--foobaz-->
    hey!
</foo>
XML
        );
        $node = $this->translator->translate($document);

        $this->assertInstanceOf(Document::class, $node);
        $this->assertSame('1.0', (string) $node->version());
        $this->assertSame('utf-8', (string) $node->encoding());
        $this->assertSame('html', $node->type()->name());
        $this->assertSame(
            '-//W3C//DTD HTML 4.01//EN',
            $node->type()->publicId()
        );
        $this->assertSame(
            'http://www.w3.org/TR/html4/strict.dtd',
            $node->type()->systemId()
        );
        $this->assertCount(1, $node->children());
        $foo = $node->children()->current();
        $this->assertInstanceOf(Element::class, $foo);
        $this->assertSame('foo', $foo->name());
        $this->assertCount(1, $foo->attributes());
        $this->assertSame('baz', $foo->attribute('bar')->value());
        $this->assertCount(7, $foo->children());
        $linebreak = $foo->children()->get(0);
        $this->assertInstanceOf(Text::class, $linebreak);
        $this->assertSame("\n    ", $linebreak->content());
        $foobar = $foo->children()->get(1);
        $this->assertInstanceOf(SelfClosingElement::class, $foobar);
        $this->assertSame('foobar', $foobar->name());
        $linebreak = $foo->children()->get(2);
        $this->assertInstanceOf(Text::class, $linebreak);
        $this->assertSame("\n    ", $linebreak->content());
        $div = $foo->children()->get(3);
        $this->assertInstanceOf(Element::class, $div);
        $this->assertSame('div', $div->name());
        $this->assertFalse($div->hasAttributes());
        $this->assertCount(3, $div->children());
        $linebreak = $div->children()->get(0);
        $this->assertInstanceOf(Text::class, $linebreak);
        $this->assertSame("\n        ", $linebreak->content());
        $cdata = $div->children()->get(1);
        $this->assertInstanceOf(CharacterData::class, $cdata);
        $this->assertSame('whatever', $cdata->content());
        $linebreak = $div->children()->get(2);
        $this->assertInstanceOf(Text::class, $linebreak);
        $this->assertSame("\n    ", $linebreak->content());
        $linebreak = $foo->children()->get(4);
        $this->assertInstanceOf(Text::class, $linebreak);
        $this->assertSame("\n    ", $linebreak->content());
        $comment = $foo->children()->get(5);
        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertSame('foobaz', $comment->content());
        $text = $foo->children()->get(6);
        $this->assertInstanceOf(Text::class, $text);
        $this->assertSame("\n    hey!\n", $text->content());
        $this->assertSame($xml, (string) $node);
    }

    /**
     * @expectedException Innmind\Xml\Exception\UnknownNodeType
     */
    public function testThrowWhenNoTranslatorFoundForANodeType()
    {
        (new Translator(
            new Map('int', NodeTranslator::class)
        ))->translate(new \DOMDocument);
    }
}
