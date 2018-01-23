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

namespace Chaplin\Http;

use Chaplin\Http\HttpInterface;
use Zend_Http_Client;
use Zend_Log;
use Zend_Http_Client_Exception;
use Chaplin\Http\Exception\InvalidURL;
use Zend_Uri_Exception;
use Chaplin\Log;
use Chaplin\Http\Exception\Unsuccessful;
use DOMDocument;
use Exception;
use DOMXPath;
use Chaplin\Http\Exception\XPathCannotFind;
use Chaplin\Http\Exception\XPathNotUnique;



/**
 * Wrapper to the Zend HTTP Client - just so we can use this interface
 * This only does GET requests right now - to do POST extend this - for one, the arrPageBody[url] needs some more
 *
 * @package default
 * @author  Dan Dart <chaplin@dandart.co.uk>
**/
class Client implements HttpInterface
{
    private $_zendHttpClient;

    /**
     * Save a new Zend_Http_Client in the object
     *
     * @param  Zend_Http_Client $client (for testing purposes)
     * @author Dan Dart <chaplin@dandart.co.uk>
    **/
    public function __construct(Zend_Http_Client $client = null)
    {
        $this->_zendHttpClient = $client;
        if (is_null($client)) {
            $this->_zendHttpClient  = new Zend_Http_Client();
        }
            $this->_zendHttpClient->setConfig(
                array(
                'timeout' => 30
                )
            );
    }

    /**
     * Array of cached page bodies - key is URL
     *
     * @var array
    **/
    private $_arrPageBody = array();

    /**
     * Get the page body from a URL
     *
     * @param  string $url
     * @return string $pageBody
     * @throws Chaplin\Http\Exception\Unsuccessful
     * @throws Chaplin\Http\Exception\InvalidURL
     * @author Dan Dart <chaplin@dandart.co.uk>
    **/
    public function getPageBody($url, $intLogPriority = Zend_Log::ERR)
    {
        // Make sure the URL has no spaces - re-encoding screws it up
        $url = str_replace(' ', '%20', $url);

        if (isset($this->_arrPageBody[$url])) {
            return $this->_arrPageBody[$url];
        }
        try {
            $this->_zendHttpClient->setUri($url);
        } catch (Zend_Http_Client_Exception $e) {
            throw new InvalidURL($url, $e);
        } catch (Zend_Uri_Exception $e) {
            Log::getInstance()->log('WARNING: Seemingly valid but unparseable URL: ' . $url, $intLogPriority);
            throw new InvalidURL($url, $e);
        }

        $httpResponse = $this->_zendHttpClient->request();

        // Log if priority added - and if 200 don't log the body
        // Tim hates this - but is there another way?
        if (!is_null($intLogPriority)) {
            if (200 == $httpResponse->getStatus()) {
                Log::getInstance()->log('Request: '.$url.', Response code: ('.$httpResponse->getStatus().')', $intLogPriority);
            } else {
                Log::getInstance()->log('Request: '.$url.', Response code: ('.$httpResponse->getStatus().'), body: ('.$httpResponse->getBody().')', $intLogPriority);
            }
        }

        if (!$httpResponse->isSuccessful()) {
            throw new Unsuccessful($url, $httpResponse->getStatus());
        }

        $this->_arrPageBody[$url] = $this->_checkForMetaRedirect($url, $httpResponse->getBody());

        return $this->_arrPageBody[$url];
    }

    public function getObject($url, $intLogPriority = Zend_Log::ERR)
    {
        // Make sure the URL has no spaces - re-encoding screws it up
        $url = str_replace(' ', '%20', $url);

        if (isset($this->_arrPageBody[$url])) {
            return $this->_arrPageBody[$url];
        }
        try {
            $this->_zendHttpClient->setUri($url);
            $this->_zendHttpClient->setHeaders('Accept', 'application/json');
        } catch (Zend_Http_Client_Exception $e) {
            throw new InvalidURL($url, $e);
        } catch (Zend_Uri_Exception $e) {
            Log::getInstance()->log('WARNING: Seemingly valid but unparseable URL: ' . $url, $intLogPriority);
            throw new InvalidURL($url, $e);
        }

        $httpResponse = $this->_zendHttpClient->request();

        // Log if priority added - and if 200 don't log the body
        // Tim hates this - but is there another way?
        if (!is_null($intLogPriority)) {
            if (200 == $httpResponse->getStatus()) {
                Log::getInstance()->log('Request: '.$url.', Response code: ('.$httpResponse->getStatus().')', $intLogPriority);
            } else {
                Log::getInstance()->log('Request: '.$url.', Response code: ('.$httpResponse->getStatus().'), body: ('.$httpResponse->getBody().')', $intLogPriority);
            }
        }

        if (!$httpResponse->isSuccessful()) {
            throw new Unsuccessful($url, $httpResponse->getStatus());
        }

        $this->_arrPageBody[$url] = $this->_checkForMetaRedirect($url, $httpResponse->getBody());

        return $this->_arrPageBody[$url];
    }


    public function getResponse($url, $intLogPriority = Zend_Log::ERR)
    {
        // Make sure the URL has no spaces - re-encoding screws it up
          $url = str_replace(' ', '%20', $url);

        try {
            $this->_zendHttpClient->setUri($url);
        } catch (Zend_Http_Client_Exception $e) {
            throw new InvalidURL($url, $e);
        } catch (Zend_Uri_Exception $e) {
            Log::getInstance()->log('WARNING: Seemingly valid but unparseable URL: ' . $url);
            throw new InvalidURL($url, $e);
        }

          $httpResponse = $this->_zendHttpClient->request();

          // Log if priority added - and if 200 don't log the body
          // Tim hates this - but is there another way?
        if (!is_null($intLogPriority)) {
            if (200 == $httpResponse->getStatus()) {
                Log::getInstance()->log('Request: '.$url.', Response code: ('.$httpResponse->getStatus().')', $intLogPriority);
            } else {
                Log::getInstance()->log('Request: '.$url.', Response code: ('.$httpResponse->getStatus().'), body: ('.$httpResponse->getBody().')', $intLogPriority);
            }
        }

          $this->_arrPageBody[$url] = $this->_checkForMetaRedirect($url, $httpResponse->getBody());

          return $httpResponse;
    }

    /**
     * Use use the client to parse the page
     *
     * @param  string $strURL
     * @param  string $strXPath
     * @return string
     * @author Tim Langley
    **/
    public function scrapeXPath($strURL, $strXPath)
    {
        $strPageBody = $this->getPageBody($strURL);
        $strElement = $this->_parseXPath($strURL, $strPageBody, $strXPath);
        return $strElement;
    }


    /**
     * Parses the raw XPath out of some data
     *
     * @param  string $strData
     * @param  string $strXPath
     * @return string
     * @author Dan Dart <chaplin@dandart.co.uk>
    **/
    public function parseRawXPath($strData, $strXPath)
    {
        // Load a parsing environment
        $domDocument = new DOMDocument();
        // Be quiet - else we'll see a tonne of errors if the XML is invalid - we don't care but we might want to tell later?...
        if (!@$domDocument->loadXML($strData)) {
            throw new Exception('Could not load XML! Raw follows: ' . $strData);
        }

        $domXPath = new DOMXPath($domDocument);

        // Query the DOM with an XPath query
        $domNodes = $domXPath->query($strXPath);

        // If no nodes were found...
        if ($domNodes->length == 0) {
            throw new XPathCannotFind($strXPath);
        }

        // If more than one node was found...
        if ($domNodes->length > 1) {
            throw new XPathNotUnique($strXPath);
        }
        $strNode = $domNodes->item(0);

        return $strNode->nodeValue;
    }

    /**
     * Parses raw HTML XPath
     * If URL is present and XPath ends in @src or @href then it attempts absolute URL detection
     *
     * @param  string $strData
     * @param  string $strXPath
     * @param  string $strURL
     * @return void
     * @author Dan Dart <chaplin@dandart.co.uk>
    **/
    public function parseRawHtmlXPath($strData, $strXPath, $strURL = null)
    {
        // Load a parsing environment
        $domDocument = new DOMDocument();
        // Be quiet - else we'll see a tonne of errors if the HTML is invalid - we don't care but we might want to tell later?...
        if (!@$domDocument->loadHTML($strData)) {
            throw new Exception('Could not load HTML! Raw follows: ' . $strData);
        }

        $domXPath = new DOMXPath($domDocument);

        // Query the DOM with an XPath query
        $domNodes = $domXPath->query($strXPath);

        // If no nodes were found...
        if ($domNodes->length == 0) {
            throw new XPathCannotFind($strXPath);
        }

        // If more than one node was found...
        if ($domNodes->length > 1) {
            throw new XPathNotUnique($strXPath);
        }
        $strNode = $domNodes->item(0);

        $strValue = $strNode->nodeValue;
        return $strValue;
    }

    /**
    * Gets a Zend_Http_Response from trying to get this URL
    * TODO: Move everything here
    *
    * @param  string $strURL
    * @param  int    $intLogPriority = null
    * @return Zend_Http_Response
    * @author Dan Dart <chaplin@dandart.co.uk>
    **/
    public function getHttpResponse($url, $intLogPriority = Zend_Log::ERR)
    {
        // Make sure the URL has no spaces - re-encoding screws it up
        $url = str_replace(' ', '%20', $url);

        try {
            $this->_zendHttpClient->setUri($url);
        } catch (Zend_Http_Client_Exception $e) {
            throw new InvalidURL($url, $e);
        } catch (Zend_Uri_Exception $e) {
            Log::getInstance()->log('WARNING: Seemingly valid but unparseable URL: ' . $url);
            throw new InvalidURL($url, $e);
        }

        $httpResponse = $this->_zendHttpClient->request();

        // Log if priority added - and if 200 don't log the body
        // Tim hates this - but is there another way?
        if (!is_null($intLogPriority)) {
            if (200 == $httpResponse->getStatus()) {
                Log::getInstance()->log('Request: '.$url.', Response code: ('.$httpResponse->getStatus().')', $intLogPriority);
            } else {
                Log::getInstance()->log('Request: '.$url.', Response code: ('.$httpResponse->getStatus().'), body: ('.$httpResponse->getBody().')', $intLogPriority);
            }
        }

        // Do Not Cache This Here!! We have caches above here

        return $httpResponse;
    }

    protected function _parseXPath($strURL, $strPageBody, $strXPath)
    {
        $value = $this->parseRawHtmlXPath($strPageBody, $strXPath);

        if (strpos($strXPath, '/@src') !== false || strpos($strXPath, '/@href') !== false) {
            $value = $this->_getAbsoluteURL($strURL, $value);
        }

        return $value;
    }

    protected function _checkForMetaRedirect($strURL, $strPageBody)
    {
        try {
            $strRedirectContent = $this->_parseXPath($strURL, $strPageBody, "//meta[@http-equiv='refresh']/@content");
        } catch (XPathCannotFind $e) {
            // We didn't find a redirect tag
            return $strPageBody;
        }

        $strRedirectContent = strtolower($strRedirectContent);

        /**
         * Now, RedirectContent will either look like:
         *      (a) a number (600)
         *      (b) a number, semicolon and url=[a url]
        **/

        // The first instance - it won't go anywhere new so just return it back
        if (false === stripos($strRedirectContent, 'url=')) {
            return $strPageBody;
        }

        // We know we have a URL now, so split the redirect string so we can get it
        $arrNewURL = explode('url=', $strRedirectContent, 2);
        $strNewURL = $arrNewURL[1];

        $strNewURL = $this->_getAbsoluteURL($strURL, $strNewURL);

        if ($strNewURL != $strURL) {
            return $this->getPageBody($strNewURL);
        }

        return $strPageBody;
    }

    protected function _getAbsoluteURL($strPageURL, $strRelativePath)
    {
        // If Relative URLs contain '../' at the beginning, they can either refer to the current directory


        // Sometimes sites link to "//host.com/url" when they mean "https://host.com/url" - Google does this
        // This is already an absolute URL so add the missing scheme from the page URL
        if (strpos($strRelativePath, '//') === 0) {
            return parse_url($strPageURL, PHP_URL_SCHEME) . ':' . $strRelativePath;
        }

         /* return if already absolute URL**/
        if (parse_url($strRelativePath, PHP_URL_SCHEME) != '') {
            return $strRelativePath;
        }

        // YES, this IS strPageURL, because we want to parse out most of it and append the relative path to it!
        $parsed = parse_url($strPageURL);

        /* remove non-directory element from path**/
        $path = preg_replace('#/[^/]*$#', '', $parsed['path']);

        /* destroy path if relative url points to root**/
        if (substr($strRelativePath, 0, 1) == '/') {
            $parsed['path'] = '';
        }

        /* dirty absolute URL**/
        $strAbsolutePath = $parsed['host'] . $parsed['path'] . '/' . $strRelativePath;

        /* replace '//' or '/./' or '/foo/../' with '/'**/
        $strRegex = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
        for ($n=1; $n>0; $strAbsolutePath = preg_replace($strRegex, '/', $strAbsolutePath, -1, $n)) {
        }

        // If somehow we don't end up with a scheme, add the one from the URL
        // For example, Google gave us //url.com/xyzzy
        if (!isset($parsed['scheme'])) {
            $parsed['scheme'] = parse_url($strPageURL, PHP_URL_SCHEME);
        }

        /* absolute URL is ready!**/
        $strAbsoluteURL = $parsed['scheme'].'://'.$strAbsolutePath;

        return $strAbsoluteURL;
    }
}
