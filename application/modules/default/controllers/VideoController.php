<?php
class VideoController extends Zend_Controller_Action
{
    public function watchAction()
    {
        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            return $this->_redirect('/');
        }
        
        $modelVideo = Chaplin_Gateway::getInstance()
            ->getVideo()
            ->getByVideoId($strVideoId);
        $this->view->assign('video', $modelVideo);
    }
    
    public function uploadAction()
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
        
        $this->_helper->FlashMessenger('Video Saved.');
        $this->_redirect('/video/watch/id/'.$modelVideo->getVideoId());
    }
    
    public function youtubeAction()
    {
        $strURL = $this->_request->getQuery('url');
        
    }
}
