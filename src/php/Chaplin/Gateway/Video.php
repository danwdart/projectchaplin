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
 * @package   ProjectChaplin
 * @author    Dan Dart <chaplin@dandart.co.uk>
 * @copyright 2012-2018 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   GIT: $Id$
 * @link      https://github.com/danwdart/projectchaplin
**/
class Chaplin_Gateway_Video extends Chaplin_Gateway_Abstract
{
    private $_daoVideo;

    public function __construct(Chaplin_Dao_Interface_Video $daoVideo)
    {
        $this->_daoVideo = $daoVideo;
    }

    public function getFeaturedVideos(Chaplin_Model_User $modelUser = null)
    {
        return $this->_daoVideo->getFeaturedVideos($modelUser);
    }

    public function getByVideoId($strVideoId, Chaplin_Model_User $modelUser = null)
    {
        return $this->_daoVideo->getByVideoId($strVideoId, $modelUser);
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
