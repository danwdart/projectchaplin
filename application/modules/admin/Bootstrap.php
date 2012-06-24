<?php
class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{
    // Load the local models and forms
    protected function _initModuleAutoloader()
    {    
        $this->_resourceLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'default',
            'basePath'  => APPLICATION_PATH . '/modules/admin',
        ));  
    }    
    protected function _initAcl()
    {    
        $acl = Zend_Registry::get('acl');
        {    
            $acl->add(new Zend_Acl_Resource('admin/node'));
            $acl->add(new Zend_Acl_Resource('admin/nodestatus'));
            $acl->add(new Zend_Acl_Resource('admin/error'));
                 
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GOD, 'admin/node');
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GUEST, 'admin/nodestatus');            
            $acl->allow(Chaplin_Model_User_Helper_UserType::TYPE_GOD, 'admin/error');
        }
    }
}
