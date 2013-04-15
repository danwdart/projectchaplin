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

	public function convertAction()
	{
		foreach($this->_request->getPost() as $strFile => $intInclude) {
			if (!$intInclude) {
				continue;
			}
			$strFilename = base64_decode($strFile);


            $strPathToThumb = $strFilename.'.png';
            
            $strRelaFile = basename($strFilename);
            
            $strPath = realpath(APPLICATION_PATH.'/../public/uploads');
            $strFullStoredPath = $strPath.'/'.$strRelaFile;


            copy($strFilename, $strFullStoredPath);
       

            $strRelaThumb = basename($strPathToThumb);
            
            $arrPathInfo = pathinfo($strFilename);
            $strTitle = $arrPathInfo['filename'];
            
            $strRelaPath = '/uploads/';
            
            $ret = 0;
                
            $strError = Chaplin_Service::getInstance()
                ->getAVConv()
                ->getThumbnail($strFilename, $strPathToThumb, $ret);
            if(0 != $ret) {
                die(var_dump($strError));
            }
            
            // Put this somewhere else
            //unlink($strFilename);
            
            $modelUser = Chaplin_Auth::getInstance()->getIdentity()->getUser();
            
            $modelVideo = Chaplin_Model_Video::create(
                $modelUser,
                $strRelaPath.$strRelaFile,
                $strRelaPath.$strRelaThumb,
                $strTitle
            );
            $modelVideo->save();
            
            $modelConvert = Chaplin_Model_Video_Convert::create($modelVideo);
            Chaplin_Gateway::getInstance()->getVideo_Convert()->save($modelConvert);
		}
		$this->_redirect('/');
	}
}