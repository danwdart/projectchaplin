<?php
class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->view->assign('ittFeaturedVideos', Chaplin_Gateway::getInstance()
            ->getVideo()
            ->getFeaturedVideos()
        );
    }
}

