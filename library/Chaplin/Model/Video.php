<?php
class Chaplin_Model_Video extends Chaplin_Model_Abstract
{
    const FIELD_VIDEOID = self::FIELD_ID;
    const FIELD_USERNAME = 'Username';
    const FIELD_FILENAME = 'Filename';
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
    
    public static function create(
        Chaplin_Model_User $modelUser,
        $strFilename, // form element?
        $strTitle
    ) {
        $video = new self();
        $video->_setField(self::FIELD_USERNAME, $modelUser->getUsername();
        $video->_setField(self::FIELD_FILENAME, $strFilename);
        $video_>_setField(self::FIELD_TITLE, $strTitle);
        return $video;
    }
    
    public function save()
    {
        return Chaplin_Gateway::getInstance()
            ->getVideo()
            ->save($this);
    }
}
