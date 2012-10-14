<?php
class Admin_ImportController extends Zend_Controller_Action
{
	private $_strAcceptableTypes = '3gp|3gpp|flv|asf|mov|rm|wmv|mp4|mpg|webm|avi|mkv|ogv';

	public function indexAction()
	{
		$form = new Admin_Form_Import_Directory();

		if(!$this->_request->isPost()) {
			return $this->view->assign('form', $form);
		}

		if(!$form->isValid($this->_request->getPost())) {
			return $this->view->assign('form', $form);	
		}

		$strDirectory = $form->Directory->getValue();

		// This is strangely slower
		$ittFiles = new Chaplin_Iterator_Filter_File(
			new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator(
					$strDirectory,
					FilesystemIterator::KEY_AS_PATHNAME |
					FilesystemIterator::CURRENT_AS_FILEINFO |
					FilesystemIterator::FOLLOW_SYMLINKS |
					FilesystemIterator::SKIP_DOTS
				),
				RecursiveIteratorIterator::LEAVES_ONLY,
    			RecursiveIteratorIterator::CATCH_GET_CHILD
			)
		);
		$ittFiles->setAcceptableTypes($this->_strAcceptableTypes);

		$form = new Admin_Form_Import_Files($ittFiles);

		$this->view->assign('form', $form);
	}
}