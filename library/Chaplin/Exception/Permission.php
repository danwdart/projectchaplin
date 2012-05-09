<?php
class Chaplin_Exception_Permission extends Exception
{
    const MESSAGE = 'Sorry - you don\'t have permission to do that.';

    public function __construct()
    {
        parent::__construct(sprintf(self::MESSAGE));
    }
}
