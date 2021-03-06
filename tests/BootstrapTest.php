<?php
declare(strict_types = 1);

namespace Tests\Innmind\Xml;

use function Innmind\Xml\bootstrap;
use Innmind\Xml\{
    Reader\Reader,
    Reader\Cache,
};
use PHPUnit\Framework\TestCase;

class BootstrapTest extends TestCase
{
    public function testBootstrap()
    {
        $services = bootstrap();
        $reader = $services['reader'];
        $cache = $services['cache'];
        $storage = $services['cache_storage'];

        $this->assertInstanceOf(Reader::class, $reader);
        $this->assertIsCallable($cache);
        $this->assertInstanceOf(Cache::class, $cache($reader));
        $this->assertInstanceOf(Cache\Storage::class, $storage);
    }
}
