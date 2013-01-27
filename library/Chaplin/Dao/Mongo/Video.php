<?php
/**
 * This file is part of Project Chaplin.
 *
 * Project Chaplin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Project Chaplin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Project Chaplin. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    Project Chaplin
 * @author     Dan Dart
 * @copyright  2012-2013 Project Chaplin
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version    git
 * @link       https://github.com/dandart/projectchaplin
**/
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
