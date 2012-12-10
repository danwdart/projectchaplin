<?php
class CliController extends Zend_Controller_Action
{
    public function preDispatch()
    {
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function cliAction()
    {
        Chaplin_Service::getInstance()
            ->getExchange('Video')
            ->debugMessage();
    }
    
    public function debugAction()
    {
        Chaplin_Service::getInstance()
            ->getExchange('Video')
            ->debug();
    }
}

