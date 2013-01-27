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
abstract class Chaplin_Config_Abstract
{
    private static $_arrInstances;
    
    protected $_zendConfig;
    
    public static function getInstance()
    {
        $strClass = get_called_class();
        if(isset(self::$_arrInstances[$strClass])) {
           return self::$_arrInstances[$strClass];
        }
        
        $instance = new $strClass();
        self::$_arrInstances[$strClass] = $instance;
        return $instance;
    }
     
    public static function inject(Chaplin_Config_Abstract $mockInstance)
    {
        $strClass = get_called_class();
        self::$_arrInstances[$strClass] = $mockInstance;
        return $mockInstance;
    }

    public static function reset()
    {
        self::$_arrInstances = array();
    }
    
    private function __construct()
    {
        $strConfigFile = $this->_getConfigFile();
        if(!file_exists($strConfigFile)) {
            throw new Exception($strConfigFile);
        }

        $strConfigClass = 'Zend_Config_'.$this->_getConfigType();
        
        if(!class_exists($strConfigClass)) {
            throw new Exception('Config class '.$strConfigClass.' does not exist');
        }

        $this->_zendConfig = new $strConfigClass(
            $strConfigFile,
            APPLICATION_ENV
        );
    }
    
    abstract protected function _getConfigFile();
    
    abstract protected function _getConfigType();

    protected function _getValue($strValue, $strKey)
    {
        if(is_null($strValue)) {
            throw new Exception(
                'Nonexistent key: '.$strKey.' on '.APPLICATION_ENV
            );
        }
        
        return $strValue;
    }

    protected function _getOptionalValue($strValue, $mixedDefault)
    {
        if(is_null($strValue)) {
            return $mixedDefault;
        }
        
        return $strValue;
    }
}
