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
        $resource = $strModule.'/'.$strController;
        $role = $auth->hasIdentity() ?
            $auth->getIdentity()->getUser()->getUserType()->getUserType():
            Chaplin_Model_User_Helper_UserType::TYPE_GUEST;

        if (false === strpos($request->getRequestUri(), 'login')
            && $this->_acl->isAllowed($role, strtolower($resource), $strAction)
            && 'error' != $strAction
        ) {
            $login = new Zend_Session_Namespace('login');
            $login->url = $request->getRequestUri();
        }

        if (!$this->_acl->isAllowed($role, strtolower($resource), $strAction)) {
            return $redirectHelper->gotoUrl('/login');
        }
    }
}
