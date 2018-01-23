<?php
/**
 * @see Zend_Cache_Backend
**/
// require_once 'Zend/Cache/Backend.php';

/**
 * @see Zend_Cache_Backend_ExtendedInterface
**/
// require_once 'Zend/Cache/Backend/ExtendedInterface.php';

/**
 * Redis adapter for Zend_Cache
 *
 * @author  Soin Stoiana
 * @author  Colin Mollenhour
 * @version 0.0.1
**/
namespace Chaplin\Cache\Backend;

use Redis;
use Zend_Cache as Cache;
use Zend_Cache_Backend as Backend;
use Zend_Cache_Backend_ExtendedInterface as BackendInterface;
use Zend_Config as Config;

class PhpRedis extends Backend implements BackendInterface
{

    const SET_IDS  = 'zc:ids';
    const SET_TAGS = 'zc:tags';

    const PREFIX_DATA     = 'zc:d:';
    const PREFIX_MTIME    = 'zc:m:';
    const PREFIX_TAG_IDS  = 'zc:ti:';
    const PREFIX_ID_TAGS  = 'zc:it:';

    /**
 * @var Redis
**/
    protected $redis;

    protected $options;

    /**
 * @var bool
**/
    protected $notMatchingTags = false;

    /**
 * @var bool
**/
    protected $exactMtime = false;

    /**
     * Contruct Zend_Cache Redis backend
     *
     * @param  array $options
    **/
    public function __construct($options = array())
    {
        if ($options instanceof Config) {
            $options = $options->toArray();
        }

        if (isset($options['phpredis'])) {
            $this->redis = $options['phpredis'];
        } else {
            if (empty($options['server'])) {
                Cache::throwException('Redis \'server\' not specified.');
            }

            if (empty($options['port'])) {
                Cache::throwException('Redis \'port\' not specified.');
            }

            $this->redis = new Redis;
            if (! $this->redis->connect($options['server'], $options['port'])) {
                Cache::throwException("Could not connect to Redis server {$options['server']}:{$options['port']}");
            }
        }

        if (! empty($options['database'])) {
            $this->redis->select((int) $options['database']) or
              Cache::throwException('The redis database could not be selected.');
        }

        if (isset($options['notMatchingTags'])) {
            $this->notMatchingTags = (bool) $options['notMatchingTags'];
        }

        if (isset($options['exactMtime'])) {
            $this->exactMtime = (bool) $options['exactMtime'];
        }

        if (isset($options['automatic_cleaning_factor'])) {
            $this->options['automatic_cleaning_factor'] = (int) $options['automatic_cleaning_factor'];
        } else {
            $this->options['automatic_cleaning_factor'] = 20000;
        }
    }
    /**
     * Load value with given id from cache
     *
     * @param  string  $id                     Cache id
     * @param  boolean $doNotTestCacheValidity If set to true, the cache validity won't be tested
     * @return string|false cached datas
    **/
    public function load($id, $doNotTestCacheValidity = false)
    {
        $data = $this->redis->get(self::PREFIX_DATA . $id);
        if ($data === null) {
            return false;
        }
        return $data;
    }

    /**
     * Test if a cache is available or not (for the given id)
     *
     * @param  string $id Cache id
     * @return bool|int False if record is not available or "last modified" timestamp of the available cache record
    **/
    public function test($id)
    {
        if ($this->exactMtime) {
            $mtime = $this->redis->get(self::PREFIX_MTIME . $id);
            return ($mtime ? $mtime : false);
        } else {
            $exists = $this->redis->exists(self::PREFIX_DATA . $id);
            return ($exists ? time() : false);
        }
    }

    /**
     * Save some string datas into a cache record
     *
     * Note : $data is always "string" (serialization is done by the
     * core not by the backend)
     *
     * @param  string   $data             Datas to cache
     * @param  string   $id               Cache id
     * @param  array    $tags             Array of strings, the cache record will be tagged by each string entry
     * be tagged by each string entry
     * @param  bool|int $specificLifetime If != false, set a specific lifetime
     * for this cache record (null => infinite lifetime)
     * @return boolean True if no problem
    **/
    public function save($data, $id, $tags = array(), $specificLifetime = false)
    {
        if (!is_array($tags)) {
            $tags = array($tags);
        }

        $lifetime = $this->getLifetime($specificLifetime);

        // Set the data
        if ($lifetime) {
            $result = $this->redis->setex(self::PREFIX_DATA . $id, $lifetime, $data);
        } else {
            $result = $this->redis->set(self::PREFIX_DATA . $id, $data);
        }

        if ($result == 'OK') {
            // Set the modified time
            if ($this->exactMtime) {
                if ($lifetime) {
                    $this->redis->setex(self::PREFIX_MTIME . $id, $lifetime, time());
                } else {
                    $this->redis->set(self::PREFIX_MTIME . $id, time());
                }
            }

            if (count($tags) > 0) {
                // Update the list with all the tags
                $this->redisVariadic('sAdd', self::SET_TAGS, $tags);

                // Update the list of tags for this id, expire at same time as data key
                $this->redis->del(self::PREFIX_ID_TAGS . $id);
                $this->redisVariadic('sAdd', self::PREFIX_ID_TAGS . $id, $tags);
                if ($lifetime) {
                    $this->redis->expire(self::PREFIX_ID_TAGS . $id, $lifetime);
                }

                // Update the id list for each tag
                foreach ($tags as $tag) {
                    $this->redis->sAdd(self::PREFIX_TAG_IDS . $tag, $id);
                }
            }

            // Update the list with all the ids
            if ($this->notMatchingTags) {
                $this->redis->sAdd(self::SET_IDS, $id);
            }

            return true;
        }

        return false;
    }

    /**
     * Remove a cache record
     *
     * @param  string $id Cache id
     * @return boolean True if no problem
    **/
    public function remove($id)
    {
        // Remove data
        $result = $this->redis->del(self::PREFIX_DATA . $id);

        // Remove mtime
        if ($this->exactMtime) {
            $this->redis->del(self::PREFIX_MTIME . $id);
        }

        // Remove id from list of all ids
        if ($this->notMatchingTags) {
            $this->redis->srem(self::SET_IDS, $id);
        }

        // Get list of tags for this id
        $tags = $this->redis->sMembers(self::PREFIX_ID_TAGS . $id);

        // Update the id list for each tag
        foreach ($tags as $tag) {
            $this->redis->srem(self::PREFIX_TAG_IDS . $tag, $id);
        }

        // Remove list of tags
        $this->redis->del(self::PREFIX_ID_TAGS . $id);

        return (bool) $result;
    }

    protected function removeByNotMatchingTags($tags)
    {
        $ids = $this->getIdsNotMatchingTags($tags);
        if (! $ids) {
            return;
        }

        // Remove data
        $this->redisVariadic('del', $this->preprocessIds($ids));

        // Remove mtimes
        if ($this->exactMtime) {
            $this->redisVariadic('del', $this->preprocessMtimes($ids));
        }

        // Remove ids from list of all ids
        if ($this->notMatchingTags) {
            $this->redisVariadic('srem', self::SET_IDS, $ids);
        }

        // Update the id list for each tag
        $tagsToClean = $this->redisVariadic('sUnion', $this->preprocessIdTags($ids));
        foreach ($tagsToClean as $tag) {
            $this->redisVariadic('srem', self::PREFIX_TAG_IDS . $tag, $ids);
        }

        // Remove tag lists for all ids
        $this->redisVariadic('del', $this->preprocessIdTags($ids));
    }

    protected function removeByMatchingTags($tags)
    {
        $ids = $this->getIdsMatchingTags($tags);
        if ($ids) {
            // Remove data
            $this->redisVariadic('del', $this->preprocessIds($ids));

            // Remove mtimes
            if ($this->exactMtime) {
                $this->redisVariadic('del', $this->preprocessMtimes($ids));
            }

            // Remove ids from tags not cleared
            $idTags = $this->preprocessIdTags($ids);
            $otherTags = (array) $this->redisVariadic('sUnion', $idTags);
            $otherTags = array_diff($otherTags, $tags);
            foreach ($otherTags as $tag) {
                $this->redisVariadic('srem', self::PREFIX_TAG_IDS . $tag, $ids);
            }

            // Remove tag lists for all ids
            $this->redisVariadic('del', $idTags);

            // Remove ids from list of all ids
            if ($this->notMatchingTags) {
                $this->redisVariadic('srem', self::SET_IDS, $ids);
            }
        }
    }

    protected function removeByMatchingAnyTags($tags)
    {
        $ids = $this->getIdsMatchingAnyTags($tags);
        if ($ids) {
            // Remove data
            $this->redisVariadic('del', $this->preprocessIds($ids));

            // Remove mtimes
            if ($this->exactMtime) {
                $this->redisVariadic('del', $this->preprocessMtimes($ids));
            }

            // Remove ids from tags not cleared
            $idTags = $this->preprocessIdTags($ids);
            $otherTags = (array) $this->redisVariadic('sUnion', $idTags);
            $otherTags = array_diff($otherTags, $tags);
            foreach ($otherTags as $tag) {
                $this->redisVariadic('srem', self::PREFIX_TAG_IDS . $tag, $ids);
            }

            // Remove tag lists for all ids
            $this->redisVariadic('del', $idTags);

            // Remove ids from list of all ids
            if ($this->notMatchingTags) {
                $this->redisVariadic('srem', self::SET_IDS, $ids);
            }
        }

        // Remove tag id lists
        $this->redisVariadic('del', $this->preprocessTagIds($tags));

        // Remove tags from list of tags
        $this->redisVariadic('srem', self::SET_TAGS, $tags);
    }

    protected function collectGarbage()
    {
        // Clean up expired keys from tag id set and global id set
        $exists = array();
        $tags = (array) $this->redis->sMembers(self::SET_TAGS);
        foreach ($tags as $tag) {
            $tagMembers = $this->redis->sMembers(self::PREFIX_TAG_IDS . $tag);
            if (! count($tagMembers)) {
                continue;
            }
            $expired = array();
            foreach ($tagMembers as $id) {
                if (! isset($exists[$id])) {
                    $exists[$id] = $this->redis->exists($id);
                }
                if (! $exists[$id]) {
                    $expired[] = $id;
                }
            }
            if (! count($expired)) {
                continue;
            }

            if (count($expired) == count($tagMembers)) {
                $this->redis->del(self::PREFIX_TAG_IDS . $tag);
                $this->redis->sRem(self::SET_TAGS, $tag);
            } else {
                $this->redisVariadic('sRem', self::PREFIX_TAG_IDS . $tag, $expired);
            }
            if ($this->notMatchingTags) {
                $this->redisVariadic('sRem', self::SET_IDS, $expired);
            }
        }

        // Clean up global list of ids for ids with no tag
        if ($this->notMatchingTags) {
            // TODO
        }
    }

    /**
     * Clean some cache records
     *
     * Available modes are :
     * 'all' (default)  => remove all cache entries ($tags is not used)
     * 'old'            => unsupported
     * 'matchingTag'    => supported
     * 'notMatchingTag' => supported
     * 'matchingAnyTag' => supported
     *
     * @param  string $mode Clean mode
     * @param  array  $tags Array of tags
     * @throws Zend_Cache_Exception
     * @return boolean True if no problem
    **/
    public function clean($mode = Cache::CLEANING_MODE_ALL, $tags = array())
    {
        if ($tags && ! is_array($tags)) {
            $tags = array($tags);
        }

        if ($mode == Cache::CLEANING_MODE_ALL) {
            return ($this->redis->flushDb() == 'OK');
        }

        if ($mode == Cache::CLEANING_MODE_OLD) {
            $this->collectGarbage();
            return true;
        }

        if (! count($tags)) {
            return true;
        }

        $result = true;

        switch ($mode) {
            case Cache::CLEANING_MODE_MATCHING_TAG:
                $this->removeByMatchingTags($tags);
                break;

            case Cache::CLEANING_MODE_NOT_MATCHING_TAG:
                $this->removeByNotMatchingTags($tags);
                break;

            case Cache::CLEANING_MODE_MATCHING_ANY_TAG:
                $this->removeByMatchingAnyTags($tags);
                break;

            default:
                Cache::throwException('Invalid mode for clean() method: '.$mode);
        }
        return (bool) $result;
    }

    /**
     * Return true if the automatic cleaning is available for the backend
     *
     * @return boolean
    **/
    public function isAutomaticCleaningAvailable()
    {
        return true;
    }

    /**
     * Set the frontend directives
     *
     * @param  array $directives Assoc of directives
     * @throws Zend_Cache_Exception
     * @return void
    **/
    public function setDirectives($directives)
    {
        parent::setDirectives($directives);
        $lifetime = $this->getLifetime(false);
        if ($lifetime > 2592000) {
            Cache::throwException('Redis backend has a limit of 30 days (2592000 seconds) for the lifetime');
        }
    }

    /**
     * Return an array of stored cache ids
     *
     * @return array array of stored cache ids (string)
    **/
    public function getIds()
    {
        if ($this->notMatchingTags) {
            return (array) $this->redis->sMembers(self::SET_IDS);
        } else {
            $keys = $this->redis->keys(self::PREFIX_DATA . '*');
            $prefixLen = strlen(self::PREFIX_DATA);
            foreach ($keys as &$key) {
                $key = substr($key, $prefixLen);
            }
            return $keys;
        }
    }

    /**
     * Return an array of stored tags
     *
     * @return array array of stored tags (string)
    **/
    public function getTags()
    {
        return (array) $this->redis->sMembers(self::SET_TAGS);
    }

    /**
     * Return an array of stored cache ids which match given tags
     *
     * In case of multiple tags, a logical AND is made between tags
     *
     * @param  array $tags array of tags
     * @return array array of matching cache ids (string)
    **/
    public function getIdsMatchingTags($tags = array())
    {
        return (array) $this->redisVariadic('sInter', $this->preprocessTagIds($tags));
    }

    /**
     * Return an array of stored cache ids which don't match given tags
     *
     * In case of multiple tags, a negated logical AND is made between tags
     *
     * @param  array $tags array of tags
     * @return array array of not matching cache ids (string)
    **/
    public function getIdsNotMatchingTags($tags = array())
    {
        if (! $this->notMatchingTags) {
            Cache::throwException("notMatchingTags is currently disabled.");
        }
        return (array) $this->redisVariadic('sDiff', self::SET_IDS, $this->preprocessTagIds($tags));
    }

    /**
     * Return an array of stored cache ids which match any given tags
     *
     * In case of multiple tags, a logical OR is made between tags
     *
     * @param  array $tags array of tags
     * @return array array of any matching cache ids (string)
    **/
    public function getIdsMatchingAnyTags($tags = array())
    {
        return (array) $this->redisVariadic('sUnion', $this->preprocessTagIds($tags));
    }

    /**
     * Return the filling percentage of the backend storage
     *
     * @throws Zend_Cache_Exception
     * @return int integer between 0 and 100
    **/
    public function getFillingPercentage()
    {
        return 0;
    }

    /**
     * Return an array of metadatas for the given cache id
     *
     * The array must include these keys :
     * - expire : the expire timestamp
     * - tags : a string array of tags
     * - mtime : timestamp of last modification time
     *
     * @param  string $id cache id
     * @return array array of metadatas (false if the cache id is not found)
    **/
    public function getMetadatas($id)
    {
        $mtime = $this->test($id);
        if (! $mtime) {
            return false;
        }
        $ttl = $this->redis->ttl(self::PREFIX_DATA . $id);
        $tags = (array) $this->redis->sMembers(self::PREFIX_ID_TAGS . $id);

        return array(
            'expire' => time() + $ttl,
            'tags' => $tags,
            'mtime' => $mtime,
        );
    }

    /**
     * Give (if possible) an extra lifetime to the given cache id
     *
     * @param  string $id            cache id
     * @param  int    $extraLifetime
     * @return boolean true if ok
    **/
    public function touch($id, $extraLifetime)
    {
        $ttl = $this->redis->ttl(self::PREFIX_DATA . $id);
        if ($ttl != -1) {
            $expireAt = time() + $ttl + $extraLifetime;
            $result = $this->redis->expireAt(self::PREFIX_DATA . $id, $expireAt);
            if ($result) {
                if ($this->exactMtime) {
                    $this->redis->expireAt(self::PREFIX_MTIME . $id, $expireAt);
                }
                $this->redis->expireAt(self::PREFIX_ID_TAGS . $id, $expireAt);
            }
            return (bool) $result;
        }
        return false;
    }

    /**
     * Return an associative array of capabilities (booleans) of the backend
     *
     * The array must include these keys :
     * - automatic_cleaning (is automating cleaning necessary)
     * - tags (are tags supported)
     * - expired_read (is it possible to read expired cache records
     *                 (for doNotTestCacheValidity option for example))
     * - priority does the backend deal with priority when saving
     * - infinite_lifetime (is infinite lifetime can work with this backend)
     * - get_list (is it possible to get the list of cache ids and the complete list of tags)
     *
     * @return array associative of with capabilities
    **/
    public function getCapabilities()
    {
        return array(
            'automatic_cleaning' => ($this->options['automatic_cleaning_factor'] > 0),
            'tags'               => true,
            'expired_read'       => false,
            'priority'           => false,
            'infinite_lifetime'  => true,
            'get_list'           => $this->notMatchingTags,
        );
    }

    protected function preprocess(&$item, $index, $prefix)
    {
        $item = $prefix . $item;
    }

    protected function preprocessItems($items, $prefix)
    {
        array_walk($items, array($this, '_preprocess'), $prefix);
        return $items;
    }

    protected function preprocessIds($ids)
    {
        return $this->preprocessItems($ids, self::PREFIX_DATA);
    }

    protected function preprocessMtimes($ids)
    {
        return $this->preprocessItems($ids, self::PREFIX_MTIME);
    }

    protected function preprocessIdTags($ids)
    {
        return $this->preprocessItems($ids, self::PREFIX_ID_TAGS);
    }

    protected function preprocessTagIds($tags)
    {
        return $this->preprocessItems($tags, self::PREFIX_TAG_IDS);
    }

    protected function redisVariadic($command, $arg1, $args = null)
    {
        if (is_array($arg1)) {
            $args = $arg1;
        } else {
            array_unshift($args, $arg1);
        }
        return call_user_func_array(array($this->redis, $command), $args);
    }

    /**
     * Required to pass unit tests
     *
     * @param  string $id
     * @return void
    **/
    public function ___expire($id)
    {
        $this->redis->del(self::PREFIX_DATA . $id);
        if ($this->exactMtime) {
            $this->redis->del(self::PREFIX_MTIME . $id);
        }
        $this->redis->del(self::PREFIX_ID_TAGS . $id);
    }
}
