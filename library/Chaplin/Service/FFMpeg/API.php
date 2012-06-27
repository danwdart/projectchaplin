<?php
class Chaplin_Service_AVConv_API
{
    private $_ffmpeg;
    
    public function __construct()
    {
        $this->_ffmpeg = new stdObject();
    }
    
    public function convertFile($strFile, $strOut)
    {
        //TODO: stream status
        system('avconv -i '.$strFile.' '.$strOut);
    }
    
    public function getThumbnail($strFile, $strOut)
    {
        system('avconv -i '.$strFile.' 'n
    }
}
