<?php

declare(strict_types=1);

namespace BEAR\QiqModule;

use PHPUnit\Framework\TestCase;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

use function assert;
use function dirname;
use function serialize;
use function unserialize;

class QiqRendererTest extends TestCase
{
    private AbstractModule $module;

    protected function setUp(): void
    {
        $qiqTemplateDir = dirname(__DIR__) . '/tests/Fake/templates';
        $this->module = new QiqModule($qiqTemplateDir);
        parent::setUp();
    }

    public function testRender(): void
    {
        $ro = (new Injector($this->module))->getInstance(FakeRo::class);
        assert($ro instanceof FakeRo);
        $ro = $ro->onGet(['name' => 'World']);
        $view = (string) $ro;
        $this->assertSame('Hello, World. That was Qiq! And this is PHP, World.
', $view);
    }

    public function testCacheRender(): FakeRo
    {
        $cachePath = __DIR__ . '/tmp';
        $this->module->install(new QiqProdModule($cachePath));
        $ro = (new Injector($this->module))->getInstance(FakeRo::class);
        assert($ro instanceof FakeRo);
        $ro = $ro->onGet(['name' => 'World']);
        $view = (string) $ro;
        $this->assertSame('Hello, World. That was Qiq! And this is PHP, World.
', $view);

        return $ro;
    }

    /**
     * @depends testCacheRender
     */
    public function testSerialize(FakeRo $ro): void
    {
        $ro = unserialize(serialize($ro));
        $this->assertInstanceOf(FakeRo::class, $ro);
    }
}
