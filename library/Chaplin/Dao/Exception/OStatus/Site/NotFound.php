<?php
class Chaplin_Dao_Exception_OStatus_Site_NotFound extends Exception
{
    const MESSAGE = 'The site by id (%s) was not found.';

    public function __construct($strId)
    {
        parent::__construct(sprintf(self::MESSAGE, $strId));
    }
}
