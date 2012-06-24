<?php
class Chaplin_Model_Video_Comment extends Chaplin_Model_Abstract_Child
{
    const FIELD_USERNAME = 'Username';
    const FIELD_COMMENT = 'Comment';
    //Feedback
    
    protected static $_arrFields = array(
        self::FIELD_USERNAME => 'Chaplin_Model_Field_Field',
        self::FIELD_COMMENT => 'Chaplin_Model_Field_Field'
    );
    
    public static function create(
        Chaplin_Model_Video $modelVideo,
        Chaplin_Model_User $modelUser,
        $strComment
    ) {
        $comment = new self($modelVideo);
        $comment->setCId(md5(new MongoId());
        $comment->_setField(self::FIELD_USERNAME, $modelUser->getUsername());
        $comment->_setField(self::FIELD_COMMENT, $strComment);
        return $video;
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
