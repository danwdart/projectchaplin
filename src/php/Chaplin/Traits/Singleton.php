<?php
declare(strict_types=1);

namespace Chaplin\Traits;

trait Singleton
{
    private static $_instance;

    private function __clone()
    {
    }

    private function __construct()
    {
    }

    public static function __callStatic($strMethod, array $arrArgs)
    {
        return call_user_func_array(
            [self::getInstance(), $strMethod],
            $arrArgs
        );
    }

    public static function getInstance() : self
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}
