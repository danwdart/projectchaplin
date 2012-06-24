<?php
class Chaplin_Http_Exception_Unsuccessful extends Chaplin_Http_Exception
{
    const MESSAGE = 'HTTP request for url %s was unsuccessful (Error code %s).';
    
    public function __construct($strUrl, $strResponseCode)
    {
        parent::__construct(sprintf(self::MESSAGE, $strUrl, $strResponseCode));
    }
}
