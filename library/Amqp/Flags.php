<?php
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
