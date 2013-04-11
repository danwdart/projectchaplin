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
class SearchController extends Zend_Controller_Action
{
    public function indexAction()
    {
    	$strSearchTerm = $this->_request->getQuery('search');
    	$strSearchTerm = htmlentities($strSearchTerm);
    	if(is_null($strSearchTerm)) {
    		return $this->_redirect('/');
    	}
    	$this->view->assign('strSearchTerm', $strSearchTerm);
        $this->view->assign('ittVideos', Chaplin_Gateway::getInstance()
            ->getVideo()
            ->getBySearchTerms($strSearchTerm)
        );

        // Retrieve Youtube results

        $yt = new Zend_Gdata_YouTube();
        $yt->setMajorProtocolVersion(2);
        $query = $yt->newVideoQuery();
        $query->videoQuery = urlencode($strSearchTerm);
        $query->startIndex = 0;
        $query->maxResults = 50;
        $query->orderBy = 'relevance';

        try {
            $this->view->ytUser = $yt->getUserProfile($strSearchTerm);
        } catch (Exception $e) {}

        $this->view->videoFeed = $yt->getVideoFeed($query);
    }

    public function youtubeAction()
    {
        $this->_helper->layout()->disableLayout();
        $intSkip = $this->_request->getQuery('skip');
        $intLimit = $this->_request->getQuery('limit');
        $strSearchTerm = $this->_request->getQuery('search');
        $strSearchTerm = htmlentities($strSearchTerm);
        if(is_null($strSearchTerm)) {
            return $this->_redirect('/');
        }
        $this->view->assign('strSearchTerm', $strSearchTerm);

        // Retrieve Youtube results

        $yt = new Zend_Gdata_YouTube();
        $query = $yt->newVideoQuery();
        $query->videoQuery = urlencode($strSearchTerm);
        $query->startIndex = (is_null($intSkip))?0:(int)$intSkip;
        $query->maxResults = (is_null($intLimit))?0:(int)$intLimit;
        $query->orderBy = 'viewCount';
 
        $this->view->videoFeed = $yt->getVideoFeed($query);
    }
}

