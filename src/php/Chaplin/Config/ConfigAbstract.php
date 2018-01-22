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
namespace Chaplin\Config;

use Chaplin\Config\Exception\{
    ConfigClassNotFound as ConfigClassNotFoundException,
    FileLinkNotFound as FileLinkNotFoundException,
    FileNotFound as FileNotFoundException,
    NonexistentKey as NonexistentKeyException,
    UnknownConfigFile as UnknownConfigFileException
};
use Chaplin\Interfaces\MultiSingleton as MultiSingletonInterface;
use Chaplin\Traits\MultiSingleton as MultiSingletonTrait;

abstract class ConfigAbstract implements MultiSingletonInterface
{
    use MultiSingletonTrait;

    const CONFIG_TEMPLATE = "Zend_Config_";

    protected $_zendConfig;

    private function __construct()
    {
        $strConfigFile = $this->_getConfigFile();

        if (empty($strConfigFile)) {
            throw new UnknownConfigFileException(get_class($this));
        }

        if (!realpath($strConfigFile)) {
            throw new FileLinkNotFoundException($strConfigFile);
        }

        $strConfigFile = realpath($strConfigFile);

        if(!file_exists($strConfigFile)) {
            throw new FileNotFoundException($strConfigFile);
        }

        $strConfigClass = self::CONFIG_TEMPLATE.ucwords($this->_getConfigType());

        if(!class_exists($strConfigClass)) {
            throw new ConfigClassNotFoundException($strConfigClass);
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
            throw new NonexistentKeyException($strKey, APPLICATION_ENV);
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
