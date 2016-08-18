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
class Chaplin_Model_Video_Youtube
    extends Chaplin_Model_Field_Hash
    implements Chaplin_Model_Interface_Message
{
    const FIELD_YTID = 'YTId';
    const FIELD_VIDEOID = 'VideoId';

    protected $_arrFields = [
        self::FIELD_YTID => ['Class' => 'Chaplin_Model_Field_Field'],
        self::FIELD_VIDEOID => ['Class' => 'Chaplin_Model_Field_Field']
    ];

    public static function create(Chaplin_Model_Video $modelVideo, $strYouTubeId)
    {
        $msgTest = new self();
        $msgTest->_setField(self::FIELD_VIDEOID, $modelVideo->getVideoId());
        $msgTest->_setField(self::FIELD_YTID, $strYouTubeId);
        return $msgTest;
    }

    private function _getYouTubeId()
    {
        return $this->_getField(self::FIELD_YTID, null);
    }

    public function process()
    {
        echo 'Downloading '.$this->_getYouTubeId().PHP_EOL;
        ob_flush();
        flush();

        $strPathToDownloadTo = realpath(APPLICATION_PATH.'/../public/uploads');

        $strOut = Chaplin_Service::getInstance()
            ->getYoutube()
            ->downloadVideo($this->_getYouTubeId(), $strPathToDownloadTo, $ret);

        echo $strOut;
        ob_flush();
        flush();
        if (0 == $ret) {
            echo 'Downloaded '.$this->_getYouTubeId().PHP_EOL;
        } else {
            echo 'Failed to download '.$this->_getYouTubeId().' because '.$strOut;
            throw new \Exception('Failed to download '.$this->_getYouTubeId().' because '.$strOut);
        }
        ob_flush();
        flush();

        $modelVideo = Chaplin_Gateway::getInstance()
            ->getVideo()
            ->getByVideoId($this->_getField(self::FIELD_VIDEOID, null));

        try {
            Chaplin_Gateway::getEmail()
                ->videoFinished($modelVideo);
            echo '"Video Finished" email successfully sent.'.PHP_EOL;
        } catch (Exception $e) {
            echo '"Video Finished" email could not be sent because '.$e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL;
            ob_flush();
            flush();
        }
    }

    public function getRoutingKey()
    {
        return 'video.youtube.'.$this->_getYouTubeId();
    }

    public function getExchangeName()
    {
        return 'Video';
    }
}
