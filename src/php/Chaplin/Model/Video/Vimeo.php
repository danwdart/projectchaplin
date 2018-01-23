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

namespace Chaplin\Model\Video;

use Chaplin\Model\Field\Hash;
use Chaplin\Model\Interfaces\Message;
use Chaplin\Model\Video;
use Chaplin\Service;
use Chaplin\Gateway;
use Exception;

class Vimeo extends Hash implements Message
{
    const FIELD_VIMEOID = 'VimeoId';
    const FIELD_VIDEOID = 'VideoId';

    protected $_arrFields = [
        self::FIELD_VIMEOID => ['Class' => 'Chaplin\\Model\\Field\\Field'],
        self::FIELD_VIDEOID => ['Class' => 'Chaplin\\Model\\Field\\Field']
    ];

    public static function create(Video $modelVideo, $strVimeoId)
    {
        $msgTest = new self();
        $msgTest->_setField(self::FIELD_VIDEOID, $modelVideo->getVideoId());
        $msgTest->_setField(self::FIELD_VIMEOID, $strVimeoId);
        return $msgTest;
    }

    private function _getVimeoId()
    {
        return $this->_getField(self::FIELD_VIMEOID, null);
    }

    public function process()
    {
        echo 'Downloading '.$this->_getVimeoId().PHP_EOL;
        ob_flush();
        flush();

        // phpstan
        $ret = null;
        $strOut = Service::getInstance()
            ->getVimeo()
            ->downloadVideo($this->_getVimeoId(), getenv("UPLOADS_PATH"), $ret);

        echo $strOut;
        ob_flush();
        flush();
        if (0 == $ret) {
            echo 'Downloaded '.$this->_getVimeoId().PHP_EOL;
        } else {
            echo 'Failed to download '.$this->_getVimeoId().PHP_EOL;
            throw new \Exception('Failed to download '.$this->_getVimeoId().' because: '.$strOut);
        }
        ob_flush();
        flush();

        $modelVideo = Gateway::getInstance()
            ->getVideo()
            ->getByVideoId($this->_getField(self::FIELD_VIDEOID, null));

        try {
            Gateway::getEmail()
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
        return 'video.vimeo.'.$this->_getVimeoId();
    }

    public function getExchangeName()
    {
        return 'Video';
    }
}
