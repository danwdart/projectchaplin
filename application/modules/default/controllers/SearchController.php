<?php
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
        $query = $yt->newVideoQuery();
        $query->videoQuery = urlencode($strSearchTerm);
        $query->startIndex = 0;
        $query->maxResults = 50;
        $query->orderBy = 'viewCount';
 
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

