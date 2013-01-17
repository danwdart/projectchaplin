<?php
abstract class Chaplin_Dao_PhpRedis_Abstract
    implements Chaplin_Dao_Interface
{
    /**
     * Cache of model Account
    **/
    private $_modelAccount;
    
    /**
     * Redis instance
    **/
    private $_redis;
    
    /**
     * Constructs with an account (or not)
     *
     * @param Chaplin_Model_Account $modelAccount 
     * @author Dan Dart
    **/
    public function __construct(Chaplin_Model_Account $modelAccount = null)
    {
        $this->_modelAccount = $modelAccount;
    }
    
    /**
     * Gets the account for the DAO objects
     *
     * @return Chaplin_Model_Account
     * @author Dan Dart
    **/
    protected function _getAccount()
    {
        return $this->_modelAccount;
    }
    
    /**
     * Gets the Redis instance
     *
     * @return Redis
     * @author Dan Dart
    **/
    protected function _getRedis()
    {
        if(is_null($this->_redis)) {
            $this->_redis = Zend_Registry::get(self::DEFAULT_REGISTRY_KEY);
        }
        
        return $this->_redis;
    }

    /**
     * Injects a redis instance for testing
     *
     * @param Redis $redis 
     * @return void
     * @author Dan Dart
    **/
    public function inject(Redis $redis)
    {
        $this->_redis = $redis;
    }
}
