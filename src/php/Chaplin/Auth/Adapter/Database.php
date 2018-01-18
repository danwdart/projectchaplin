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
namespace Chaplin\Auth\Adapter;

use Chaplin\Auth\Identity;
use Chaplin_Dao_Exception_User_NotFound as ExceptionUserNotFound;
use Chaplin_Gateway as Gateway;
use Zend_Auth_Adapter_Interface as AdapterInterface;
use Zend_Auth_Result as Result;

class Database implements AdapterInterface
{
    private $_strUsername;
    private $_strPassword;

    public function __construct($strUsername, $strPassword)
    {
        $this->_strUsername = $strUsername;
        $this->_strPassword = $strPassword;
    }

    public function authenticate()
    {
        try {
            $modelUser = Gateway::getInstance()
                ->getUser()
                ->getByUsernameAndPassword(
                    $this->_strUsername,
                    $this->_strPassword
                );

            $identity = new Identity($modelUser);

            return new Result(Result::SUCCESS, $identity);
        } catch(ExceptionUserNotFound $e) {
            return new Result(
                Result::FAILURE_CREDENTIAL_INVALID,
                null,
                array($e->getMessage())
            );
        }
    }
}
