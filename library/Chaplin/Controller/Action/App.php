<?php
class Chaplin_Controller_Action_App extends Chaplin_Controller_Action
{
    protected $_messages;

    protected function isAjax()
    {
        return ($this->getRequest()->isXMLHTTPRequest());
    }

    protected function addMessage(Array $message)
    {
        $this->_messages[] = $message;
    }

    protected function addMessages(Array $messages)
    {
        foreach($messages as $message)
        {
            $this->addMessage($message);
        }
    }

    protected function setMessage($message)
    {
        $this->_messages = array($message);
    }

    protected function getMessages()
    {
        return $this->_messages;
    }

    protected function hasMessages()
    {
        return (count($this->getMessages()) > 0);
    }

    protected function hasRedirectOnly()
    {
        foreach($this->getMessages() as $message)
        {
            if(count($message) == 1 && isset($message['redirect']))
            {
                return true;
            }
        }

        return false;
    }

    protected function drawMessages()
    {
        if(!$this->hasMessages() || $this->hasRedirectOnly())
        {
            return null;
        }

        $ret = '';

        foreach($this->getMessages() as $message)
        {
            $ret .= '<li class="' . $message['class'] . '">' . $message['text'] . '</li>';
            
            if(isset($message['redirect']))
            {
                $this->_session->messages = $ret;
            }

            return null;
        }

        return $ret;
    }

    protected function clearMessages()
    {
        $this->_messages = null;
    }

    protected function messageRedirect()
    {
        if(!$this->hasMessages())
        {
            return false;
        }

        foreach($this->getMessages() as $message)
        {
            if(isset($message['redirect']))
            {
                $this->_redirect($message['redirect']);
            }
        }
    }

    protected function _redirect($url, array $options = array())
    {
        if($this->isAjax())
        {
            $this->setMessage(array('redirect' => $url));
        }
        else
        {
            parent::_redirect($url);
        }
    }

    protected function login()
    {
        // TODO: Make this drop down and not quit the page!
        $this->_session->messages = '<li class="warn">You need to login to access this feature.</li>';
        $this->_redirect('/login?redirect=' . $_SERVER['REQUEST_URI']);
    }

    protected function requireLogin()
    {
        if(!Chaplin_Auth::getInstance()->hasIdentity())
        {
            $this->login();
        }
    }

    protected function isLoggedIn()
    {
        return Chaplin_Auth::getInstance()->hasIdentity();
    }

    public function postDispatch()
    {
        parent::postDispatch();

        if($this->isAjax())
        {
            $this->view->layout()->setLayout('app'); // This includes AJAX stuff again
        }

        if($this->hasMessages())
        {
            $this->view->assign('messages', $this->drawMessages());

            if($this->isAjax())
            {
                $this->view->layout()->disableLayout();
                $renderer = $this->getHelper('ViewRenderer');
                $renderer->setNoRender(true);
                echo json_encode($this->getMessages());
            }
            else
            {
                $this->messageRedirect();
            }
        }
    }

    public function getReferrer()
    {
        $server = $this->getRequest()->getServer();
        if(isset($server['HTTP_REFERER']))
        {
            $referrer = $server['HTTP_REFERER'];
            $host = $server['HTTP_HOST'];
            $parsed = parse_url($referrer);
            if($parsed['host'] != $host)
            {
                $referrer = '/';
            }
        }
        if($this->getQuery('redirect'))
        {
            $referrer = $this->getQuery('redirect');
        }

        if(isset($referrer))
        {
            return $referrer;
        }
        return '/';
    }
}
