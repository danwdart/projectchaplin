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
class Chaplin_Model_Node extends Chaplin_Model_Field_Hash
{
    const FIELD_NODEID = 'NodeId';
    const FIELD_IP = 'IP';
    const FIELD_NAME = 'Name';
    const FIELD_ACTIVE = 'Active';

    protected $_arrFields = array(
        self::FIELD_NODEID => array('Class' => 'Chaplin_Model_Field_FieldId'),
        self::FIELD_IP => array('Class' => 'Chaplin_Model_Field_Field'),
        self::FIELD_NAME => array('Class' => 'Chaplin_Model_Field_Field'),
        self::FIELD_ACTIVE => array('Class' => 'Chaplin_Model_Field_Field')
    );

    public static function create($strIP, $strName)
    {
        $node = new self();
        $node->_bIsNew = true;
        $node->_setField(self::FIELD_NODEID, md5(uniqid()));
        $node->_setField(self::FIELD_IP, $strIP);
        $node->_setField(self::FIELD_NAME, $strName);
        $node->_setField(self::FIELD_ACTIVE, false);
        return $node;
    }

    public function getId()
    {
        return $this->getNodeId();
    }

    public function getNodeId()
    {
        return $this->_getField(self::FIELD_NODEID, null);
    }

    public function getIP()
    {
        return $this->_getField(self::FIELD_IP, null);
    }

    public function getName()
    {
        return $this->_getField(self::FIELD_NAME, null);
    }

    public function bIsActive()
    {
        return $this->_getField(self::FIELD_ACTIVE, false);
    }

    public function getRoot()
    {
        return 'http://'.$this->getIP();
    }

    public function getStatusURL()
    {
        return $this->getRoot().'/admin/nodestatus?format=json';
    }

    public function ping()
    {
        $response = Chaplin_Service::getInstance()
            ->getHttpClient()
            ->getHttpResponse($this->getStatusURL(), null, false);
        if(200 == $response->getStatus()) {
            $this->_setField(self::FIELD_ACTIVE, true);
            $this->save();
            return true;
        }
        return false;
    }

    private function _get($url)
    {
        $strURL = $this->getRoot().$url;
        try {
            return Chaplin_Service::getInstance()
                ->getHttpClient()
                ->getObject($strURL);
        } catch (Zend_Http_Client_Adapter_Exception $e) {
            return [];
        }
    }

    public function getVideoById($strVideoId)
    {
        // todo header
        $arrVideo = $this->_get('/video/watch/id/'.$strVideoId.'?format=json');
        return Chaplin_Model_Video::createFromAPIResponse($arrVideo, $this->getRoot());
    }

    public function getFeaturedVideos()
    {
        $arrVideo = $this->_get('/?format=json');
        return new Chaplin_Iterator_Api_ModelArray('Chaplin_Model_Video', $this->getRoot(), $arrVideo);
    }

    public function delete()
    {
        return Chaplin_Gateway::getInstance()->getNode()->delete($this);
    }

    public function save()
    {
        return Chaplin_Gateway::getInstance()->getNode()->save($this);
    }
}
