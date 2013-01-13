<?php
class Chaplin_Service_YouTube_API
{
    const LOCATION = '/../external/youtube-dl';

    private $_strURL;
    
    public function __construct($strURL)
    {
        $this->_strURL = $strURL;        
    }

    public function getDownloadURL()
    {
        $strCommandLine = APPLICATION_PATH.self::LOCATION.' --prefer-free-formats -g '.$this->_strURL;
        return system($strCommandLine);
    }
    
    public function downloadVideo()
    {
        system('');
    }
    
    public function getThumbnail()
    {
    }
    
    public function getDescription()
    {
    }  
}
