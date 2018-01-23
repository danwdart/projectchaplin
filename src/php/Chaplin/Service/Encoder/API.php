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

namespace Chaplin\Service\Encoder;

class API
{
    const THUMBNAIL_DEFAULT_TIME = 0;

    const CMD_CONVERT_FILE = 'ffmpeg -y -threads 12 -i "%s" "%s" 2>&1';
    const CMD_GET_THUMBNAIL = 'ffmpeg -y -i "%s" -f image2 -vframes 1 -ss %s "%s"'.
        ' 2>&1';

    public function convertFile($strFile, $strOut, $ret)
    {
        if ("true" === getenv("NO_UPLOADS")) {
            return;
        }

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
        if ("true" === getenv("NO_UPLOADS")) {
            return;
        }

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
