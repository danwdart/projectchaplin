<?php
interface Chaplin_Dao_Interface_Video extends Chaplin_Dao_Interface
{
    public function getFeaturedVideos();
    
    public function getByVideoId($strVideoId);
    
    public function getByUser(Chaplin_Model_User $modelUser);
        
    public function delete(Chaplin_Model_Video $modelVideo);

    public function save(Chaplin_Model_Video $modelVideo);
}
