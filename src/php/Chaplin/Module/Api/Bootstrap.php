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
namespace Chaplin\Module\Api;

use Chaplin\Model\User\Helper\UserType;
use Zend_Acl_Resource as Acl;
use Zend_Application_Module_Bootstrap as ModuleBootstrap;
use Zend_Registry as Reg;

class Bootstrap extends ModuleBootstrap
{
    protected function initAcl()
    {
        //$acl = $this->getApplication()->getResource('acl');
        $acl = Reg::get('acl');

        $acl->add(new Acl('api/index'));
        $acl->add(new Acl('api/broadcast'));
        $acl->add(new Acl('api/channel'));
        $acl->add(new Acl('api/error'));
        $acl->add(new Acl('api/login'));
        $acl->add(new Acl('api/search'));
        $acl->add(new Acl('api/services'));
        $acl->add(new Acl('api/video'));
        $acl->add(new Acl('api/user'));

        $acl->allow(UserType::TYPE_GUEST, 'api/index');
        $acl->allow(UserType::TYPE_USER, 'api/broadcast');
        $acl->allow(UserType::TYPE_USER, 'api/channel');
        $acl->allow(UserType::TYPE_GUEST, 'api/error');
        $acl->allow(UserType::TYPE_GUEST, 'api/login');
        $acl->allow(UserType::TYPE_GUEST, 'api/search');
        $acl->allow(UserType::TYPE_GUEST, 'api/services');
        $acl->allow(UserType::TYPE_GUEST, 'api/user');
        $acl->allow(UserType::TYPE_USER, 'api/video');
        $acl->allow(UserType::TYPE_GUEST, 'api/video', 'watch');
        $acl->allow(UserType::TYPE_GUEST, 'api/video', 'watchshort');
        $acl->allow(UserType::TYPE_GUEST, 'api/video', 'watchyoutube');
        $acl->allow(UserType::TYPE_GUEST, 'api/video', 'watchvimeo');
        $acl->allow(UserType::TYPE_GUEST, 'api/video', 'watchremote');
    }
}
