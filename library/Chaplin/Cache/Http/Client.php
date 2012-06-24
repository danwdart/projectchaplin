<?php
class Chaplin_Cache_Http_Client
    extends Chaplin_Cache_Abstract 
    implements Chaplin_Http_Interface
{
    private $_objHttpClient;

    public function __construct(Chaplin_Http_Interface $objHttpClient, Zend_Cache_Core $cacheHttpClient = null)
    {
        $this->_objHttpClient = $objHttpClient;
        $this->setCache($cacheHttpClient);
    }
    
    public function getPageBody($strURL, $intLogPriority = null)
    {
      $cacheKey   = $this->_getCacheKey(__METHOD__, $strURL);
      if (false === ($response = $this->_cacheLoadKey($cacheKey))) {
        $response   = $this->_objHttpClient->getPageBody($strURL, $intLogPriority);
        $this->_cacheSaveKey($cacheKey, $response);
      }
      return $response;
    }
    
    public function scrapeXPath($strURL, $strXPath)
    {
        $cacheKey   = $this->_getCacheKey(__METHOD__, $strURL . 'X' . $strXPath);
        if (false === ($response = $this->_cacheLoadKey($cacheKey))) {
            $response   = $this->_objHttpClient->scrapeXPath($strURL, $strXPath);
            $this->_cacheSaveKey($cacheKey, $response);
        }
        return $response;
    }

    public function getHttpResponse($strURL, $intLogPriority = null, $bCache = true)
    {
        if(!$bCache) {
            return $this->_objHttpClient->getHttpResponse($strURL, $intLogPriority);
        }
        
        Shared_Log::getInstance()->log('Cache: trying to load ('.$strURL.')', $intLogPriority);
        $cacheKey = $this->_getCacheKey(__METHOD__, $strURL);
        if (false === ($response = $this->_cacheLoadKey($cacheKey))) {
            $response = $this->_objHttpClient->getHttpResponse($strURL, $intLogPriority);
            if(200 == $response->getStatus()) {
                $this->_cacheSaveKey($cacheKey, $response);
            }
        }
        Shared_Log::getInstance()->log('Cache: retrieved ('.$response->getBody().')', $intLogPriority);
        return $response;
    }
 
    public function parseRawXPath($strData, $strXPath)
    {
        return $this->_objHttpClient->parseRawXPath($strData, $strXPath);
    }

    public function parseRawHtmlXPath($strData, $strXPath, $strURL = null)
    {
        return $this->_objHttpClient->parseRawHtmlXPath($strData, $strXPath, $strURL);
    }
}
