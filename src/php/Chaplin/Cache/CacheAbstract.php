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
namespace Chaplin\Cache;

use Zend_Cache_Core as ZendCache;

abstract class CacheAbstract
{
    private $_zendCache;

    public function setCache(ZendCache $zendCache = null)
    {
        $this->_zendCache    = $zendCache;
    }

    protected function _cacheLoadKey($strKey)
    {
        if (is_null($this->_zendCache)) {
            return false;
        }
        return $this->_zendCache->load($strKey);
    }

    protected function _cacheSaveKey($strKey, $mixedValue)
    {
        if (is_null($this->_zendCache)) {
            return false;
        }
        return $this->_zendCache->save($mixedValue, $strKey);
    }

    protected function _getCacheKey($strMethod, $strString)
    {
        return preg_replace('/[^a-zA-Z0-9_]/', '', $strMethod.$strString);
    }
}
