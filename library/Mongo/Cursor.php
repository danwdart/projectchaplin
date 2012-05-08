<?php
/**
 * @category   MongoDB
 * @package    Mongo
 * @copyright  2010, Campaign and Digital Intelligence Ltd
 * @license    New BSD License
 * @author     Tim Langley
**/

class Mongo_Cursor implements OuterIterator, Countable
{
	private	$_cursor 				= null;	//This is the MongoCursor
	private $_strDatabaseName		= null;
	
	public function __construct(MongoCursor $cursor, $strDatabaseName)
	{
		/**
		 *	@purpose:	A cursor is a wrapper for the MongoCursor therefore it needs a "non-null" MongoCursor to create
		**/
		if(is_null($cursor) || is_null($strDatabaseName))
			throw new Mongo_Exception(Mongo_Exception::ERROR_NOT_NULL);
		$this->_cursor 				= $cursor;
		$this->_strDatabaseName		= $strDatabaseName;
	}
	public function getDatabaseName()
	{
	    return $this->_strDatabaseName;
	}
	public function limit($intLimit = 0)
	{
		/**
		 *	@purpose: 	Limits the a number of results to return
		 *	@param:		$intLimit - the number to return
		**/
		$this->_cursor = $this->_cursor->limit($intLimit);
		return $this;
	}
	public function sort($sort = array())
	{
		/**
		 *	@purpose:	Sorts the results in the Cursor
		 *	@param:		$sort	Array array(A => 1, B => -1 ) where A,B are the fields to sort on & 1 = ASC, -1 = DESC
		 *	@return:	$this (fluent) || Exception
		**/
		if(!empty($sort))
			$this->_cursor->sort($sort);
		return $this;
	}
	public function skip($intNoToSkip = 0)
	{
		/**
		 *	@purpose: 	Skips a number of results
		 *	@param:		$intNoToSkip - the number to count ahead
		**/
		$this->_cursor = $this->_cursor->skip($intNoToSkip);
		return $this;
	}
	
	//Implements OuterIterator
	public function getInnerIterator()
	{
		/**
		 *	@purpose: Returns the MongoCursor
		**/
		return $this->_cursor;
	}
	public function export()
	{
		/**
		 *	@purpose:	Exports the Iterator as an array
		**/
		$this->rewind();
		return iterator_to_array($this->getInnerIterator());
	}
	public function current()
	{
		/**
		 *	@purpose:	Returns the (array?) of the the the cursor currently represents
		 *	
		 *	@todo:		Probably should replace this with returning a Mongo_Document
		**/
		return $this->_cursor->current();
	}	
	public function getNext()
	{
		$this->next();
		return $this->current();
	}
	public function key()
	{
		return $this->getInnerIterator()->key();
	}
	public function next()
	{
		return $this->getInnerIterator()->next();
	}
	public function rewind()
	{
		return $this->getInnerIterator()->rewind();
	}
	public function valid()
	{
		return $this->getInnerIterator()->valid();
	}
	public function info()
	{
		return $this->getInnerIterator()->info();
	}
	
	//Implements Countable
	/**
	 *	@purpose 	This returns the size of the cursor
	 *	@param:		$bFoundOnly	= false	=> returns the entire size of the cursor
	 *							= true	=> returns jus the size of the dataset to be returned
	**/
	public function count($bFoundOnly = false)
	{
		return $this->getInnerIterator()->count($bFoundOnly);
	}
}
