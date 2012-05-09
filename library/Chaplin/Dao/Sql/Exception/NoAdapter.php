<?php
class Chaplin_Dao_Sql_Exception_NoAdapter extends Exception
{
    const MESSAGE = 'No SQL adapter present';

    public function __construct()
    {
        parent::__construct(sprintf(self::MESSAGE));
    }
}
