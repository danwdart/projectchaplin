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
use Chaplin\Cache\Backend\PhpRedis as BackendRedis;
use Chaplin\Cache\Http\Client as CacheHttpClient;
use Chaplin\Interfaces\Singleton as SingletonInterface;
use Chaplin\Traits\Singleton as SingletonTrait;

class Chaplin_Service implements SingletonInterface
{
    use SingletonTrait;

    const LIFETIME_SECS = 1800;

    private $_zendCache;

    private function _getCache()
    {
        if (is_null($this->_zendCache)) {
            //@TODO - probably put this in a config file
            $frontendOptions = [
                'lifetime' => self::LIFETIME_SECS,
                'automatic_serialization' => true
            ];
            if (Zend_Registry::isRegistered(
                Chaplin_Dao_PhpRedis_Abstract::DEFAULT_REGISTRY_KEY
            )
            ) {
                $backendOptions = [
                    'phpredis' => Zend_Registry::get(
                        Chaplin_Dao_PhpRedis_Abstract::DEFAULT_REGISTRY_KEY
                    )
                ];
                $backendType = new BackendRedis(
                    $backendOptions
                );
            } else {
                $backendOptions = [
                    'cache_dir' => APPLICATION_PATH.'/../temp/'
                ];
                $backendType = 'File';
            }

            $this->_zendCache = Zend_Cache::factory(
                'Core',
                $backendType,
                $frontendOptions,
                $backendOptions
            );
        }
        return $this->_zendCache;
    }

    public function setCache(Zend_Cache $zendCache)
    {
        $this->_zendCache   = $zendCache;
    }

    public function getHttpClient()
    {
        $objClient = new Chaplin_Http_Client();
        $objCache  = new CacheHttpClient(
            $objClient, $this->_getCache()
        );
        return new Chaplin_Service_Http_Client($objCache);
    }

    public function getEncoder()
    {
        return new Chaplin_Service_Encoder_API();
    }

    public function getYouTube()
    {
        return new Chaplin_Service_YouTube_API();
    }

    public function getVimeo()
    {
        return new Chaplin_Service_Vimeo_API();
    }
}
