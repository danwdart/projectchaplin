<?php
class Chaplin_Dao_Sql_Video
	extends Chaplin_Dao_Sql_Abstract
	implements Chaplin_Dao_Interface_Video
{
	const TABLE = 'Videos';

    const PK = 'VideoId';

	protected function _getTable()
	{
		return self::TABLE;
	}

    protected function _getPrimaryKey()
    {
        return self::PK;
    }

	public function getFeaturedVideos()
	{
        $strSql = 'SELECT * FROM %s';
        $arrRows = $this->_getAdapter()->fetchAll(sprintf($strSql, self::TABLE));;
		return new Chaplin_Iterator_Dao_Sql_Rows($arrRows, $this);
	}
    
    public function getByVideoId($strVideoId)
    {
    	$strSql = 'SELECT * FROM %s WHERE %s = ?';
        $arrRow = $this->_getAdapter()->fetchRow(sprintf($strSql, self::TABLE, self::PK), $strVideoId);
        if (false === $arrRow) {
            throw new Chaplin_Dao_Exception_Video_NotFound($strVideoId);
        }
        return $this->convertToModel($arrRow);
    }

    public function getBySearchTerms($strSearchTerms)
    {
    	return [];
    }
    
    public function getByUser(Chaplin_Model_User $modelUser)
    {
    	return [];
    }
            
    public function delete(Chaplin_Model_Video $modelVideo)
    {
    	return [];
    }

    public function save(Chaplin_Model_Video $modelVideo)
    {
    	return [];
    }

    public function convertToModel($arrData)
    {
        return Chaplin_Model_Video::createFromData($this, $this->_sqlToModel($arrData));
    }
}