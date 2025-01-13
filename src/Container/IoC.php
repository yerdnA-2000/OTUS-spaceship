<?php

namespace App\Container;

use App\Exception\IoCException;

/**
 * IoC (Inversion of Control). Used as facade.
 */
class IoC
{
    private static IoC $instance;
    private DependencyBag $dependencyBag;

    public function __construct(DependencyBag $dependencyBag)
    {
        $this->dependencyBag = $dependencyBag;
    }

    /**
     * Устанавливает экземпляр IoC.
     */
    public static function setInstance(IoC $ioc): void
    {
        self::$instance = $ioc;
    }

    /**
     * @throws IoCException
     */
    public static function resolve(string $key, ...$args): mixed
    {
        if (!isset(self::$instance->dependencyBag[$key])) {
            throw new IoCException("No dependency found for key: {$key}");
        }

        return (self::$instance->dependencyBag[$key])(...$args);
    }
}