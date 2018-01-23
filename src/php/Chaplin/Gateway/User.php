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

namespace Chaplin\Gateway;

use Chaplin\Gateway\GatewayAbstract;
use Chaplin\Dao\Interfaces\User as InterfaceUser;
use Chaplin\Model\User as ModelUser;

class User extends GatewayAbstract
{
    private $daoUser;

    public function __construct(InterfaceUser $daoUser)
    {
        $this->daoUser = $daoUser;
    }

    public function getAllUsers()
    {
        return $this->daoUser->getAllUsers();
    }

    public function getByUsernameAndPassword($strUsername, $strPassword)
    {
        return $this->daoUser->getByUsernameAndPassword($strUsername, $strPassword);
    }

    public function getByUsername($strUsername)
    {
        return $this->daoUser->getByUsername($strUsername);
    }

    public function delete(ModelUser $modelUser)
    {
        $this->daoUser->delete($modelUser);
    }

    public function save(ModelUser $modelUser)
    {
        $this->daoUser->save($modelUser);
    }

    public function updateByToken($strToken, $strPassword)
    {
        return $this->daoUser->updateByToken($strToken, $strPassword);
    }
}
