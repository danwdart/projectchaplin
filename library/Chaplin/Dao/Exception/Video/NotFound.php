<?php
class Chaplin_Dao_Exception_Video_NotFound extends Exception
{
    const MESSAGE = 'Video by id (%s) not found.';
    
    public function __construct($strVideoId)
    {
        parent::__construct(sprintf(self::MESSAGE, $strVideoId));
    }
}
