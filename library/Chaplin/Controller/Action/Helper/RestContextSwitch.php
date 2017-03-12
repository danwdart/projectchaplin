<?php
class Chaplin_Controller_Action_Helper_RestContextSwitch
    extends Zend_Controller_Action_Helper_ContextSwitch
{
    public function getActionContexts($action = null)
    {
        return parent::getActionContexts($action ? 'global' : null);
    }

    public function hasActionContext($action, $context)
    {
        return true;
    }

    public function addActionContext($action, $context)
    {
        throw new Zend_Controller_Action_Exception('You must call addGlobalContext() instead of addActionContext()');
    }

    public function addGlobalContext($contexts)
    {
        return parent::addActionContext('global', $contexts);
    }

    public function getCurrentContext()
    {
        return $this->_currentContext;
    }

    public function initContext($format = null)
    {
        $this->_currentContext = null;

        $controller = $this->getActionController();
        $request = $this->getRequest();
        $action = $request->getActionName();

        // Return if no context switching enabled, or no context switching
        // enabled for this action
        $contexts = $this->getActionContexts($action);
        if (empty($contexts)) {
            return;
        }

        // Return if no context parameter provided
        if (!$context = $request->getParam($this->getContextParam())) {
            if ($format === null) {
                return;
            }
            $context = $format;
            $format = null;
        }

        // Check if context allowed by action controller
        if (!$this->hasActionContext($action, $context)) {
            return;
        }

        // Return if invalid context parameter provided and no format or invalid
        // format provided
        if (!$this->hasContext($context)) {
            $context = $this->getDefaultContext();
        }

        // Use provided format if passed
        if (!empty($format) && $this->hasContext($format)) {
            $context = $format;
        }

        $suffix = $this->getSuffix($context);

        $this->_getViewRenderer()->setViewSuffix($suffix);

        $headers = $this->getHeaders($context);
        if (!empty($headers)) {
            $response = $this->getResponse();
            foreach ($headers as $header => $content) {
                $response->setHeader($header, $content);
            }
        }

        if ($this->getAutoDisableLayout()) {
            $layout = Zend_Layout::getMvcInstance();
            if (null !== $layout) {
                $layout->disableLayout();
            }
        }

        if (null !== ($callback = $this->getCallback($context, self::TRIGGER_INIT))) {
            if (is_string($callback) && method_exists($this, $callback)) {
                $this->$callback();
            }
            else if (is_string($callback) && function_exists($callback)) {
                $callback();
            }
            else if (is_array($callback)) {
                call_user_func($callback);
            }
            else
            {
                throw new Zend_Controller_Action_Exception(
                    sprintf('Invalid context callback registered for context "%s"', $context)
                );
            }
        }

        $this->_currentContext = $context;
    }
}