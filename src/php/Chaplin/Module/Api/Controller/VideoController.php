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
 * @package   ProjectChaplin
 * @author    Dan Dart <chaplin@dandart.co.uk>
 * @copyright 2012-2018 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   GIT: $Id$
 * @link      https://github.com/danwdart/projectchaplin
**/
namespace Chaplin\Module\Api\Controller;

use Chaplin_Auth as Auth;
use Chaplin_Controller_Action_Api as ApiController;
use Chaplin_Exception_NotFound as ExceptionNotFound;
use Chaplin_Gateway as Gateway;
use Chaplin_Model_Video as ModelVideo;
use Chaplin_Model_Video_Comment as ModelComment;
use Chaplin_Model_Video_Convert as ModelConvert;
use Chaplin\Module\Api\Form\Video\{
    Comment as FormComment,
    Edit as FormEdit,
    Name as FormName,
    Upload as FormUpload
};
use Chaplin_Service as Service;
use Exception;
use Misd\Linkify\Linkify;

class VideoController extends ApiController
{
    public function watchAction()
    {
        $modelUser = Auth::getInstance()
            ->hasIdentity()?
            Auth::getInstance()
                ->getIdentity()
                ->getUser():
            null;

        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            $this->_redirect('/');
            return;
        }

        $modelVideo = Gateway::getInstance()
            ->getVideo()
            ->getByVideoId($strVideoId, $modelUser);

        if ($this->_isAPICall()) {
            return $this->view->assign($modelVideo->toArray());
        }

        $this->view->strTitle = $modelVideo->getTitle();

        $ittComments = Gateway::getInstance()
            ->getVideo_Comment()
            ->getByVideoId($strVideoId);

        $this->view->video = $modelVideo;
        $this->view->assign('ittComments', $ittComments);

        $this->view->vhost = getenv("SCHEME")."://".getenv("VHOST");

        $url = $this->view->vhost.'/video/watch/id/'.$this->view->video->getVideoId();

        $strShortHost = getenv("VHOST_SHORT");
        $strShortURL = 'http://'.$strShortHost.'/'.
            str_replace('/', '-', base64_encode(hex2bin($strVideoId)));
        $this->view->assign('short', $strShortURL);

        $this->view->facebookAppId = getenv("FACEBOOK_CLIENT_ID");

        $formComment = new FormComment();

        if(!$this->_request->isPost()) {
            return $this->view->assign('formComment', $formComment);
        }

        if(!Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/login');
            return;
        }

        if(!$formComment->isValid($this->_request->getPost())) {
            return $this->view->assign('formComment', $formComment);
        }

        $strComment = trim(htmlentities($formComment->Comment->getValue()));
        if (empty($strComment)) {
            return $this->view->assign('formComment', $formComment);
        }

        $modelComment = ModelComment::create(
            $modelVideo,
            $modelUser,
            $strComment
        );

        Gateway::getInstance()
            ->getVideo_Comment()
            ->save($modelComment);

        return $this->view->assign('formComment', $formComment);
    }

    /*
    // Nodes and remote are disabled for this release.
    public function watchremoteAction()
    {
        $modelUser = Auth::getInstance()
            ->hasIdentity()?
            Auth::getInstance()
                ->getIdentity()
                ->getUser():
            null;

        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            $this->_redirect('/');
            return;
        }

        $strNodeId = $this->_request->getParam('node', 0);

        $modelNode = Gateway::getNode()
            ->getByNodeId($strNodeId);

        $this->view->node = $modelNode;

        $modelVideo = $modelNode->getVideoById($strVideoId);

        $this->view->strTitle = $modelVideo->getTitle();
        // todo comments

        $this->view->assign('video', $modelVideo);
    }
    */

    public function watchshortAction()
    {
        $strId   = $this->_request->getParam('id');
        $strId   = str_replace('-', '/', $strId);
        $strId   = str_replace(' ', '+', $strId);
        $strId   = bin2hex(base64_decode($strId));
        $strHost = getenv("VHOST");
        $this->_redirect('https://'.$strHost.'/video/watch/id/'.$strId);
        return;
    }

    public function watchyoutubeAction()
    {
        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            $this->_redirect('/');
            return;
        }

        // Get the YT information
        try {
            $ytService = Service::getInstance()->getYouTube();
            $entryVideo = $ytService->getVideoById($strVideoId);
            $this->view->entryVideo = $entryVideo;
        } catch (Exception $e) {
            throw new ExceptionNotFound('Youtube Id = '.$strVideoId);
        }
        // This won't work remotely
        if (in_array($this->_request->getClientIp(), ['127.0.0.1', '::1'])) {
            $this->view->videoURL = Service::getInstance()
                ->getYouTube()
                ->getDownloadURL($strVideoId);
            $this->view->isLocal = true;
        }

        $linkify = new Linkify(
            [
                "attr" => [
                    "target" => "_blank"
                ]
            ]
        );

        $this->view->strScheme = getenv("SCHEME");
        $this->view->strTitle = $entryVideo->getSnippet()->title;

        $strDescription = $entryVideo->getSnippet()->description;

        $strLinkified = $linkify->process($strDescription);

        $this->view->description = nl2br($strLinkified);
    }

    public function importyoutubeAction()
    {
        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            $this->_redirect('/');
            return;
        }

        $modelUser = Auth::getInstance()->getIdentity()->getUser();

        $modelVideo = Service::getInstance()
            ->getYouTube()
            ->importVideo($modelUser, $strVideoId);

        $this->_redirect('/video/watch/id/'.$modelVideo->getVideoId());
    }

    public function watchvimeoAction()
    {
        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            $this->_redirect('/');
            return;
        }

        // Get the Vimeo information
        try {
            $vimeoService = Service::getInstance()->getVimeo();
            $entryVideo = $vimeoService->getVideoById($strVideoId);
            $this->view->entryVideo = $entryVideo;
        } catch (Exception $e) {
            throw new ExceptionNotFound('Vimeo Id = '.$strVideoId);
        }
        // This won't work remotely
        if (in_array($this->_request->getClientIp(), ['127.0.0.1', '::1'])) {
            $this->view->videoURL = Service::getInstance()
                ->getVimeo()
                ->getDownloadURL($strVideoId);
            $this->view->isLocal = true;
        }
        $this->view->strScheme = getenv("SCHEME");
        $this->view->strTitle = $entryVideo['name'];

        $linkify = new Linkify(
            [
                "attr" => [
                    "target" => "_blank"
                ]
            ]
        );

        $strDescription = $entryVideo['description'];

        $strLinkified = $linkify->process($strDescription);

        $this->view->description = nl2br($strLinkified);
    }

    public function importvimeoAction()
    {
        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            $this->_redirect('/');
            return;
        }

        $modelUser = Auth::getInstance()->getIdentity()->getUser();

        $modelVideo = Service::getInstance()
            ->getVimeo()
            ->importVideo($modelUser, $strVideoId);

        $this->_redirect('/video/watch/id/'.$modelVideo->getVideoId());
    }

    public function commentsAction()
    {
        $this->_helper->layout()->disableLayout();

        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            throw new Exception('Invalid video');
        }

        $ittComments = Gateway::getInstance()
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

        $modelComment = Gateway::getInstance()
            ->getVideo_Comment()
            ->getById($strCommentId);

        if (!$modelComment->isMine()) {
            return;
        }

        Gateway::getInstance()
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
            $this->_redirect('/');
            return;
        }

        $modelUser = Auth::getInstance()
            ->hasIdentity()?
            Auth::getInstance()
                ->getIdentity()
                ->getUser():
            null;

        $modelVideo = Gateway::getInstance()
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

        $modelUser = Auth::getInstance()
            ->getIdentity()
            ->getUser();

        $modelVideo = Gateway::getInstance()
            ->getVideo()
            ->getByVideoId($strVideoId, $modelUser);

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);


        $strVote = $this->_request->getParam('vote', null);
        if(is_null($strVideoId)) {
            $this->_redirect('/');
            return;
        }

        if ('up' == $strVote) {
            Gateway::getVote()->addVote($modelUser, $modelVideo, 1);
        } elseif('down' == $strVote) {
            Gateway::getVote()->addVote($modelUser, $modelVideo, 0);
        }
    }

    public function uploadAction()
    {
        $form = new FormUpload();

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
                die(print_r($adapter->getMessages(), true));
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

            $strError = Service::getInstance()
                ->getEncoder()
                ->getThumbnail($strFilename, $strPathToThumb, $ret);
            if(0 != $ret) {
                die(var_dump($strError));
            }

            $modelUser = Auth::getInstance()->getIdentity()->getUser();

            $modelVideo = ModelVideo::create(
                $modelUser,
                $strRelaPath.$strRelaFile,
                $strRelaPath.$strRelaThumb,
                $strTitle,
                '',
                '',
                "Uploaded file"
            );
            $modelVideo->save();

            $modelConvert = ModelConvert::create($modelVideo);
            Gateway::getInstance()->getVideo_Convert()->save($modelConvert);

            $this->view->videos[] = $modelVideo;
        }
    }

    public function nameAction()
    {
        // Not sure how to implement this yet
        // Will skip until I work it out
        $this->_redirect('/');
        return;
        /*
        $identity = Auth::getInstance()
            ->getIdentity();

        $modelUser = Auth::getInstance()
            ->hasIdentity()?
            Auth::getInstance()
                ->getIdentity()
                ->getUser():
            null;

        $ittVideos = Gateway::getInstance()
            ->getVideo()
            ->getByUserUnnamed($modelUser);

        $this->view->videos = $ittVideos;

        $form = new FormName($ittVideos);

        if (!$this->_request->isPost()) {
            return $this->view->form = $form;
        }

        if (!$form->isValid($this->_request->getPost())) {
            return $this->view->form = $form;
        }

        $arrVideos = $this->_request->getPost('Videos', array());

        foreach($arrVideos as $strVideoId => $arrVideos) {
            $modelVideo = Gateway::getInstance()
                ->getVideo()
                ->getByVideoId($strVideoId, $modelUser);
            if($modelVideo->isMine()) {
                $modelVideo->setFromAPIArray($arrVideos);
                $modelVideo->save();
            }
        }

        $this->_redirect('/');
        */
    }

    public function editAction()
    {
        $this->view->strTitle = 'Edit Video - Chaplin';
        $modelUser = Auth::getInstance()
            ->hasIdentity()?
            Auth::getInstance()
                ->getIdentity()
                ->getUser():
            null;

        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            $this->_redirect('/');
            return;
        }

        $modelVideo = Gateway::getInstance()
            ->getVideo()
            ->getByVideoId($strVideoId, $modelUser);

        $form = new FormEdit($modelVideo);

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

        $this->_redirect('/video/watch/id/'.$strVideoId);
        return;
    }

    public function deleteAction()
    {
        $modelUser = Auth::getInstance()
            ->hasIdentity()?
            Auth::getInstance()
                ->getIdentity()
                ->getUser():
            null;

        if(!Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/login');
            return;
        }

        $strVideoId = $this->_request->getParam('id', null);
        if(is_null($strVideoId)) {
            $this->_redirect('/');
            return;
        }

        $modelVideo = Gateway::getInstance()
            ->getVideo()
            ->getByVideoId($strVideoId, $modelUser);

        if ($modelVideo->isMine()
            || Auth::getInstance()->getIdentity()->getUser()->isGod()
        ) {
            // Confirmation?
            $modelVideo->delete();
        }

        $this->_redirect('/');
    }
}
