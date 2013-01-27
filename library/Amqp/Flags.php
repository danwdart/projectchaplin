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
class Amqp_Flags
{
    const NOPARAM = AMQP_NOPARAM;
    const DURABLE = AMQP_DURABLE;
    const PASSIVE = AMQP_PASSIVE;
    const EXCLUSIVE = AMQP_EXCLUSIVE;
    const AUTODELETE = AMQP_AUTODELETE;
    const INTERNAL = AMQP_INTERNAL;
    const NOLOCAL = AMQP_NOLOCAL;
    const AUTOACK = AMQP_AUTOACK;
    const IFEMPTY = AMQP_IFEMPTY;
    const IFUNUSED = AMQP_IFUNUSED;
    const MANDATORY = AMQP_MANDATORY;
    const IMMEDIATE = AMQP_IMMEDIATE;
    const MULTIPLE = AMQP_MULTIPLE;
    const NOWAIT = AMQP_NOWAIT;
    
    public static function getFlags(Array $arrFlags)
    {
        $ret = 0;
        
        foreach($arrFlags as $strFlag => $intValue) {
            if ($intValue) {
                switch($strFlag) {
                    case 'Durable':
                        $ret |= self::DURABLE;
                        break;
                    case 'AutoDelete':
                        $ret |= self::AUTODELETE;
                        break;
                    default:
                        throw new Exception('Unhandled Flag: '.$strFlag);
                }
            }
        }
        
        return $ret;
    }   
}
