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
class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{
    // Load the local models and forms
    protected function _initModuleAutoloader()
    {
        $this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'default',
            'basePath'  => APPLICATION_PATH . '/modules/admin',
        ));
    }
    protected function _initAcl()
    {
        $acl = Zend_Registry::get('acl');

        $acl->add(new Zend_Acl_Resource('admin/events'));
        $acl->add(new Zend_Acl_Resource('admin/import'));
        $acl->add(new Zend_Acl_Resource('admin/node'));
        $acl->add(new Zend_Acl_Resource('admin/nodestatus'));
        $acl->add(new Zend_Acl_Resource('admin/error'));
        $acl->add(new Zend_Acl_Resource('admin/user'));
        $acl->add(new Zend_Acl_Resource('admin/setup'));
        $acl->add(new Zend_Acl_Resource('admin/daemons'));

        $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'admin/setup');
        $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_MINION, 'admin/events');
        $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GOD, 'admin/import');
        $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GOD, 'admin/daemons');
        $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GOD, 'admin/node');
        $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'admin/nodestatus');
        $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GOD, 'admin/error');
        $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GOD, 'admin/user');
    }
}
