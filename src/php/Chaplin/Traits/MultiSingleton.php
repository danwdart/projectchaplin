<?php
declare(strict_types=1);

namespace Chaplin\Traits;

trait MultiSingleton
{
    private static $_arrInstances = [];

    private function __clone()
    {
    }

    private function __construct()
    {
    }

    public static function __callStatic($strMethod, array $arrArgs)
    {
        $strClass = get_called_class();

        return call_user_func_array(
            [$strClass::getInstance(), $strMethod],
            $arrArgs
        );
    }

    public static function getInstance()
    {
        $strClass = get_called_class();
        if (isset(self::$_arrInstances[$strClass])) {
            return self::$_arrInstances[$strClass];
        }

        $instance = new $strClass();
        self::$_arrInstances[$strClass] = $instance;
        return $instance;
    }
}
