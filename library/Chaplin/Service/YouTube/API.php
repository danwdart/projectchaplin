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
        return exec($strCommandLine);
    }
    
    public function downloadVideo($strPathToSave)
    {
        $strCommandLine = APPLICATION_PATH.self::LOCATION.
            " --prefer-free-formats -o '".
            $strPathToSave."/%(title)s.%(ext)s' ".'"'.$this->_strURL.'"';
        echo $strCommandLine.PHP_EOL;
        ob_flush();
        flush();
        system($strCommandLine);
    }

    public function downloadThumbnail($strPathToSave)
    {
        $yt = new Zend_Gdata_YouTube();
        $entryVideo = $yt->getVideoEntry($this->_strURL);

        $strFilename = $strPathToSave.'/'.$entryVideo->getVideoTitle().'.webm.png';

        $arrThumbnails = $entryVideo->getVideoThumbnails();
        if (!isset($arrThumbnails[0])) {
            throw new Exception('No thumbnails?');
        }
        $strURL = $arrThumbnails[0]['url'];

        $strImage = file_get_contents($strURL);
        file_put_contents($strFilename, $strImage);

        return '/uploads/'.basename($strFilename);
    }
}
