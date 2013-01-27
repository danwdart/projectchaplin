<?php
interface Chaplin_Iterator_Interface extends Countable, ArrayAccess, SeekableIterator
{
    const SORT_ASC = 1;
    const SORT_DESC = -1; 
    const SORT_NUM_ASC = 2;
    const SORT_NUM_DESC = -2; 
    /**
     *  Returns whether the Iterator is empty (ie no data passed in)
     *  @return:    true | false;
    **/
    public function isEmpty();
    /**
     *  Limits the number of rows to be returned in the cursor
     *  @param:     $intNoRows  = number of rows to return
     *  @return:    $this (this is a fluent interface)
    **/
    public function limit($intNoRows);
    /**
     *  Skips the first  $intNoRows
     *  @param:     $intNoRows  = number of rows to skip
     *  @return:    $this (this is a fluent interface)
    **/
    public function skip($intNoRows);
    /**
     *  Sorts the cursor 
     *  @param:     $arrColumns     Associative array of Key => value
     *  @return:    $this (this is a fluent interface)
    **/
    public function sort(Array $arrColumns = array());
}
