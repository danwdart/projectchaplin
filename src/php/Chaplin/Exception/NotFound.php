<?php
class Chaplin_Exception_NotFound
    extends Exception
{
    const MESSAGE = 'Page not found (%s)';

    public function __construct($strIdentifier)
    {
        parent::__construct(sprintf(self::MESSAGE, $strIdentifier));
    }
}