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
        
        $formComment = new default_Form_Video_Comment();
                
        if(!$this->_request->isPost()) {
            return $this->view->assign('formComment', $formComment);
        }
        
        if(!Chaplin_Auth::getInstance()->hasIdentity()) {
            return $this->_redirect('/login');
        }
        
        if(!$formComment->isValid($this->_request->getPost())) {
            return $this->view->assign('formComment', $formComment);
        }
           
        $modelUser =  Chaplin_Auth::getInstance()->getIdentity()->getUser();
          
        $modelComment = Chaplin_Model_Video_Comment::create(
            $modelVideo,
            $modelUser,
            $formComment->Comment->getValue()
        );
        $modelComment->save();
        
        return $this->view->assign('formComment', $formComment);
    }
    
    public function downloadAction()
    {
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
        
        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            return $this->_redirect('/');
        }
        
        $modelVideo = Chaplin_Gateway::getInstance()
            ->getVideo()
            ->getByVideoId($strVideoId);
        // read/etc/protect later?
        $strPath = realpath(APPLICATION_PATH.'/../public'.$modelVideo->getFilename());
        $this->getResponse()->setHeader(
            'Content-Disposition', 'attachment; filename='.basename($modelVideo->getFilename())
        );
        echo file_get_contents($strPath);
    }

    public function voteAction()
    {
        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            return $this->_redirect('/');
        }

        $strVote = $this->_request->getParam('vote', null);
        
    }
    
    public function uploadAction()
    {
        $form = new default_Form_Video_Upload();
        
        if(!$this->_request->isPost()) {
            return $this->view->assign('form', $form);
        }
        
        var_dump($_POST);
        var_dump($_FILES);
        var_dump(file_get_contents('php://stdin'));

        die();
        
        
        
        if(!$form->isValid($this->_request->getPost())) {
            echo 'Set post_max_size to something sensible.';
            return $this->view->assign('form', $form);
        }
        
        // We can't directly receive multiple files

        $adapter = $form->Files->getTransferAdapter();
        foreach($adapter->getFileInfo() as $info) {
            if (!$adapter->receive($info['name'])) {
                die(print_r($adapter->getMessages(),true));
            }
        }

        $this->view->videos = array();

        foreach ($adapter->getFileInfo() as $arrFileInfo) {
        die(var_dump($arrFileInfo));
            /*$adapter->addFilter(
                'Rename', array(
                    'target' => $form->Files->getDestination(),
                    'overwrite' => true
                )
            );*/
            $strFilename = $arrFileInfo['tmp_name'];      

            $strPathToWebm = $strFilename.'.webm';
            $strPathToThumb = $strFilename.'.png';
            
            $strWebM = basename($strPathToWebm);
            $strThumb = basename($strPathToThumb);
            
            $strRelaPath = '/uploads/';
            
            $ret = 0;
            
            $strError = Chaplin_Service::getInstance()
                ->getAVConv()
                ->convertFile($strFilename, $strPathToWebm, $ret);
            if(0 != $ret) {
                die(var_dump($strError));
            }
            
            $ret = 0;
                
            $strError = Chaplin_Service::getInstance()
                ->getAVConv()
                ->getThumbnail($strFilename, $strPathToThumb, $ret);
            if(0 != $ret) {
                die(var_dump($strError));
            }
            
            // Put this somewhere else
            //unlink($strFilename);
            
            $modelUser = Chaplin_Auth::getInstance()->getIdentity()->getUser();
            
            $modelVideo = Chaplin_Model_Video::create(
                $modelUser,
                $strRelaPath.$strWebM,
                $strRelaPath.$strThumb,
                null
            );
            
            $modelVideo->save();
            $this->view->videos[] = $modelVideo;
        }
    }
    
    public function nameAction()
    {
        $identity = Chaplin_Auth::getInstance()
            ->getIdentity();
        
        $modelUser = $identity->getUser();
        
        $this->view->videos = Chaplin_Gateway::getInstance()
            ->getVideo()
            ->getByUserUnnamed($modelUser);
    }
    
    public function youtubeAction()
    {
        $strURL = $this->_request->getQuery('url');
        
    }
    
    public function deleteAction()
    {
        if(!Chaplin_Auth::getInstance()->hasIdentity()) {
            return $this->_redirect('/login');
        }
        
        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            return $this->_redirect('/');
        }
        
        $modelVideo = Chaplin_Gateway::getInstance()
            ->getVideo()
            ->getByVideoId($strVideoId);
        
        if ($modelVideo->isMine() || 
            Chaplin_Auth::getInstance()->getIdentity()->getUser()->isGod()) {
            // Confirmation?
            $modelVideo->delete();
        }
                    
        $this->_redirect('/');
    }
}
