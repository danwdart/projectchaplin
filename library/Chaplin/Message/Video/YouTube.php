<?php
class Chaplin_Message_Video_YouTube
    extends Chaplin_Message_Abstract
{
    const FIELD_YTID = 'YTId';
    const FIELD_VIDEOID = 'VideoId';

    private $_modelVideo;

    public static function create(Chaplin_Model_Video $modelVideo, $strYouTubeId)
    {
        $msgTest = new self();
        $msgTest->_setField(self::FIELD_VIDEOID, $modelVideo->getVideoId());
        $msgTest->_setField(self::FIELD_YTID, $strYouTubeId);
        return $msgTest;
    }
    
    private function _getVideoId()
    {
        return $this->_getField(self::FIELD_VIDEOID, null);
    }
    
    private function _getModelVideo()
    {
        if(is_null($this->_modelVideo)) {
            $this->_modelVideo = Chaplin_Gateway::getInstance()
                ->getVideo()
                ->getByVideoId($this->_getVideoId());
        }
        
        return $this->_modelVideo;
    }                
    
    private function _getYouTubeId()
    {
        return $this->_getField(self::FIELD_YTID, null);
    }

    public function process()
    {
        echo 'Downloading '.$this->_getYouTubeId().PHP_EOL;
        ob_flush();
        flush();
        
        $strPathToDownloadTo = realpath(APPLICATION_PATH.'/../public/uploads');

        $strOut = Chaplin_Service::getInstance()
            ->getYoutube($this->_getYouTubeId())
            ->downloadVideo($strPathToDownloadTo);

        echo $strOut;
        ob_flush();
        flush();        
        echo 'Downloaded '.$this->_getYouTubeId().PHP_EOL;
        ob_flush();
        flush();
    }
    
    public function getRoutingKey()
    {
        return 'video.youtube.'.$this->_getYouTubeId();
    }

    public function getExchangeName()
    {
        return Chaplin_Service_Amqp_Video::EXCHANGE_NAME;
    }
}    
