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
namespace Chaplin\Module\Cli\Controller;

use Chaplin\Gateway;
use Chaplin\Model\User as ModelUser;
use Chaplin\Model\User\Helper\UserType;
use Zend_Db_Statement_Exception as StatementException;
use Chaplin\Controller\Action as Controller;

class InitController extends Controller
{
    public function preDispatch()
    {
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function cliAdminuser()
    {
        $strUsername = getenv("ADMIN_USERNAME");
        $strPassword = getenv("ADMIN_PASSWORD");
        $strFullName = getenv("ADMIN_FULL_NAME");
        $strEmail = getenv("ADMIN_EMAIL");

        echo "Creating admin user with username = $strUsername, ".
            "and password = $strPassword, ".
            "with full name = $strFullName ".
            "and email = $strEmail\n";

        $modelUser = ModelUser::create($strUsername, $strPassword);

        $modelUser->setNick($strFullName);
        $modelUser->setEmail($strEmail);
        $modelUser->setUserType(new UserType(UserType::ID_GOD));

        try {
            Gateway::getUser()->save($modelUser);
            echo "Success\n";
        } catch (StatementException $e) {
            // Catch duplicate key only
            // hack: mysql
            if (23000 !== $e->getCode()) {
                throw $e;
            }
            echo "User already exists.";
        }
    }
}
