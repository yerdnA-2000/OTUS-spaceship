<?php

namespace App\Tests\Plugin;

use App\Container\IoC;
use App\Exception\IoCException;
use App\Plugin\RotatePlugin;
use App\Rotate\RotatableInterface;
use App\Rotate\RotateCommand;
use App\Tests\TestCase\BaseIoCUsingTestTrait;
use PHPUnit\Framework\TestCase;

class RotatePluginTest extends TestCase
{
    use BaseIoCUsingTestTrait;

    protected function setUp(): void
    {
        $this->initIoC();
    }

    /**
     * @throws IoCException
     */
    public function testLoadPluginAndResolve(): void
    {
        (new RotatePlugin())->load();

        $rotatable = self::createMock(RotatableInterface::class);

        $rotateCommand = IoC::resolve('rotate', $rotatable);
        self::assertInstanceOf(RotateCommand::class, $rotateCommand);
    }
}