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
 * @link      https://github.com/kathiedart/projectchaplin
**/

use Chaplin_Model_User_Helper_UserType as UserType;
use Zend_Acl_Resource as Acl;
use Zend_Application_Module_Bootstrap as ModuleBootstrap;
use Zend_Registry as Reg;

class Default_Bootstrap extends ModuleBootstrap
{
    protected function _initAcl()
    {
        //$acl = $this->getApplication()->getResource('acl');
        $acl = Reg::get('acl');

        $acl->add(new Acl('default/index'));
        $acl->add(new Acl('default/broadcast'));
        $acl->add(new Acl('default/error'));
        $acl->add(new Acl('default/login'));
        $acl->add(new Acl('default/search'));
        $acl->add(new Acl('default/services'));
        $acl->add(new Acl('default/video'));
        $acl->add(new Acl('default/user'));

        $acl->allow(UserType::TYPE_GUEST, 'default/index');
        $acl->allow(UserType::TYPE_USER, 'default/broadcast');
        $acl->allow(UserType::TYPE_GUEST, 'default/error');
        $acl->allow(UserType::TYPE_GUEST, 'default/login');
        $acl->allow(UserType::TYPE_GUEST, 'default/search');
        $acl->allow(UserType::TYPE_GUEST, 'default/services');
        $acl->allow(UserType::TYPE_GUEST, 'default/user');
        $acl->allow(UserType::TYPE_USER, 'default/video');
        $acl->allow(UserType::TYPE_GUEST, 'default/video', 'watch');
        $acl->allow(UserType::TYPE_GUEST, 'default/video', 'watchshort');
        $acl->allow(UserType::TYPE_GUEST, 'default/video', 'watchyoutube');
        $acl->allow(UserType::TYPE_GUEST, 'default/video', 'watchvimeo');
        $acl->allow(UserType::TYPE_GUEST, 'default/video', 'watchremote');
    }
}
