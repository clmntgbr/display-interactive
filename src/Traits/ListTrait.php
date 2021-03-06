<?php

namespace App\Traits;

use ReflectionClass;

trait ListTrait
{
    public static function getConstantsList(): array
    {
        $class = new ReflectionClass(__CLASS__);
        $constants = $class->getConstants();
        $result = [];

        foreach ($constants as $constant) {
            $result[uniqid()] = $constant;

        }

        return $result;
    }
}
