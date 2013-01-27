<?php
class Chaplin_Iterator_Dao_Sql_Rows implements Chaplin_Iterator_Interface
{
    private $_daoInterface;
    private $_bEmpty        = false;

    private $_intOffset     = 0;
    private $_intStartRow   = 0; 
    private $_intReturnRows;

    public function __construct(Array $arrRows, Chaplin_Dao_Sql_Abstract $daoInterface)
    {
        $this->_arrRows = $arrRows;
        $this->_daoInterface = $daoInterface;
    }
    public function isEmpty()
    {
        if (0 == count($this->_arrRows)) {
            $this->_bEmpty = true;
        }
        return $this->_bEmpty;
    }
    public function count()
    {
        return count($this->_arrRows);
    }
    public function current()
    {
        $arrCurrentItem = $this->_arrRows[$this->_intOffset];
        return $this->_daoInterface->convertToModel($arrCurrentItem);
    }
    function key() 
    {
        return $this->_intOffset;
    }
    function next() 
    {
        $this->_intOffset++;
    }
    function rewind() 
    {
        $this->_intOffset = 0;
    }
    function valid()
    {
        return isset($this->_arrRows[$this->_intOffset]);
    }
    //Implements ArrayAccess
    public function offsetSet($offset, $value) 
    {
        throw new Chaplin_Exception_NotImplemented();
    } 
    public function offsetExists($offset)
    {
        throw new Chaplin_Exception_NotImplemented();
    }
    public function offsetUnset($offset)
    {
        throw new Chaplin_Exception_NotImplemented();
    } 
    public function offsetGet($offset)
    {
        throw new Chaplin_Exception_NotImplemented();
    }

    /*  Limits the number of rows to be returned in the cursor
     *  @param:     $intNoRows  = number of rows to return
     *  @return:    $this (this is a fluent interface)
     **/
    public function limit($intNoRows)
    {
        throw new Chaplin_Exception_NotImplemented();
    }
    /**
     *  Skips the first  $intNoRows
     *  @param:     $intNoRows  = number of rows to skip
     *  @return:    $this (this is a fluent interface)
     **/
    public function skip($intNoRows)
    {
        throw new Chaplin_Exception_NotImplemented();
    }
    /**
     *  Sorts the cursor 
     *  @param:     $arrColumns     Associative array of Key => value (1 = ASC, -1 = DESC)
     *  @return:    $this (this is a fluent interface)
     **/
    public function sort(Array $arrColumns = array())
    {
        throw new Chaplin_Exception_NotImplemented();
    }

    //Implements SeekableIterator
    public function seek($strPosition)
    {
        throw new Chaplin_Exception_NotImplemented();
    }
}
