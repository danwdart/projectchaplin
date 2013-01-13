<?php
class Chaplin_Dao_Mongo_Video
    extends Chaplin_Dao_Mongo_Abstract
    implements Chaplin_Dao_Interface_Video
{
    const COLLECTION = 'Videos';

    protected function _getCollectionName()
    {
        return self::COLLECTION;
    }
    
    public function getFeaturedVideos()
    {
        $arrQuery = array();
        
        $cursor = $this->_getCollection()->find($arrQuery);
        $cursor->sort(array(Chaplin_Model_Video::FIELD_TIMECREATED =>
            Chaplin_Iterator_Interface::SORT_DESC
        ));
        $cursor->limit(40);
        return new Chaplin_Iterator_Dao_Mongo_Cursor($cursor, $this);
    }

    public function getBySearchTerms($strSearchTerms)
    {

        $strRegex = '/('.implode('|',explode(' ',preg_quote($strSearchTerms))).')/i';
        $arrQuery = array(
            Chaplin_Model_Video::FIELD_TITLE => new MongoRegex($strRegex)
        );
        
        $cursor = $this->_getCollection()->find($arrQuery);
        $cursor->sort(array(Chaplin_Model_Video::FIELD_TIMECREATED =>
            Chaplin_Iterator_Interface::SORT_DESC
        ));
        $cursor->limit(40);
        return new Chaplin_Iterator_Dao_Mongo_Cursor($cursor, $this);
    }
    
    public function getByVideoId($strVideoId)
    {
        $arrQuery = array(
            Chaplin_Model_Video::FIELD_VIDEOID => $strVideoId
        );
        
        $arrVideo = $this->_getCollection()->findOne($arrQuery);
        
        if(is_null($arrVideo)) {
            throw new Chaplin_Dao_Exception_Video_NotFound($strVideoId);
        }
        return $this->convertToModel($arrVideo);
    }
    
    public function getByUser(Chaplin_Model_User $modelUser)
    {
        $arrQuery = array(
            Chaplin_Model_Video::FIELD_USERNAME => $modelUser->getUsername()
        );
        
        $cursor = $this->_getCollection()->find($arrQuery);
        
        return new Chaplin_Iterator_Dao_Mongo_Cursor($cursor, $this);
    }
    
    public function getByUserUnnamed(Chaplin_Model_User $modelUser)
    {
        $arrQuery = array(
            Chaplin_Model_Video::FIELD_USERNAME => $modelUser->getUsername(),
            Chaplin_Model_Video::FIELD_TITLE => ''
        );
        
        $cursor = $this->_getCollection()->find($arrQuery);
        
        return new Chaplin_Iterator_Dao_Mongo_Cursor($cursor, $this);
    }

    public function convertToModel($arrVideo)
    {
        return Chaplin_Model_Video::createFromData($this, $arrVideo);
    }

    public function delete(Chaplin_Model_Video $modelVideo)
    {
        $this->_delete($modelVideo);
    }

    public function save(Chaplin_Model_Video $modelVideo)
    {
        $this->_save($modelVideo);
    }
}
