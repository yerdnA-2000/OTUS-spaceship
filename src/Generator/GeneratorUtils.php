<?php

namespace App\Generator;

use ReflectionMethod;
use ReflectionType;

class GeneratorUtils
{
    public static function isVoidMethod(ReflectionMethod $method): bool
    {
        $returnType = $method->getReturnType();

        return 'void' === $returnType->getName();
    }
    
    public static function getParametersString(ReflectionMethod $method, bool $withTypes = true): string
    {
        $params = $method->getParameters();
        $result = '';

        foreach ($params as $param) {
            $type = $withTypes ? self::getTypeString($param->getType()) : '';

            $result .= sprintf('%s $%s,', $type, $param->getName());
        }

        return $result;
    }

    public static function getTypeString(ReflectionType $type): string
    {
        $name = $type->getName();
        $name = $type->isBuiltin() ? $name : '\\' . $name;

        return $type->allowsNull() ? '?' . $name : $name;
    }

    public static function getReturnTypeString(ReflectionMethod $method): string
    {
        $returnType = $method->getReturnType();

        return GeneratorUtils::getTypeString($returnType);
    }
}