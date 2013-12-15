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

        if ($this->_isAPICall()) {
            return $this->view->assign($modelVideo->toArray());
        }

        $this->view->strTitle = $modelVideo->getTitle();

        $ittComments = Chaplin_Gateway::getInstance()
            ->getVideo_Comment()
            ->getByVideoId($strVideoId);
            
        $this->view->assign('video', $modelVideo);
        $this->view->assign('ittComments', $ittComments);
        $strShortHost = Chaplin_Config_Servers::getInstance()->getShort();
        $strShortURL = 'http://'.$strShortHost.'/'.
            str_replace('/','-',base64_encode(hex2bin($strVideoId)));
        $this->view->assign('short', $strShortURL);

        $strTwitterShare = '<iframe id="tweetbutton" allowtransparency="true" frameborder="0" scrolling="no" src="'.
        'https://platform.twitter.com/widgets/tweet_button.html'.
        '?url='.urlencode($strShortURL).
        '&dnt=true'.
        '&hashtags=projectchaplin'.
        '&text='.$this->view->strTitle.' : '.
        '"></iframe>';

        $this->view->twittershare = $strTwitterShare;
        
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

    public function watchremoteAction()
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
        
        $strNodeId = $this->_request->getParam('node', 0);

        $modelNode = Chaplin_Gateway::getNode()
            ->getByNodeId($strNodeId);

        $this->view->node = $modelNode;

        $modelVideo = $modelNode->getVideoById($strVideoId);

        $this->view->strTitle = $modelVideo->getTitle();
        // todo comments

        $this->view->assign('video', $modelVideo);
    }

    public function watchshortAction()
    {
	    $strId   = $this->_request->getParam('id');
        $strId   = str_replace('-','/', $strId);
        $strId   = str_replace(' ','+', $strId);
        $strId   = bin2hex(base64_decode($strId));
        $strHost = Chaplin_Config_Servers::getInstance()
            ->getVhost();
        return $this->_redirect('https://'.$strHost.'/video/watch/id/'.$strId);
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
        try {
            $yt = new Zend_Gdata_YouTube();
            $entryVideo = $yt->getVideoEntry($strVideoId);
            $this->view->entryVideo = $entryVideo;
        } catch (Exception $e) {
            throw new Chaplin_Exception_NotFound('Youtube Id = '.$strVideoId);
        }
        // This won't work remotely
        if (in_array($this->_request->getClientIp(), ['127.0.0.1', '::1'])) {
            $this->view->videoURL = Chaplin_Service::getInstance()
                ->getYouTube($strVideoId)
                ->getDownloadURL();
            $this->view->isLocal = true;
        }
        $this->view->strScheme = Chaplin_Config_Chaplin::getInstance()->getScheme();
        $this->view->strTitle = $this->view->entryVideo->getTitle()->getText();
    }

    public function importremoteAction()
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
        
        $strNodeId = $this->_request->getParam('node', 0);

        $modelNode = Chaplin_Gateway::getNode()
            ->getByNodeId($strNodeId);

        $this->view->node = $modelNode;

        $modelVideo = $modelNode->getVideoById($strVideoId);
        $modelVideo->save();
        Chaplin_Model_Video_Import::create($modelVideo, $modelNode);

        return $this->_redirect('/video/watch/id'.$modelVideo->getVideoId());
    }

    public function importyoutubeAction()
    {
        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            return $this->_redirect('/');
        }

        $modelUser = Chaplin_Auth::getInstance()->getIdentity()->getUser();

        $modelVideo = Chaplin_Service::getInstance()
            ->getYouTube($strVideoId)
            ->importVideo($modelUser);
        
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

        if ($this->_isAPICall()) {
            return $this->view->assign($ittComments->toArray());
        }

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
        $strVideoId = $this->_request->getParam('id', null);

        $modelUser = Chaplin_Auth::getInstance()
            ->getIdentity()
            ->getUser();

        $modelVideo = Chaplin_Gateway::getInstance()
            ->getVideo()
            ->getByVideoId($strVideoId, $modelUser);

        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
    
        
        $strVote = $this->_request->getParam('vote', null);
        if(is_null($strVideoId)) {
            return $this->_redirect('/');
        }

        if ('up' == $strVote) {
            Chaplin_Gateway::getVote()->addVote($modelUser, $modelVideo, 1);
        } elseif('down' == $strVote) {
            Chaplin_Gateway::getVote()->addVote($modelUser, $modelVideo, 0);
        }
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
                ->getEncoder()
                ->getThumbnail($strFilename, $strPathToThumb, $ret);
            if(0 != $ret) {
                die(var_dump($strError));
            }
            
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
        $this->view->strTitle = 'Edit Video - Chaplin';
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
