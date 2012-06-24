<?php
class Admin_NodeController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->view->ittNodes = Chaplin_Gateway::getInstance()
            ->getNode()
            ->getAllNodes();
    }
    
    public function createAction()
    {
        $form = new Admin_Form_Node_Create();
        if(!$this->_request->isPost()) {
            return $this->view->form = $form;
        }
        if(!$form->isValid($this->_request->getPost())) {
            return $this->view->form = $form;
        }
        
        $modelNode = Chaplin_Model_Node::create(
            $form->IP->getValue(),
            $form->Name->getValue()
        );
        $modelNode->save();
        
        $this->_helper->FlashMessenger('Added Node');
        return $this->_redirect('/admin/node');
    }
    
    public function pingAction()
    {
        $strNodeId = $this->_request->getParam('NodeId', null);
        if(is_null($strNodeId)) {
            return $this->_redirect('/admin/node');
        }
        
        $modelNode = Chaplin_Gateway::getInstance()
            ->getNode()
            ->getByNodeId($strNodeId);
            
        if(!$modelNode->ping()) {
            $this->_helper->FlashMessenger('Host '.$modelNode->getIP().' is not responding.');
        }
        
        return $this->_redirect('/admin/node');
    }
}
