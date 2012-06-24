<?php
abstract class Chaplin_Cache_Abstract
{
    private $_zendCache;
    
    public function setCache(Zend_Cache_Core $zendCache   = null)
    {
        $this->_zendCache    = $zendCache;
    }

    protected function _cacheLoadKey($strKey)
    {
        if (is_null($this->_zendCache))
            return false;
        return $this->_zendCache->load($strKey);
    }

    protected function _cacheSaveKey($strKey, $mixedValue)
    {
        if (is_null($this->_zendCache))
            return false;
        return $this->_zendCache->save($mixedValue, $strKey);
    }

    protected function _getCacheKey($strMethod, $strString)
    {
        return preg_replace('/[^a-zA-Z0-9_]/', '', $strMethod.$strString);
    }
}
