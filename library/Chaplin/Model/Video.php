<?php
class Chaplin_Model_Video extends Chaplin_Model_Field_Hash
{
    const FIELD_VIDEOID = self::FIELD_ID;
    const FIELD_TIMECREATED = 'TimeCreated';
    const FIELD_USERNAME = 'Username';
    const FIELD_FILENAME = 'Filename';
    const FIELD_THUMBNAIL = 'Thumbnail';
    const FIELD_TITLE = 'Title';
    const FIELD_LENGTH = 'Length';
    const FIELD_WIDTH = 'Width';
    const FIELD_HEIGHT = 'Height';
    const FIELD_FORMAT = 'Format';
    const FIELD_BITRATE = 'Bitrate';
    const FIELD_SIZE = 'Size';
    const FIELD_VIEWS = 'Views';
    const FIELD_PARTIALVIEWS = 'PartialViews';
    const FIELD_BOUNCES = 'Bounces';
    const FIELD_OBJ_FEEDBACK = 'Feedback';
    const FIELD_ARRAY_TAGS = 'Tags';
    const FIELD_ARRAY_NOTTAGS = 'NotTags';
    const CHILD_ASSOC_COMMENTS = 'Comments';

    protected $_arrFields = array(
        self::FIELD_VIDEOID => 'Chaplin_Model_Field_FieldId',
        self::FIELD_TIMECREATED => 'Chaplin_Model_Field_Field',
        self::FIELD_USERNAME => 'Chaplin_Model_Field_Field',
        self::FIELD_FILENAME => 'Chaplin_Model_Field_Field',
        self::FIELD_THUMBNAIL => 'Chaplin_Model_Field_Field',
        self::FIELD_TITLE => 'Chaplin_Model_Field_Field',
        //self::CHILD_ASSOC_COMMENTS => 'Chaplin_Model_Field_Collection_Assoc'
    );

    public static function create(
        Chaplin_Model_User $modelUser,
        $strFilename, // form element?
        $strThumbURL,
        $strTitle
    ) {
        $video = new self();
        $video->_setField(self::FIELD_VIDEOID, md5(new MongoId()));
        $video->_setField(self::FIELD_TIMECREATED, time());
        $video->_setField(self::FIELD_USERNAME, $modelUser->getUsername());
        $video->_setField(self::FIELD_FILENAME, $strFilename);
        $video->_setField(self::FIELD_THUMBNAIL, $strThumbURL);
        $video->_setField(self::FIELD_TITLE, $strTitle);
        return $video;
    }

    public function getVideoId()
    {
        return $this->_getField(self::FIELD_VIDEOID, null);
    }

    public function getTitle()
    {
        return $this->_getField(self::FIELD_TITLE, null);
    }

    public function getFilename()
    {
        return $this->_getField(self::FIELD_FILENAME, null);
    }
    
    public function setFilename($strFilename)
    {
        return $this->_getField(self::FIELD_FILENAME, $strFilename);
    }

    public function getThumbnail()
    {
        return $this->_getField(self::FIELD_THUMBNAIL, null);
    }

    public function getComments()
    {
        return $this->_getField(self::CHILD_ASSOC_COMMENTS, array());
    }

    public function getUsername()
    {
        return $this->_getField(self::FIELD_USERNAME, null);
    }

    public function isMine()
    {
        if(!Chaplin_Auth::getInstance()->hasIdentity()) {
            return false;
        }
        if(Chaplin_Auth::getInstance()->getIdentity()->getUser()->isGod()) {
            // God users own everything, mwuhahaha
            return true;
        }
        return Chaplin_Auth::getInstance()->getIdentity()->getUser()->getUsername() ==
            $this->getUsername();
    }

    public function delete()
    {
        $strFullPath = APPLICATION_PATH.'/public'.$this->getFilename();
        unlink($strFullPath);
        $strThumbnailPath = APPLICATION_PATH.'/public'.$this->getThumbnail();
        unlink($strThumbnailPath);
        return Chaplin_Gateway::getInstance()
            ->getVideo()
            ->delete($this);
    }

    public function save()
    {
        return Chaplin_Gateway::getInstance()
            ->getVideo()
            ->save($this);
    }
}
