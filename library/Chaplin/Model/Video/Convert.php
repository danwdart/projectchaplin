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
class Chaplin_Model_Video_Convert
    extends Chaplin_Model_Field_Hash
    implements Chaplin_Model_Interface_Message
{
    const FIELD_VIDEOID = 'VideoId';

    private $_modelVideo;

    protected $_arrFields = [
        self::FIELD_VIDEOID => ['Class' => 'Chaplin_Model_Field_Field']
    ];

    public static function create(Chaplin_Model_Video $modelVideo)
    {
        $msgVideo = new self();
        $msgVideo->_setField(self::FIELD_VIDEOID, $modelVideo->getVideoId());
        $msgVideo->_modelVideo = $modelVideo;
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

    public function process()
    {
        $modelVideo = Chaplin_Gateway::getInstance()
            ->getVideo()
            ->getByVideoId($this->_getField(self::FIELD_VIDEOID, null));

        $strFullPath = APPLICATION_PATH.'/../public';

        $strFilenameRawFullPath = $strFullPath.$modelVideo->getFilename();

        echo 'Converting '.$strFilenameRawFullPath.PHP_EOL;
        ob_flush();
        flush();

        $strPathToWebm = $strFullPath.$modelVideo->getFilename().'.webm';

        $ret = 0;

        $strError = Chaplin_Service::getInstance()
            ->getEncoder()
            ->convertFile($strFilenameRawFullPath, $strPathToWebm, $ret);

        if(0 != $ret) {
            throw new Exception('Unable to convert: '.$strFilenameRawFullPath);
        }

        echo 'Converted '.$strFilenameRawFullPath;
        ob_flush();
        flush();

        unlink($strFilenameRawFullPath);

        $modelVideo->setFilename($modelVideo->getFilename().'.webm');
        $modelVideo->save();

        try {
            Chaplin_Gateway::getEmail()
                ->videoFinished($modelVideo);
            echo '"Video Finished" email successfully sent.'.PHP_EOL;
        } catch (Exception $e) {
            echo '"Video Finished" email could not be sent because '.$e->getMessage().PHP_EOL.$e->getTraceAsString();
            ob_flush();
            flush();
        }
    }

    public function getRoutingKey()
    {
        return 'video.convert.'.$this->_getModelVideo()->getUsername();
    }

    public function getExchangeName()
    {
        return 'Video';
    }
}
