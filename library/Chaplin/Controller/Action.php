<?php
class Chaplin_Controller_Action extends Zend_Controller_Action
{
    protected $_session;

    public function preDispatch()
    {
        $this->view->addHelperPath('Chaplin/View/Helper/', 'Chaplin_View_Helper');

        $this->_session = new Zend_Session_Namespace('zetabud');

        if(!is_null($this->_session->messages))
        {
            $this->view->assign('session_messages', $this->_session->messages);
            $this->_session->messages = null;
        }
    }

    public function isPost()
    {
        return $this->getRequest()->isPost();
    }

    public function isGet()
    {
        return $this->getRequest()->isGet();
    }

    public function getPost($param = null)
    {
        return $this->getRequest()->getPost($param);
    }

    public function getQuery($param = null)
    {
        return $this->getRequest()->getQuery($param);
    }

    public function setAppTitle($title)
    {
        $this->view->assign('apptitle', $title);
    }

    public function setAppSection($section)
    {
        $this->view->assign('appsection', $section);
    }
}
