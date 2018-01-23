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

namespace Chaplin;

use Chaplin\Config\Gateways as ConfigGateways;
use Chaplin\Interfaces\Singleton as SingletonInterface;
use Chaplin\Traits\Singleton as SingletonTrait;
use Exception;

class Gateway implements SingletonInterface
{
    use SingletonTrait;

    public function getGateway($strName)
    {
        $configGateways = ConfigGateways::getInstance();
        $strDaoType = $configGateways->getDaoType($strName);
        $param = $configGateways->getParam($strName);

        $strClassEnd = str_replace("_", "\\", $strName);

        $strGatewayClass = 'Chaplin\\Gateway\\'.$strClassEnd;

        if (!is_null($configGateways->getDaoName($strName))) {
            $strName = $configGateways->getDaoName($strName);
        }
        if (is_null($strDaoType)) {
            throw new Exception('Dao Type is null for '.$strName);
        }
        $strDaoClass = 'Chaplin\\Dao\\'.$strDaoType.'\\'.$strClassEnd;

        if (!class_exists($strGatewayClass)) {
            throw new Exception('Class does not exist: '.$strGatewayClass);
        }
        if (!class_exists($strDaoClass)) {
            throw new Exception('Class does not exist: '.$strDaoClass);
        }

        $dao = new $strDaoClass($param);
        return new $strGatewayClass($dao);
    }

    public function __call($strMethod, array $arrParams)
    {
        if ('get' != substr($strMethod, 0, 3)) {
            throw new Exception('Invalid method: '.__CLASS__.'::'.$strMethod);
        }
        $strGatewayType = substr($strMethod, 3);
        return $this->getGateway($strGatewayType);
    }

    public static function __callStatic(string $strMethod, array $arrParams)
    {
        return call_user_func_array(
            [
                self::getInstance(),
                $strMethod
            ],
            $arrParams
        );
    }
}
