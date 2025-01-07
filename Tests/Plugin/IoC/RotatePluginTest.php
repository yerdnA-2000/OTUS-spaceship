<?php

namespace App\Tests\Plugin\IoC;

use App\Container\IoC;
use App\Exception\IoCException;
use App\Plugin\IoC\RotatePlugin;
use App\Rotate\RotatableInterface;
use App\Rotate\RotateCommand;
use PHPUnit\Framework\TestCase;

class RotatePluginTest extends TestCase
{
    private IoC $ioc;

    protected function setUp(): void
    {
        $this->ioc = new IoC();

        $this->ioc->loadPlugins([new RotatePlugin()]);
    }

    /**
     * @throws IoCException
     */
    public function testLoadPluginsAndResolve(): void
    {
        $rotatable = self::createMock(RotatableInterface::class);

        $rotateCommand = $this->ioc->resolve('rotate', $rotatable);
        self::assertInstanceOf(RotateCommand::class, $rotateCommand);
    }
}