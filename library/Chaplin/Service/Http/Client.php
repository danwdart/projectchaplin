<?php
/**
 * The hook that Service->getHttpClient() provides
 *
 * @package default
 * @author Dan Dart
**/
class Chaplin_Service_Http_Client
{
    protected $_objHttpClient;
    
    /**
     * Create the object from the interface
     *
     * @param Chaplin_Http_Interface $objHttpClient This needs mocking for testing
     * 
     * @author Dan Dart
    **/
    public function __construct(Chaplin_Http_Interface $objHttpClient)
    {
        $this->_objHttpClient = $objHttpClient;
    }
    
    /**
     * Try to use the client to get the page body
     *
     * @param string $strURL         The URLs we want to uses
     * @param int    $intLogPriority = null
     * 
     * @return string
     * @author Dan Dart
    **/
    public function getPageBody($strURL, $intLogPriority = null)
    {
        return $this->_objHttpClient->getPageBody($strURL, $intLogPriority);
    }
    /**
     * Use use the client to parse the page 
     *
     * @param string $strURL   The URL
     * @param string $strXPath The xPath
     * 
     * @return string
     * @author Tim Langley
    **/
    public function scrapeXPath($strURL, $strXPath)
    {
        return $this->_objHttpClient->scrapeXPath($strURL, $strXPath);
    }
    
    /**
     * Library method to just parse some raw data with an XPath
     *
     * @param string $strData  The data we want to parses
     * @param string $strXPath The xPath
     * 
     * @return string
     * @author Dan Dart
    **/
    public function parseRawXPath($strData, $strXPath)
    {
        return $this->_objHttpClient->parseRawXPath($strData, $strXPath);
    }

    /**
     * Library method to just parse some raw data with an XPath (HTML version)
     *
     * @param string $strData  The page body
     * @param string $strXPath The Xpath
     * @param string $strURL   = null (for absolute paths - does not scrape)
     * 
     * @return string
     * @author Dan Dart
    **/
    public function parseRawHtmlXPath($strData, $strXPath, $strURL = null)
    {
        return $this->_objHttpClient->parseRawHtmlXPath($strData, $strXPath, $strURL);
    }
    
    /**
     * Gets an HTTP Response
     *
     * @param string $strURL 
     * @param string $intLogPriority 
     * @param string $bCache
     * @return Chaplin_Cache_HttpClient
     * @author Dan Dart
    **/
    public function getHttpResponse($strURL, $intLogPriority = null, $bCache = true)
    {
        return $this->_objHttpClient->getHttpResponse($strURL, $intLogPriority, $bCache);
    }
}
