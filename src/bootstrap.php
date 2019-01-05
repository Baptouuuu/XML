<?php
declare(strict_types = 1);

namespace Innmind\Xml;

use Innmind\Xml\{
    Reader\Reader,
    Reader\Cache,
    Translator\NodeTranslator,
    Translator\NodeTranslators,
};

function bootstrap(): array {
    return [
        'reader' => new Reader(
            new NodeTranslator(
                NodeTranslators::defaults()
            )
        ),
        'cache_storage' => $storage = new Cache\Storage,
        'cache' => static function(ReaderInterface $reader) use ($storage): ReaderInterface {
            return new Cache($reader, $storage);
        },
    ];
}
