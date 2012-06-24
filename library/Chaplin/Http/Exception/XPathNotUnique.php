<?php
class Chaplin_Http_Exception_XPathNotUnique extends Chaplin_Exception
{
    const MESSAGE = 'Could not find a unique element for query "%s" while scraping URL';
    
    public function __construct($strRef)
    {
        parent::__construct(sprintf(self::MESSAGE, $strRef));
    }
}
