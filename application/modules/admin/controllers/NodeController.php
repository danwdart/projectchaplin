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
class Admin_NodeController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->view->ittNodes = Chaplin_Gateway::getInstance()
            ->getNode()
            ->getAllNodes();
    }
    
    public function createAction()
    {
        $form = new Admin_Form_Node_Create();
        if(!$this->_request->isPost()) {
            return $this->view->form = $form;
        }
        if(!$form->isValid($this->_request->getPost())) {
            return $this->view->form = $form;
        }
        
        $modelNode = Chaplin_Model_Node::create(
            $form->IP->getValue(),
            $form->Name->getValue()
        );
        $modelNode->save();
        
        $this->_helper->FlashMessenger('Added Node');
        return $this->_redirect('/admin/node');
    }

    public function deleteAction()
    {
        $strNodeId = $this->_request->getParam('NodeId', null);
        if(!is_null($strNodeId)) {
            Chaplin_Gateway::getNode()->deleteById($strNodeId);
        }
        return $this->_redirect('/admin/node');
    }
    
    public function pingAction()
    {
        $strNodeId = $this->_request->getParam('NodeId', null);
        if(is_null($strNodeId)) {
            return $this->_redirect('/admin/node');
        }
        
        $modelNode = Chaplin_Gateway::getInstance()
            ->getNode()
            ->getByNodeId($strNodeId);
            
        if(!$modelNode->ping()) {
            $this->_helper->FlashMessenger('Host '.$modelNode->getIP().' is not responding.');
        }
        
        return $this->_redirect('/admin/node');
    }
}
