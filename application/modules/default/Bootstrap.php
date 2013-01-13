<?php
class Default_Bootstrap extends Zend_Application_Module_Bootstrap
{
    // Load the local models and forms
    protected function _initModuleAutoloader()
    {    
        $this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'default',
            'basePath'  => APPLICATION_PATH . '/modules/default',
        ));  
    }    
    protected function _initAcl()
    {    
        //$acl = $this->getApplication()->getResource('acl');     
        $acl = Zend_Registry::get('acl');
        {    
            $acl->add(new Zend_Acl_Resource('default/index'));
            $acl->add(new Zend_Acl_Resource('default/broadcast'));
            $acl->add(new Zend_Acl_Resource('default/error'));
            $acl->add(new Zend_Acl_Resource('default/login'));
            $acl->add(new Zend_Acl_Resource('default/search'));
            $acl->add(new Zend_Acl_Resource('default/video'));
                 
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'default/index');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_USER, 'default/broadcast');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'default/error');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'default/login');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'default/search');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_USER, 'default/video');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'default/video', 'watch');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'default/video', 'watchyoutube');
        }
    }
}
