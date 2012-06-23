<?php
class VideoController extends Zend_Controller_Action
{
    public function indexAction()
    {
        if(!Chaplin_Auth::getInstance()->hasIdentity()) {
            return $this->_redirect('/login');
        }
        
        $form = new default_Form_Video_Upload();
        
        if(!$this->_request->isPost()) {
            return $this->view->assign('form', $form);
        }
        
        if(!$form->isValid($this->_request->getPost())) {
            return $this->view->assign('form', $form);
        }
        
        $form->File->receive();
        
        $strFilename = '/uploads/'.basename($form->File->getFilename());
        
        $modelUser = Chaplin_Auth::getInstance()->getIdentity()->getUser();
        
        $modelVideo = Chaplin_Model_Video::create(
            $modelUser,
            $strFilename,
            $form->Title->getValue()
        );
        
        $modelVideo->save();
        
        $this->view->assign('filename', $strFilename);
    }
}
