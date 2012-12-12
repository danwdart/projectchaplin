<?php
class cliBootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Chaplin_');
        $autoloader->registerNamespace('Amqp_');
        $autoloader->registerNamespace('Mongo_');
        $autoloader->registerNamespace('FFMpeg\\');
        $autoloader->registerNamespace('Monolog\\');
    }

    protected function _bootstrap($resource = null)
    {
        try {
            parent::_bootstrap($resource);
        } catch(Exception $e) {
            echo $e->getException();
            ob_flush();
            flush();
        }
    }
    public function run()
    {
        try {
            parent::run();
        } catch(Exception $e) {
            echo $e->getException();
            ob_flush();
            flush();
        }
    }
}

