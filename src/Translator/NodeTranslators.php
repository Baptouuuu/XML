<?php
declare(strict_types = 1);

namespace Innmind\Xml\Translator;

use Innmind\Xml\Translator\NodeTranslator\{
    DocumentTranslator,
    ElementTranslator,
    CharacterDataTranslator,
    CommentTranslator,
    TextTranslator,
    EntityReferenceTranslator,
};
use Innmind\Immutable\{
    MapInterface,
    Map,
};

final class NodeTranslators
{
    private static $defaults;

    /**
     * @return MapInterface<int, NodeTranslatorInterface>
     */
    public static function defaults(): MapInterface
    {
        if (!self::$defaults) {
            self::$defaults = Map::of('int', NodeTranslator::class)
                (XML_DOCUMENT_NODE, new DocumentTranslator)
                (XML_ELEMENT_NODE, new ElementTranslator)
                (XML_CDATA_SECTION_NODE, new CharacterDataTranslator)
                (XML_TEXT_NODE, new TextTranslator)
                (XML_COMMENT_NODE, new CommentTranslator)
                (XML_ENTITY_REF_NODE, new EntityReferenceTranslator);
        }

        return self::$defaults;
    }
}
