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
namespace Chaplin\Module\Api\Controller;

use Chaplin\Controller\Action\Api as ApiController;
use Chaplin\Gateway;
use Chaplin\Service;
use Zend_Uri as Uri;
use Zend_Uri_Exception as UriException;

class SearchController extends ApiController
{
    private function extractYouTubeID(string $strSearchTerm) : string
    {
        try {
            // TODO : search helper
            // Note: only full Scheme://FQDN/Path URLs are supported currently
            $uri = Uri::factory($strSearchTerm);
            // Detect YouTube
            if (false !== strpos($uri->getHost(), 'youtube.com')) {
                $strQS = $uri->getQuery();
                parse_str($strQS, $arrQuery);
                if (isset($arrQuery['v'])) {
                    $strSearchTerm = $arrQuery['v'];
                }
            }
            // Insert other detectors here
        } catch (UriException $e) {
            // That's fine
        }

        return $strSearchTerm;
    }

    public function getIndex()
    {
        $strSearchTerm = $this->_request->getQuery('search');
        $strSearchTerm = htmlentities($strSearchTerm);
        $this->view->strTitle = $strSearchTerm.': Search - Chaplin';

        if (is_null($strSearchTerm)) {
            $this->redirect('/');
            return;
        }

        $strSearchTerm = $this->extractYouTubeID($strSearchTerm);

        $ittVideos = Gateway::getInstance()
            ->getVideo()
            ->getBySearchTerms($strSearchTerm);

        if ($this->isAPICall()) {
            return $this->view->assign($ittVideos->toArray());
        }

        $this->view->assign('strSearchTerm', $strSearchTerm);
        $this->view->assign('ittVideos', $ittVideos);

        $service = Service::getInstance();

        // Retrieve Youtube results
        $serviceYouTube = $service->getYouTube();
        $ytUser = $serviceYouTube->getUserProfile($strSearchTerm);
        $videoFeed = $serviceYouTube->search($strSearchTerm);

        // Retrieve Vimeo results
        $serviceVimeo = $service->getVimeo();
        $vimeoUser = $serviceVimeo->getUserProfile($strSearchTerm);
        $vimeoFeed = $serviceVimeo->search($strSearchTerm);

        $this->view->vimeoUser = $vimeoUser;
        $this->view->ytUser = $ytUser;

        //$dm = new Dailymotion();
        //$result = $dm->get('/search/'.urlencode($strSearchTerm));
        //die(var_dump($result));
        $this->view->videoFeed = $videoFeed;
        $this->view->vimeoFeed = $vimeoFeed;
    }

    public function getIndexAPI()
    {
        $strSearchTerm = htmlentities($this->_request->getQuery('search'));

        if (is_null($strSearchTerm)) {
            $this->view->assign([]);
            return;
        }

        $ittVideos = Gateway::getInstance()
            ->getVideo()
            ->getBySearchTerms($strSearchTerm);

        $this->view->assign($ittVideos->toArray());
    }

    public function getYoutube()
    {
        $intSkip = $this->_request->getQuery('skip');
        $intLimit = $this->_request->getQuery('limit');
        $strSearchTerm = $this->_request->getQuery('search');
        $strSearchTerm = htmlentities($strSearchTerm);

        if (is_null($strSearchTerm)) {
            $this->redirect('/');
            return;
        }

        $this->view->assign('strSearchTerm', $strSearchTerm);
        // Retrieve Youtube results

        $serviceYouTube = Service::getInstance()->getYouTube();

        $videoFeed = $serviceYouTube->search($strSearchTerm, $intSkip, $intLimit);

        $this->view->videoFeed = $videoFeed;
    }
}
