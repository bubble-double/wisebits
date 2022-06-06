<?php

namespace App\WiseBits\Helpers;

class ObjectHelper
{
    /**
     * Return class name without namespace
     * Example: ObjectHelper::getClassNameWithoutNamespace(ObjectHelper::class);
     *
     * @param string $className
     *
     * @return string
     */
    public static function getClassNameWithoutNamespace(string $className): string
    {
        return \substr(\strrchr($className, "\\"), 1);
    }
}
