<?php
class Chaplin_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    private $_acl;

    public function __construct(Zend_Acl $acl)
    {
        $this->_acl = $acl;
    }

    private function _isValidRequest(Zend_Controller_Request_Abstract $request)
    {
        $dispatcher = Zend_Controller_Front::getInstance()->getDispatcher();
        if ($dispatcher->isDispatchable($request)) {
            $className = $dispatcher->getControllerClass($request);
            $fullClassName = $dispatcher->loadClass($className);
            $action = $dispatcher->getActionMethod($request);
            $class = new Zend_Reflection_Class($fullClassName);
            return $class->hasMethod($action);
        }
        return false;
    }
 
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if (!$this->_isValidRequest($request)) {
            return;
        }

        $redirectHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');

        $defaultModule = Zend_Controller_Front::getInstance()->getDefaultModule();
        $strModule = $request->getModuleName();
        $strController = $request->getControllerName();
        $strAction = $request->getActionName();

        $auth = Chaplin_Auth::getInstance();
        // If we're on default don't bother with the /controller bit - it's all the same
        $resource = ($defaultModule == $strModule)?$defaultModule:$strModule.'/'.$strController;
        $role = $auth->hasIdentity() ? $auth->getIdentity()->getUser()->getUserType()->getUserType(): Chaplin_Model_User_Helper_UserType::TYPE_GUEST;
        
        if (!$this->_acl->isAllowed($role, $resource, $strAction)) {
            throw new Chaplin_Exception_Permission();
        }
    }
}
