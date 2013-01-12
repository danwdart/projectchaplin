<?php
class Chaplin_Model_Video_Comment
    extends Chaplin_Model_Field_Hash
{
    const FIELD_USERNAME = 'Username';
    const FIELD_COMMENT = 'Comment';
    //Feedback
    
    protected $_arrFields = array(
        self::FIELD_ID => array('Class' => 'Chaplin_Model_Field_FieldId'),
        self::FIELD_USERNAME => array('Class' => 'Chaplin_Model_Field_Field'),
        self::FIELD_COMMENT => array('Class' => 'Chaplin_Model_Field_Field')
    );
    
    public static function create(
        Chaplin_Model_Field_Collection $collection,
        Chaplin_Model_User $modelUser,
        $strComment
    ) {
        $comment = new self();
        $comment->_setField(self::FIELD_ID, md5(new MongoId()));
        $comment->_setField(self::FIELD_USERNAME, $modelUser->getUsername());
        $comment->_setField(self::FIELD_COMMENT, $strComment);
        $collection->addHash($comment);
        return $comment;
    }
    
    public function getUsername()
    {
        return $this->_getField(self::FIELD_USERNAME, null);
    }
    
    private $_modelUser;
    public function getUser()
    {
        if(is_null($this->_modelUser)) {
            $this->_modelUser = Chaplin_Gateway::getInstance()
                ->getUser()
                ->getByUsername($this->getUsername());
        }
        return $this->_modelUser;
    }
    
    public function getComment()
    {
        return $this->_getField(self::FIELD_COMMENT, null);
    }
}
