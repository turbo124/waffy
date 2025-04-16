<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Turbo124\Waffy\Deny;

#[CoversClass(Deny::class)]
final class FileTest extends TestCase
{

    protected function setUp(): void
    {
    }

    public function testDenyPathExists()
    {
        $deny = new Deny();

        $this->assertIsString($deny->getDenyPath());
    }

    public function testDenyPathIsWritable()
    {
        $deny = new Deny();

        $this->assertTrue(is_writable($deny->getDenyPath()));
    }

    public function testDenyPathIsReadable()
    {
        $deny = new Deny();

        $this->assertTrue(is_readable($deny->getDenyPath()));
    }
    
    public function testAddDeny()
    {
        $deny = new Deny();

        $deny->addDeny('192.1.1.1');

        $this->assertStringContainsString('deny 192.1.1.1;', file_get_contents($deny->getDenyPath()));
    }
    
    public function testAddDenySecondEntry()
    {
        $deny = new Deny();

        $deny->addDeny('192.1.1.2');

        $this->assertStringContainsString('deny 192.1.1.2', file_get_contents($deny->getDenyPath()));
    }
    
}
