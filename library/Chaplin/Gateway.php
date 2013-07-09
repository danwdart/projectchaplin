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
class Chaplin_Gateway
{
    private static $_instance;
    
    private function __clone()
    {
    }

    private function __construct()
    {
    }

    public static function __callStatic($strMethod, Array $arrArgs)
    {
        return call_user_func_array([self::getInstance(), $strMethod], $arrArgs);
    }

    public static function getInstance()
    {
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function inject(Chaplin_Gateway $gateway)
    {
        self::$_instance = $gateway;
    }

    public function getGateway($strName)
    {
        $configGateways = Chaplin_Config_Gateways::getInstance();
        $strDaoType = $configGateways->getDaoType($strName);
        $param = $configGateways->getParam($strName);

        $strGatewayClass = 'Chaplin_Gateway_'.$strName;
        
        if (!is_null($configGateways->getDaoName($strName))) {
            $strName = $configGateways->getDaoName($strName);
        }
        if (is_null($strDaoType)) {
            throw new Exception('Dao Type is null for '.$strName);
        }
        $strDaoClass = 'Chaplin_Dao_'.$strDaoType.'_'.$strName;
        
        if (!class_exists($strGatewayClass)) {
            throw new Exception('Class does not exist: '.$strGatewayClass);
        }
        if (!class_exists($strDaoClass)) {
            throw new Exception('Class does not exist: '.$strDaoClass);
        }

        $dao = new $strDaoClass($param);
        return new $strGatewayClass($dao);
    }

    public function __call($strMethod, Array $arrParams)
    {
        if ('get' != substr($strMethod, 0, 3)) {
            throw new Exception('Invalid method: '.__CLASS__.'::'.$strMethod);
        }
        $strGatewayType = substr($strMethod, 3);
        return $this->getGateway($strGatewayType);        
    }
}
