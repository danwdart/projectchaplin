<?php


namespace Chaplin;

use Zend_Controller_Front;

class Log
{
    public static function getInstance()
    {
        return Zend_Controller_Front::getInstance()
         ->getParam('bootstrap')
         ->getResource('log');
    }
}
