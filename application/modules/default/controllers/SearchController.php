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
    }
}

