<?php

namespace App\Container;

use App\Exception\IoCException;
use Closure;

/**
 * IoC (Inversion of Control)
 */
class IoC
{
    /**
     * Хранит ассоциации между ключами и замыканиями для создания объектов.
     */
    private array $bindings = [];

    /**
     * @throws IoCException
     */
    public function resolve(string $key, ...$args)
    {
        if (!isset($this->bindings[$key])) {
            throw new IoCException("No binding found for key: {$key}");
        }

        return ($this->bindings[$key])(...$args);
    }

    /**
     * Позволяет зарегистрировать зависимость по ключу с помощью замыкания.
     */
    public function register(string $key, Closure $resolver): void
    {
        $this->bindings[$key] = $resolver;
    }

    public function unregister(string $key): void
    {
        if (isset($this->bindings[$key])) {
            unset($this->bindings[$key]);
        }
    }

    public function loadPlugins(array $plugins): void
    {
        foreach ($plugins as $plugin) {
            if (method_exists($plugin, 'register')) {
                $plugin->register($this);
            }
        }
    }
}