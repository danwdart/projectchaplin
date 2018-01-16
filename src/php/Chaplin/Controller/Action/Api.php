<?php
class Chaplin_Controller_Action_Api
    extends Zend_Controller_Action
{
    final public function init()
    {
        $this->_helper->getHelper('restContextSwitch')
            ->setContext(
                'html', [
                 'suffix'    => '',
                 'headers'   => [
                     'Content-Type' => 'text/html; Charset=UTF-8',
                 ]
                ]
            )
            ->addGlobalContext(['html', 'json', 'xml'])
            ->setAutoJsonSerialization(true)
            ->initContext();

        $this->_postInit();
    }
    
    protected function _isAPICall()
    {
        return 'json' == $this->_helper
            ->getHelper('restContextSwitch')
            ->getCurrentContext();
    }

    protected function _isXml()
    {
        return 'xml' == $this->_helper
            ->getHelper('restContextSwitch')
            ->getCurrentContext();
    }

    protected function _forceAPI($arrOut)
    {
        if (!$this->_isAPICall()) {
            $this->_helper->layout()->disableLayout();
            $this->_helper->viewRenderer->setNoRender();
            if ($this->_isXml()) {
                // not implemented for now
                return $this->getResponse()->setHttpResponseCode(501);
            }

            echo $this->view->json($arrOut);
            return;
        }

        return $this->view->assign($arrOut);
    }

    protected function _postInit()
    {
        // override if required
    }
}