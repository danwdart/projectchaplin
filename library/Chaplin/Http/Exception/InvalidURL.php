<?php
class Chaplin_Http_Exception_InvalidURL extends Chaplin_Http_Exception
{
    const MESSAGE = '%s was an invalid URL, reason: %s';
    
    public function __construct($strUrl, $e)
    {
        parent::__construct(sprintf(self::MESSAGE, $strUrl, $e->getMessage()));
    }
}
