<?php
class Chaplin_Gateway_Video
{
    private $_daoVideo;

    public function __construct(Chaplin_Dao_Interface_Video $daoVideo)
    {
        $this->_daoVideo = $daoVideo;
    }

    public function getFeaturedVideos()
    {
        return $this->_daoVideo->getFeaturedVideos();
    }

    public function getByVideoId($strVideoId)
    {
        return $this->_daoVideo->getByVideoId($strVideoId);
    }
    
    public function getByUser(Chaplin_Model_User $modelUser)
    {
        return $this->_daoVideo->getByUser($modelUser);
    }
    
    public function delete(Chaplin_Model_Video $modelVideo)
    {
        return $this->_daoVideo->delete($modelVideo);
    }

    public function save(Chaplin_Model_Video $modelVideo)
    {
        return $this->_daoVideo->save($modelVideo);
    }
}
