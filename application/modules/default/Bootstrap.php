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
class Default_Bootstrap extends Zend_Application_Module_Bootstrap
{
    // Load the local models and forms
    protected function _initModuleAutoloader()
    {    
        $this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'default',
            'basePath'  => APPLICATION_PATH . '/modules/default',
        ));  
    }    
    protected function _initAcl()
    {    
        //$acl = $this->getApplication()->getResource('acl');     
        $acl = Zend_Registry::get('acl');
        {    
            $acl->add(new Zend_Acl_Resource('default/index'));
            $acl->add(new Zend_Acl_Resource('default/broadcast'));
            $acl->add(new Zend_Acl_Resource('default/error'));
            $acl->add(new Zend_Acl_Resource('default/login'));
            $acl->add(new Zend_Acl_Resource('default/manifest'));
            $acl->add(new Zend_Acl_Resource('default/messages'));
            $acl->add(new Zend_Acl_Resource('default/search'));
            $acl->add(new Zend_Acl_Resource('default/video'));
            $acl->add(new Zend_Acl_Resource('default/user'));
                 
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'default/index');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_USER, 'default/broadcast');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'default/error');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'default/login');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'default/manifest');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_USER, 'default/messages');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'default/search');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'default/user');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_USER, 'default/video');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'default/video', 'watch');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'default/video', 'watchshort');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'default/video', 'watchyoutube');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'default/video', 'watchremote');
        }
    }
}
