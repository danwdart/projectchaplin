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
 * @package    Project Chaplin
 * @author     Dan Dart
 * @copyright  2012-2013 Project Chaplin
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version    git
 * @link       https://github.com/dandart/projectchaplin
**/
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
