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
class Chaplin_Model_Video_Import
    extends Chaplin_Model_Field_Hash
    implements Chaplin_Model_Interface_Message
{
    const FIELD_VIDEOID = 'VideoId';
    const FIELD_NODEID = 'NodeId';

    private $_modelVideo;
    private $_modelNode;

    protected $_arrFields = [
        self::FIELD_VIDEOID => ['Class' => 'Chaplin_Model_Field_Field'],
        self::FIELD_NODEID => ['Class' => 'Chaplin_Model_Field_Field']
    ];

    public static function create(Chaplin_Model_Video $modelVideo, Chaplin_Model_Node $modelNode)
    {
        $msgVideo = new self();
        $msgVideo->_setField(self::FIELD_VIDEOID, $modelVideo->getVideoId());
        $msgVideo->_setField(self::FIELD_NODEID, $modelNode->getNodeId());
        $msgVideo->_modelVideo = $modelVideo;
        $msgVideo->_modelNode = $modelNode;
        return $msgVideo;
    }

    public function getId()
    {
        return $this->_getField(self::FIELD_VIDEOID, null);
    }

    private function _getModelVideo()
    {
        if (is_null($this->_modelVideo)) {
            $this->_modelVideo = Chaplin_Gateway::getInstance()
            ->getVideo()
            ->getByVideoId($this->_getField(self::FIELD_VIDEOID, null));
        }
        return $this->_modelVideo;
    }

    private function _getModelNode()
    {
        if (is_null($this->_modelNode)) {
            $this->_modelNode = Chaplin_Gateway::getInstance()
            ->getNode()
            ->getByNodeId($this->_getField(self::FIELD_NODEID, null));
        }
        return $this->_modelNode;
    }
    
    public function process()
    {
        $modelVideo = $this->_getModelVideo();
        $modelNode = $this->_modelNode();
        
        echo 'Importing '.$modelVideo->getTitle().' from '.$modelNode->getIP().PHP_EOL;
        ob_flush();
        flush();

        Chaplin_Service::getInstance()->getDownload()->importFromNode($modelNode, $modelVideo);

        try {
            Chaplin_Gateway::getEmail()
                ->videoFinished($modelVideo);
        } catch (Exception $e) {
            echo 'Video Finished Email could not be sent.';
            ob_flush();
            flush();
        }
    }
    
    public function getRoutingKey()
    {
        return 'video.import.'.$this->_getModelVideo()->getUsername();
    }

    public function getExchangeName()
    {
        return 'Video';
    }
}    
