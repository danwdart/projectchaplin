<?php
class Chaplin_Iterator_Filter_File
	extends FilterIterator
{
	private $_strAcceptableTypes;

	public function setAcceptableTypes($strAcceptableTypes)
	{
		$this->_strAcceptableTypes = $strAcceptableTypes;
	}

	public function accept()
	{
		return parent::current()->isFile() && 
			preg_match('/\\.('.$this->_strAcceptableTypes.')$/i', parent::current()->getFilename()) &&
			false === strpos(parent::current()->getPathname(), realpath(APPLICATION_PATH.'/..'));
	}
}