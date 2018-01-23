<?php
namespace Chaplin\Controller\Action;

use Zend_Controller_Action;

class Api extends Zend_Controller_Action
{
    final public function init()
    {
        $this->_helper->getHelper('restContextSwitch')
            ->setContext(
                'html',
                [
                 'suffix'    => '',
                 'headers'   => [
                     'Content-Type' => 'text/html; Charset=UTF-8',
                 ]
                ]
            )
            ->addGlobalContext(['html', 'json', 'xml'])
            ->setAutoJsonSerialization(true)
            ->initContext();

        $this->postInit();
    }

    protected function isAPICall()
    {
        return 'json' == $this->_helper
            ->getHelper('restContextSwitch')
            ->getCurrentContext();
    }

    protected function isXml()
    {
        return 'xml' == $this->_helper
            ->getHelper('restContextSwitch')
            ->getCurrentContext();
    }

    protected function forceAPI($arrOut)
    {
        if (!$this->isAPICall()) {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender();
            if ($this->isXml()) {
                // not implemented for now
                return $this->getResponse()->setHttpResponseCode(501);
            }

            echo $this->view->json($arrOut);
            return;
        }

        return $this->view->assign($arrOut);
    }

    protected function postInit()
    {
        // override if required
    }
}
