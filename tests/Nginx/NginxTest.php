<?php

namespace Turbo124\Waffy\Tests\Nginx;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Turbo124\Waffy\Nginx;

#[CoversClass(Nginx::class)]
final class NginxTest extends TestCase
{

    protected function setUp(): void
    {
    }

    public function testNginxConfigTest()
    {
        $this->assertTrue(Nginx::test());
    }

    public function testNginxReload()
    {
        $this->assertTrue(Nginx::reload());
    }
}