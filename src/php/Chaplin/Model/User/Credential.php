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

namespace Chaplin\Model\User;

use Chaplin\Model\Field\Hash;
use Chaplin\Model\User;

class Credential extends Hash
{
    const FIELD_ServiceName = 'ServiceName';
    const FIELD_ServiceURL  = 'ServiceURL';
    const FIELD_AccessToken = 'AccessToken';

    protected $arrFields = array(
        self::FIELD_ServiceName => 'Chaplin\\Model\\Field\\Field',
        self::FIELD_ServiceURL  => 'Chaplin\\Model\\Field\\Field',
        self::FIELD_AccessToken => 'Chaplin\\Model\\Field\\Field'
    );

    public static function create(User $modelUser, $strServiceName, $strServiceURL, $strAccessToken)
    {
        $credential = new self();
        $credential->setField(self::FIELD_ServiceName, $strServiceName);
        $credential->setField(self::FIELD_ServiceURL, $strServiceURL);
        $credential->setField(self::FIELD_AccessToken, $strAccessToken);
        return $credential;
    }

    public function getServiceName()
    {
        return $this->getField(self::FIELD_ServiceName, null);
    }

    public function getServiceURL()
    {
        return $this->getField(self::FIELD_ServiceURL, null);
    }

    public function getAccessToken()
    {
        return $this->getField(self::FIELD_AccessToken, null);
    }
}
