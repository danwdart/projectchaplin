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
         ->addGlobalContext(['html', 'json'])
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

 
    protected function _postInit()
    {
        // override if required
    }
}