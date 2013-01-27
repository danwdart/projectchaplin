<?php
class Chaplin_Message_Video_Convert
    extends Chaplin_Message_Abstract
{
    const FIELD_VIDEOID = 'VideoId';

    private $_modelVideo;

    public static function create(Chaplin_Model_Video $modelVideo)
    {
        $msgVideo = new self();
        $msgVideo->_setField(self::FIELD_VIDEOID, $modelVideo->getVideoId());
        $msgVideo->_modelVideo = $modelVideo;
        return $msgVideo;
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
    
    public function process()
    {
        $modelVideo = $this->_getModelVideo();
        
        $strFullPath = APPLICATION_PATH.'/../public';
        
        $strFilename = $strFullPath.$modelVideo->getFilename();
        
        echo 'Converting '.$strFilename.PHP_EOL;
        ob_flush();
        flush();
        
        $strPathToWebm = $strFullPath.$modelVideo->getFilename().'.webm';
    
        $ret = 0;
    
        $strError = Chaplin_Service::getInstance()
            ->getAVConv()
            ->convertFile($strFilename, $strPathToWebm, $ret);
        
        if(0 != $ret) {
            throw new Exception('Unable to convert: '.$strFilename);
        }
        
        echo 'Converted '.$strFilename;
        ob_flush();
        flush();
        
        $modelVideo->setFilename($modelVideo->getFilename().'.webm');
        $modelVideo->save();
    }
    
    public function getRoutingKey()
    {
        return 'video.encode.'.$this->_getModelVideo()->getUsername();
    }

    public function getExchangeName()
    {
        return Chaplin_Service_Amqp_Video::EXCHANGE_NAME;
    }
}    
