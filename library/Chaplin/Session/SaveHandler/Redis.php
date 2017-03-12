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
 * @author    Ivan Shumkov
 * @author    Kathie Dart <chaplin@kathiedart.uk>
 * @copyright 2012-2017 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   GIT: $Id$
 * @link      https://github.com/kathiedart/projectchaplin
**/
/**
 * This file was partially ripped off from Rediska
 * The original author was Ivan Shumkov
 * The original licence was BSD:
 * http://www.opensource.org/licenses/bsd-license.php
 */
class Chaplin_Session_SaveHandler_Redis
    implements Zend_Session_SaveHandler_Interface
{
    const c_ZNAME = 'Chaplin_SessionSet';

    /**
   * PhpRedis instance
   *
   * @var PhpRedis
  **/
    protected $_phpredis;

    /**
   * Sessions set
   *
   * @var Chaplin_Session_Set
  **/
    protected $_set;

    /**
   * Configuration
   *
   * @var array
  **/
    protected $_options = array(
    'keyprefix' => 'PHPSESSIONS_',
    'lifetime'  => null,
    'registrykey' => null
    );

    /**
   * Construct save handler
   *
   * @param Zend_Config|array $options
  **/
    public function __construct($options = array())
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        $configSessions = Chaplin_Config_Sessions::getInstance();

        // Set default lifetime
        $this->_options['lifetime'] =
            (int)$configSessions->getRememberMeSeconds();

        // Set default keyprefix
        $this->_options['keyprefix'] =
            (int)$configSessions->getName();

        $this->setOptions($options);

        if (is_null($options['registrykey'])) {
            throw new Exception('registrykey is not set');
        }
        $this->_phpredis = Zend_Registry::get($options['registrykey']);

        foreach ($this->_options as $name => $value) {
            if (isset($options[$name])) {
                unset($options[$name]);
            }
        }

        $this->_set = array();
    }

    /**
   * Destructor
   *
   * @return void
  **/
    public function __destruct()
    {
        Zend_Session::writeClose();
    }

    /**
   * Open Session
   *
   * @param  string $savePath
   * @param  string $name
   * @return boolean
  **/
    public function open($savePath, $name)
    {
        return true;
    }

    /**
   * Close session
   *
   * @return boolean
  **/
    public function close()
    {
        return true;
    }

    /**
   * Read session data
   *
   * @param  string $id
   * @return string
  **/
    public function read($id)
    {
        return $this->_phpredis->get($this->_getKeyName($id));
    }

    /**
   * Write session data
   *
   * @param  string $id
   * @param  string $data
   * @return boolean
  **/
    public function write($id, $data)
    {
        $this->_phpredis->zAdd(
            self::c_ZNAME,
            1,
            $id
        );

        $reply = $this->_phpredis->set($this->_getKeyName($id), $data);

        if ($reply) {
            $this->_phpredis->expire(
                $this->_getKeyName($id),
                $this->_options['lifetime']
            );
        }

        return $reply;
    }

    /**
   * Destroy session
   *
   * @param  string $id
   * @return boolean
  **/
    public function destroy($id)
    {
        $this->_phpredis->zDelete(
            self::c_ZNAME,
            $id
        );

        return $this->_phpredis->delete($this->_getKeyName($id));
    }

    /**
   * Garbage Collection
   *
   * @param  int $maxlifetime
   * @return true
  **/
    public function gc($maxlifetime)
    {
        // Gets all the elements at this ordered set.
        // Returns index-array(val1,val2)
        $sessions = $this->_phpredis->zRange(self::c_ZNAME, 0, -1);
        foreach ($sessions as &$session) {
            // Converts the sessions array into an array(key1,key2)
            // (keys are prefix + val)
            $session = $this->_getKeyName($session);
        }

        // TODO: May by use TTL? Need benchmark.

        // This is the wrong thing to use. A correct command would be something
        // that is called with array(key1,key2) and returns array(key1 => val1)
        // and no val2 if it doesn't exist
        //$lifeSession = $this->_phpredis->get($sessions);

        foreach ($sessions as $session) {
            if (false == $this->_phpredis->get($session)) {
                // If it doesn't exist anymore in our range, delete it!
                $sessionWithoutPrefix = substr(
                    $session,
                    strlen($this->_options['keyprefix'])
                );
                $this->_phpredis->delete($sessionWithoutPrefix);
            }
        }

        return true;
    }

    /**
   * Set options array
   *
   * @param  array $options Options (see $_options description)
   * @return Chaplin_Session_SaveHandler_Redis
  **/
    public function setOptions(array $options)
    {
        foreach ($options as $name => $value) {
            if (method_exists($this, "set$name")) {
                call_user_func(array($this, "set$name"), $value);
            } else {
                $this->setOption($name, $value);
            }
        }

        return $this;
    }

    /**
   * Set option
   *
   * @throws Zend_Session_SaveHandler_Exception
   * @param  string $name  Name of option
   * @param  mixed  $value Value of option
   * @return Chaplin_Session_SaveHandler_Redis
  **/
    public function setOption($name, $value)
    {
        $lowerName = strtolower($name);

        if (!array_key_exists($lowerName, $this->_options)) {
            throw new Zend_Session_SaveHandler_Exception(
                "Unknown option '$name'"
            );
        }

        $this->_options[$lowerName] = $value;

        return $this;
    }

    /**
   * Get option
   *
   * @param  string $name Name of option
   * @return mixed
  **/
    public function getOption($name)
    {
        $lowerName = strtolower($name);

        if (!array_key_exists($lowerName, $this->_options)) {
            throw new Zend_Session_SaveHandler_Exception(
                "Unknown option '$name'"
            );
        }

        return $this->_options[$lowerName];
    }

    /**
   * Set PhpRedis instance
   *
   * @param  PhpRedis $phpredis
   * @return Chaplin_Session_SaveHandler_Redis
  **/
    public function setPhpRedis(Redis $phpredis)
    {
        $this->_phpredis = $phpredis;

        return $this;
    }

    /**
   * Get PhpRedis instance
   *
   * @return PhpRedis
  **/
    public function getPhpRedis()
    {
        return $this->_phpredis;
    }

    /**
   * Add prefix to session name
   *
   * @param  string $id
   * @return string
  **/
    protected function _getKeyName($id)
    {
        return $this->_options['keyprefix'] . $id;
    }
}
