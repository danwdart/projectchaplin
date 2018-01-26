<?php
declare(strict_types=1);

namespace Chaplin\Traits;

trait MultiSingleton
{
    private static $arrInstances = [];

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
        if (isset(self::$arrInstances[$strClass])) {
            return self::$arrInstances[$strClass];
        }

        $instance = new $strClass();
        self::$arrInstances[$strClass] = $instance;
        return $instance;
    }
}
