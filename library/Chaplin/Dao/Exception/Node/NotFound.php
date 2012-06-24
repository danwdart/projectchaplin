<?php
class Chaplin_Dao_Exception_Node_NotFound extends Zend_Exception
{
    const MESSAGE = 'Node with id (%s) was not found.';

    public function __construct($strNodeId)
    {
        parent::__construct(sprintf(self::MESSAGE, $strNodeId));
    }
}
