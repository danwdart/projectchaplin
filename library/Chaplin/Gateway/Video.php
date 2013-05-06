<?php
/**
 * This file is part of Project Chaplin.
 *
 * Project Chaplin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Project Chaplin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Project Chaplin. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    Project Chaplin
 * @author     Dan Dart
 * @copyright  2012-2013 Project Chaplin
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version    git
 * @link       https://github.com/dandart/projectchaplin
**/
class Chaplin_Gateway_Video
    extends Chaplin_Gateway_Abstract
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
    
    public function getByUserUnnamed(Chaplin_Model_User $modelUser)
    {
        return $this->_daoVideo->getByUserUnnamed($modelUser);
    }
    
    public function getBySearchTerms($strSearchTerms)
    {
        return $this->_daoVideo->getBySearchTerms($strSearchTerms);   
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
