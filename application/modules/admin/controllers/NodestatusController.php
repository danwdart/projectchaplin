<?php
class Admin_NodestatusController extends Zend_Controller_Action
{
    public function init()
    {
        $contextSwitch = $this->_helper->getHelper('contextSwitch');
        $contextSwitch->addActionContext('index', 'json')
                      ->initContext();
    }

    public function indexAction()
    {
        $this->view->assign(array('version' => 0.1));
    }
}
