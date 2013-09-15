<?php
/**
 * This file is part of Project Chaplin.
 *
 * Project Chaplin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Project Chaplin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Project Chaplin. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    Project Chaplin
 * @author     Dan Dart
 * @copyright  2012-2013 Project Chaplin
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version    git
 * @link       https://github.com/dandart/projectchaplin
**/
class VideoController extends Chaplin_Controller_Action_Api
{
    public function demoAction()
    {
        $modelUser = Chaplin_Auth::getInstance()
            ->hasIdentity()?
        Chaplin_Auth::getInstance()
            ->getIdentity()
            ->getUser():
        null;

        $modelVideo = Chaplin_Model_Video::create(
            $modelUser,
            '',
            '',
            'demo'
        );
        $modelVideo->save();
        $this->_redirect('/');
    }

    public function watchAction()
    {
        $modelUser = Chaplin_Auth::getInstance()
            ->hasIdentity()?
        Chaplin_Auth::getInstance()
            ->getIdentity()
            ->getUser():
        null;

        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            return $this->_redirect('/');
        }
        
        $modelVideo = Chaplin_Gateway::getInstance()
            ->getVideo()
            ->getByVideoId($strVideoId, $modelUser);

        $this->view->strTitle = $modelVideo->getTitle();

        $ittComments = Chaplin_Gateway::getInstance()
            ->getVideo_Comment()
            ->getByVideoId($strVideoId);
            
        $this->view->assign('video', $modelVideo);
        $this->view->assign('ittComments', $ittComments);
        $strShortHost = Chaplin_Config_Servers::getInstance()->getShort();
        $this->view->assign('short', 'http://'.$strShortHost.'/'.base64_encode(hex2bin($strVideoId)));
        
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

        $strComment = trim(htmlentities($formComment->Comment->getValue()));
        if (empty($strComment)) {
            return $this->view->assign('formComment', $formComment);
        }
          
        $modelComment = Chaplin_Model_Video_Comment::create(
            $modelVideo,
            $modelUser,
            $strComment
        );

        Chaplin_Gateway::getInstance()
            ->getVideo_Comment()
            ->save($modelComment);
        
        return $this->view->assign('formComment', $formComment);
    }

    public function testAction()
    {
         $modelVideo = Chaplin_Model_Video::create(
            Zend_Auth::getInstance()->getIdentity()->getUser(),
            '',
            '',
            'Test Video'
        );
        $modelVideo->save();
        
        $this->_redirect('/video/watch/id/'.$modelVideo->getVideoId());
    }

    public function watchyoutubeAction()
    {
        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            return $this->_redirect('/');
        }

        // Get the YT information

        $yt = new Zend_Gdata_YouTube();
        $entryVideo = $yt->getVideoEntry($strVideoId);
        $this->view->entryVideo = $entryVideo;
        // This won't work remotely
        if (in_array($this->_request->getClientIp(), ['127.0.0.1', '::1'])) {
            $this->view->videoURL = Chaplin_Service::getInstance()->getYouTube($strVideoId)->getDownloadURL();
            $this->view->isLocal = true;
        }
        $this->view->strScheme = Chaplin_Config_Chaplin::getInstance()->getScheme();
        $this->view->strTitle = $this->view->entryVideo->getTitle()->getText();
    }

    public function importyoutubeAction()
    {
        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            return $this->_redirect('/');
        }

        // Get the YT information

        $yt = new Zend_Gdata_YouTube();
        $entryVideo = $yt->getVideoEntry($strVideoId);

        $strTitle = $entryVideo->getVideoTitle();

        $strPath = realpath(APPLICATION_PATH.'/../public/uploads');
        $strVideoFile = $strPath.'/'.$strVideoId.'.webm';
        $strRelaFile = '/uploads/'.$strVideoId.'.webm';
        $strThumbnail = Chaplin_Service::getInstance()
            ->getYouTube($strVideoId)
            ->downloadThumbnail($strPath);

        $modelUser = Chaplin_Auth::getInstance()->getIdentity()->getUser();
            
        $modelVideo = Chaplin_Model_Video::create(
            $modelUser,
            $strRelaFile,
            $strThumbnail,
            $strTitle
        );
        $modelVideo->save();
        
        $modelYoutube = Chaplin_Model_Video_Youtube::create($modelVideo, $strVideoId);
        Chaplin_Gateway::getInstance()->getVideo_Youtube()->save($modelYoutube);

        $this->_redirect('/video/watch/id/'.$modelVideo->getVideoId());
    }

    public function commentsAction()
    {
        $this->_helper->layout()->disableLayout();

        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            throw new Exception('Invalid video');
        }

        $ittComments = Chaplin_Gateway::getInstance()
            ->getVideo_Comment()
            ->getByVideoId($strVideoId);

        $this->view->assign('comments', $ittComments);
    }

    public function deletecommentAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $strCommentId = $this->_request->getParam('id', null);
        
        $modelComment = Chaplin_Gateway::getInstance()
            ->getVideo_Comment()
            ->getById($strCommentId);

        if (!$modelComment->isMine()) {
            return;
        }

        Chaplin_Gateway::getInstance()
            ->getVideo_Comment()
            ->deleteById($strCommentId);

        $this->getResponse()->setHttpResponseCode(204);
    }
    
    public function downloadAction()
    {
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
        
        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            return $this->_redirect('/');
        }

        $modelUser = Chaplin_Auth::getInstance()
            ->hasIdentity()?
        Chaplin_Auth::getInstance()
            ->getIdentity()
            ->getUser():
        null;
        
        $modelVideo = Chaplin_Gateway::getInstance()
            ->getVideo()
            ->getByVideoId($strVideoId, $modelUser);
        // read/etc/protect later?
        $strPath = realpath(APPLICATION_PATH.'/../public'.$modelVideo->getFilename());
        $this->getResponse()->setHeader(
            'Content-Type', 'video/webm'
        );
        $this->getResponse()->setHeader(
            'Content-Disposition', 'attachment; filename='.basename($modelVideo->getFilename())
        );
        echo file_get_contents($strPath);
    }

    public function voteAction()
    {
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
    
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
        
        if(!$form->isValid($this->_request->getPost())) {
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
            /*$adapter->addFilter(
                'Rename', array(
                    'target' => $form->Files->getDestination(),
                    'overwrite' => true
                )
            );*/
            $strFilename = $arrFileInfo['tmp_name'];      
            $strMimeType = $arrFileInfo['type'];
            if (0 !== strpos($strMimeType, 'video/')) {
                // Ignore any non-videos
                // TODO: extension check?
                continue;
            }

            $strPathToThumb = $strFilename.'.png';
            
            $strRelaFile = basename($strFilename);
            $strRelaThumb = basename($strPathToThumb);
            
            $arrPathInfo = pathinfo($strFilename);
            $strTitle = $arrPathInfo['filename'];
            
            $strRelaPath = '/uploads/';
            
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
                $strRelaPath.$strRelaFile,
                $strRelaPath.$strRelaThumb,
                $strTitle
            );
            $modelVideo->save();
            
            $modelConvert = Chaplin_Model_Video_Convert::create($modelVideo);
            Chaplin_Gateway::getInstance()->getVideo_Convert()->save($modelConvert);
           
            $this->view->videos[] = $modelVideo;
        }
    }
    
    public function nameAction()
    {
        // Not sure how to implement this yet
        // Will skip until I work it out
        return $this->_redirect('/');
        $identity = Chaplin_Auth::getInstance()
            ->getIdentity();

        $modelUser = Chaplin_Auth::getInstance()
            ->hasIdentity()?
        Chaplin_Auth::getInstance()
            ->getIdentity()
            ->getUser():
        null;

        $ittVideos = Chaplin_Gateway::getInstance()
            ->getVideo()
            ->getByUserUnnamed($modelUser);
        
        $this->view->videos = $ittVideos;
        
        $form = new default_Form_Video_Name($ittVideos);
        
        if (!$this->_request->isPost()) {        
            return $this->view->form = $form;
        }
        
        if (!$form->isValid($this->_request->getPost())) {
            return $this->view->form = $form;
        }
        
        $arrVideos = $this->_request->getPost('Videos', array());
        
        foreach($arrVideos as $strVideoId => $arrVideos) {
            $modelVideo = Chaplin_Gateway::getInstance()
                ->getVideo()
                ->getByVideoId($strVideoId, $modelUser);
            if($modelVideo->isMine()) {
                $modelVideo->setFromAPIArray($arrVideos);
                $modelVideo->save();
            }
        }
        
        $this->_redirect('/');        
    }
    
    public function editAction()
    {
        $modelUser = Chaplin_Auth::getInstance()
            ->hasIdentity()?
        Chaplin_Auth::getInstance()
            ->getIdentity()
            ->getUser():
        null;

        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            return $this->_redirect('/');
        }
        
        $modelVideo = Chaplin_Gateway::getInstance()
            ->getVideo()
            ->getByVideoId($strVideoId, $modelUser);
        
        $form = new default_Form_Video_Edit($modelVideo);
        
        if (!$this->_request->isPost()) {
            return $this->view->form = $form;
        }
        
        if (!$form->isValid($this->_request->getPost())) {
            return $this->view->form = $form;
        }
        
        $arrVideos = $this->_request->getPost('Video', array());
        
        if($modelVideo->isMine()) {
            $modelVideo->setFromAPIArray($arrVideos);
            $modelVideo->save();
        }
        
        return $this->_redirect('/video/watch/id/'.$strVideoId);
    }
    
    public function youtubeAction()
    {
        $strURL = $this->_request->getQuery('url');
         
    }
    
    public function deleteAction()
    {
        $modelUser = Chaplin_Auth::getInstance()
            ->hasIdentity()?
        Chaplin_Auth::getInstance()
            ->getIdentity()
            ->getUser():
        null;

        if(!Chaplin_Auth::getInstance()->hasIdentity()) {
            return $this->_redirect('/login');
        }
        
        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            return $this->_redirect('/');
        }
        
        $modelVideo = Chaplin_Gateway::getInstance()
            ->getVideo()
            ->getByVideoId($strVideoId, $modelUser);
        
        if ($modelVideo->isMine() || 
            Chaplin_Auth::getInstance()->getIdentity()->getUser()->isGod()) {
            // Confirmation?
            $modelVideo->delete();
        }
                    
        $this->_redirect('/');
    }
}
