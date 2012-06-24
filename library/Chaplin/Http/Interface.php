<?php
/**
 * Interface for relevant Http_Client interfaces so we can mock Zend_Http_Client
 *
 * @package default
 * @author Dan Dart
**/
interface Chaplin_Http_Interface
{
    /**
     * Try to use the client to get the page body
     *
     * @param string $strURL 
     * @return string
     * @author Dan Dart
    **/
    public function getPageBody($url);
    /**
     * Use use the client to parse the page 
     *
     * @param string $strURL 
     * @param string $strXPath 
     * @return string
     * @author Tim Langley
    **/
    public function scrapeXPath($strURL, $strXPath);
}
