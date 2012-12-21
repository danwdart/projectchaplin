<?php
class Chaplin_Service_Youtube_API
{
    const LOCATION = '/../external/youtube-dl';

    private $_strURL;
    
    public function __construct($strURL, $strPath)
    {
        $this->_strURL = $strURL;
        
    }
    
    public function downloadVideo($strURL)
    {
        system('');
    }
    
    public function getThumbnail($strURL)
    {
    }
    
    public function getDescription($strURL)
    {
    }  
}
