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
class Chaplin_Model_User_Credential extends Chaplin_Model_Field_Collection
{
    const FIELD_ServiceName = 'ServiceName';
    const FIELD_ServiceURL  = 'ServiceURL';
    const FIELD_AccessToken = 'AccessToken';

    protected $_arrFields = array(
        self::FIELD_ServiceName => 'Chaplin_Model_Field_Field',
        self::FIELD_ServiceURL  => 'Chaplin_Model_Field_Field',
        self::FIELD_AccessToken => 'Chaplin_Model_Field_Field'
    );

    public static function create(Chaplin_Model_User $modelUser, $strServiceName, $strServiceURL, $strAccessToken)
    {
        $credential = new self($modelUser);
        $credential->_setField(self::FIELD_ServiceName, $strServiceName);
        $credential->_setField(self::FIELD_ServiceURL, $strServiceURL);
        $credential->_setField(self::FIELD_AccessToken, $strAccessToken);
        return $credential;
    }

    public function getServiceName()
    {
        return $this->_getField(self::FIELD_ServiceName, null);
    }

    public function getServiceURL()
    {
        return $this->_getField(self::FIELD_ServiceURL, null);
    }

    public function getAccessToken()
    {
        return $this->_getField(self::FIELD_AccessToken, null);
    }
}
