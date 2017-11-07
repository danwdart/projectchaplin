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
 * @author    Kathie Dart <chaplin@kathiedart.uk>
 * @copyright 2012-2017 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   GIT: $Id$
 * @link      https://github.com/kathiedart/projectchaplin
**/
class Chaplin_Service_Encoder_API
{
    const THUMBNAIL_DEFAULT_TIME = 0;

    const CMD_CONVERT_FILE = 'ffmpeg -threads 9 -i "%s" "%s" 2>&1';
    const CMD_GET_THUMBNAIL = 'ffmpeg -i "%s" -f image2 -vframes 1 -ss %s "%s"'.
        ' 2>&1'; 

    public function convertFile($strFile, $strOut, $ret)
    {
        //TODO: stream status
        $strCommand = sprintf(
            self::CMD_CONVERT_FILE,
            $strFile,
            $strOut
        );
        echo $strCommand.PHP_EOL;
        ob_flush();
        //die(var_dump($strCommand));
        return system($strCommand, $ret);
    }

    public function getThumbnail($strFile, $strOut, $ret)
    {
        $strCommand = sprintf(
            self::CMD_GET_THUMBNAIL,
            $strFile,
            self::THUMBNAIL_DEFAULT_TIME,
            $strOut
        );
        //echo $strCommand.PHP_EOL;
        //ob_flush();
        //die(var_dump($strCommand));
        return system($strCommand, $ret);
    }
}
