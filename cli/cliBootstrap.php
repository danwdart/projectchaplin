<?php
class cliBootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Chaplin_');
        $autoloader->registerNamespace('Amqp_');
        $autoloader->registerNamespace('Mongo_');
        $autoloader->registerNamespace('Mustache_');
    }

    protected function _initSmtp()
    {
        $configSmtp = Chaplin_Config_Servers::getInstance();
        $arrSmtp = $configSmtp->getSmtpSettings();
        $transport = new Zend_Mail_Transport_Smtp(
            $arrSmtp['server']['host'],
            $arrSmtp['server']['options']
        );
        Zend_Mail::setDefaultTransport($transport);
    }

    protected function _bootstrap($resource = null)
    {
        try {
            parent::_bootstrap($resource);
        } catch(Exception $e) {
            echo $e->getException();
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

