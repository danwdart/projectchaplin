<?php
class Chaplin_Model_Field_Collection
	implements Iterator, Countable
{
	private $_intIndex = 0;
	private $_strHashType = null;
	private $_bIsDirty = false;
	private $_collHashes = array();

	public function __construct($strHashType)
	{
		$this->_strHashType = $strHashType;		
	}

	public function bIsDirty()
	{
		return $this->_bIsDirty;
	}

	public function addHash(Chaplin_Model_Field_Hash $hash)
	{
		$this->_collHashes[] = $hash;
		$this->_bIsDirty = true;
	}

	public function valid()
	{
		return isset($this->_collHashes[$this->_intIndex]);
	}

	public function current()
	{
		return $this->_collHashes[$this->_intIndex];
	}

	public function next()
	{
		$this->_intIndex++;
	}

	public function rewind()
	{
		$this->_intIndex = 0;
	}

	public function count()
	{
		return count($this->_collHashes);
	}

	public function key()
	{
		return $this->_intIndex;
	}

	public function seek($strId)
	{
		foreach($thos->_collHashes as $hash) {
			if ($hash->getId() == $strId) {
				return $hash;
			}
		}
		throw new OutOfBoundsException($strId);
	}

	public function setFromData($mixedValue)
    {
    	if (!is_array($mixedValue)) {
        	throw new Exception('Not Array');
        }

        $strHashType = $this->_strHashType;

        foreach($mixedValue as $strId => $arrData) {
        	$strHashType::createFromIterator($this, $arrData);
        	$this->_collHashes[] = $strHashType::createFromIterator($this, $arrData);
        }
        return $this;
    }       
    
    public function setValue($mixedValue)
    {
        throw new Exception("Can't do this!");
    }
        
    public function getValue($mixedDefault)
    {
        return $this;
    }
}