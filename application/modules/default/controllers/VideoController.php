<?php
class VideoController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $form = new default_Form_Video_Upload();
        
        if(!$this->_request->isPost()) {
            return $this->view->assign('form', $form);
        }
        
        if(!$form->isValid($this->_request->getPost())) {
            return $this->view->assign('form', $form);
        }
        
        $form->File->receive();
        
        $strFilename = '/uploads/'.basename($form->File->getFilename());
        
        $this->view->assign('filename', $strFilename);
    }
}
