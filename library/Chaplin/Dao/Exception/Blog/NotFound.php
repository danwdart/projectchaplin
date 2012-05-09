<?php
class Chaplin_Dao_Exception_Blog_NotFound extends Exception
{
    const MESSAGE = 'The blog by id (%s) was not found.';

    public function __construct($strId)
    {
        parent::__construct(sprintf(self::MESSAGE, $strId));
    }
}
