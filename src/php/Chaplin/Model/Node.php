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

namespace Chaplin\Model;

use Chaplin\Model\Field\Hash;
use Chaplin\Service;
use Exception;
use Zend_Http_Client_Adapter_Exception;
use Chaplin\Model\Video;
use Chaplin\Iterator\Api\ModelArray;
use Chaplin\Gateway;

class Node extends Hash
{
    const FIELD_NODEID = 'NodeId';
    const FIELD_IP = 'IP';
    const FIELD_NAME = 'Name';
    const FIELD_ACTIVE = 'Active';

    protected $_arrFields = array(
        self::FIELD_NODEID => array('Class' => 'Chaplin\\Model\\Field\\FieldId'),
        self::FIELD_IP => array('Class' => 'Chaplin\\Model\\Field\\Field'),
        self::FIELD_NAME => array('Class' => 'Chaplin\\Model\\Field\\Field'),
        self::FIELD_ACTIVE => array('Class' => 'Chaplin\\Model\\Field\\Field')
    );

    public static function create($strIP, $strName)
    {
        $node = new self();
        $node->_bIsNew = true;
        $node->_setField(self::FIELD_NODEID, md5(uniqid()));
        $node->_setField(self::FIELD_IP, $strIP);
        $node->_setField(self::FIELD_NAME, $strName);
        $node->_setField(self::FIELD_ACTIVE, 0);
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
        try {
            $response = Service::getInstance()
                ->getHttpClient()
                ->getHttpResponse($this->getStatusURL(), null, 0);
            if (200 == $response->getStatus()) {
                $this->_setField(self::FIELD_ACTIVE, 1);
                $this->save();
                return true;
            }
        } catch (Exception $e) {
            $this->_setField(self::FIELD_ACTIVE, 0);
            $this->save();
            return false;
        }
        return false;
    }

    private function _get($url)
    {
        $strURL = $this->getRoot().$url;
        try {
            return Service::getInstance()
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
        return Video::createFromAPIResponse($arrVideo, $this->getRoot());
    }

    public function getFeaturedVideos()
    {
        $arrVideo = $this->_get('/?format=json');
        return new ModelArray("Chaplin\\Model\\Video", $this->getRoot(), $arrVideo);
    }

    public function delete()
    {
        return Gateway::getInstance()->getNode()->delete($this);
    }

    public function save()
    {
        return Gateway::getInstance()->getNode()->save($this);
    }
}
