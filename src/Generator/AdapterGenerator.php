<?php

namespace App\Generator;

use App\Container\IoC;
use ReflectionClass;
use ReflectionMethod;
use Exception;

class AdapterGenerator
{
    /**
     * Генерирует адаптер для заданного интерфейса.
     *
     * @param string $interfaceName Имя интерфейса.
     * @param string $className Имя класса, который будет адаптироваться.
     * @return string Код сгенерированного адаптера.
     * @throws Exception
     */
    public static function generateAdapter(string $interfaceName, string $className): string
    {
        if (!interface_exists($interfaceName)) {
            throw new Exception("Interface {$interfaceName} does not exist.");
        }

        $reflection = new ReflectionClass($interfaceName);
        $adapterClass = $reflection->getShortName() . "Adapter";

        $methods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $methods[] = self::generateMethod($method, $className);
        }

        return self::generateClass($interfaceName, $adapterClass, $className, implode("\n", $methods));
    }

    private static function generateMethod(ReflectionMethod $method, string $className): string
    {
        $methodName = $method->getName();
        $paramsWithTypes = GeneratorUtils::getParametersString($method);
        $paramsWithoutTypes = GeneratorUtils::getParametersString($method, false);
        $returnType = GeneratorUtils::getReturnTypeString($method);
        $rowStart = GeneratorUtils::isVoidMethod($method) ? '' : 'return ';

        return "
                public function {$methodName}({$paramsWithTypes}): {$returnType}
                {
                    {$rowStart}IoC::resolve('{$className}:{$methodName}', \$this->obj, {$paramsWithoutTypes});
                }
        ";
    }

    private static function generateClass(string $interfaceName, string $adapterClass, string $className, string $methods): string
    {
        $iocClass = IoC::class;

        return "<?php
            namespace App\\AutoGenerated;

            use {$iocClass};

            class {$adapterClass} implements \\{$interfaceName} 
            {
                private \\{$className} \$obj;

                public function __construct(\\{$className} \$obj) 
                {
                    \$this->obj = \$obj;
                }

                {$methods}
            }
        ";
    }

    /**
     * Создает экземпляр адаптера для заданного интерфейса и класса.
     *
     * @param string $interfaceName Имя интерфейса.
     * @param object $obj Объект, который будет передан в адаптер.
     * @return object Экземпляр сгенерированного адаптера.
     * @throws Exception
     */
    public static function createAdapter(string $interfaceName, object $obj): object
    {
        $className = get_class($obj);

        $adapterCode = self::generateAdapter($interfaceName, $className);

        // Компиляция и создание класса на лету
        eval("?>$adapterCode");

        // Создание экземпляра сгенерированного адаптера
        return new ("App\\AutoGenerated\\" . (new ReflectionClass($interfaceName))->getShortName() . "Adapter")($obj);
    }
}