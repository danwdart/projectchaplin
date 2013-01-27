<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Chaplin_');
        $autoloader->registerNamespace('Amqp_');
        $autoloader->registerNamespace('Mongo_');
        $autoloader->registerNamespace('FFMpeg\\');
        $autoloader->registerNamespace('Monolog\\');
    }

    protected function _initAcl()
    {
        $acl = new Zend_Acl();

        $acl->addRole(
            new Zend_Acl_Role(
                Chaplin_Model_User_Helper_UserType::TYPE_GUEST
            )
        );

        $acl->addRole(
            new Zend_Acl_Role(
                Chaplin_Model_User_Helper_UserType::TYPE_USER
            ),
            Chaplin_Model_User_Helper_UserType::TYPE_GUEST
        );

        $acl->addRole(
            new Zend_Acl_Role(
                Chaplin_Model_User_Helper_UserType::TYPE_SILVER
            ),
            Chaplin_Model_User_Helper_UserType::TYPE_USER
        );

        $acl->addRole(
            new Zend_Acl_Role(
                Chaplin_Model_User_Helper_UserType::TYPE_GOLD
            ),
            Chaplin_Model_User_Helper_UserType::TYPE_SILVER
        );

        $acl->addRole(
            new Zend_Acl_Role(
                Chaplin_Model_User_Helper_UserType::TYPE_MINION
            ),
            Chaplin_Model_User_Helper_UserType::TYPE_GOLD
        );

        $acl->addRole(
            new Zend_Acl_Role(
                Chaplin_Model_User_Helper_UserType::TYPE_GOD
            ),
            Chaplin_Model_User_Helper_UserType::TYPE_MINION
        );
        
        $this->bootstrap('frontController');
        $this->frontController->registerPlugin(new Chaplin_Controller_Plugin_Acl($acl));
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole(Chaplin_Model_User_Helper_UserType::TYPE_GUEST);
        Zend_Registry::set('acl', $acl);
        
    }
    
    protected function _initIniValues()
    {
        ini_set('post_max_size', '2000M');
        ini_set('upload_max_filesize', '2000M');
    }
    
    protected function _initRoutes()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
/*
        $route = new Zend_Controller_Router_Route_Regex('.*-p(\d+).htm',
            array(
                'controller' => 'product',
                'action'     => 'index'
            ),
            array(1 => 'id')
        );
        $router->addRoute('product', $route);
        */
        $route = new Zend_Controller_Router_Route(
            'user/:id/:action',
            array(
                'controller' => 'user',
                'action' => 'index',
                'id' => null
            )
        );
        $router->addRoute('user', $route);

        $route = new Zend_Controller_Router_Route_Static(
            'logout',
            array(
                'controller' => 'login',
                'action' => 'logout'
            )
        );
        $router->addRoute('logout', $route);
        
        $route = new Zend_Controller_Router_Route_Static(
            'userinfo',
            array(
                'controller' => 'login',
                'action' => 'userinfo'
            )
        );
        $router->addRoute('userinfo', $route);
    }
    
    protected function _initSession()
    {
        $configSessions = Chaplin_Config_Sessions::getInstance();
        $configServers = Chaplin_Config_Servers::getInstance();
        if ($configServers->getRedisSettings()) {
            $strRegistryKey = $configServers->getRedisSettings()->registrykey;
            $intTimeout = (int)$configServers->getRedisSettings()->timeout;
            $arrServers = $configServers->getRedisSettings()->servers->toArray();
            $redis = new Redis();
            $strHost = $arrServers[0]['host'];
            $strPort = $arrServers[0]['port'];
            $redis->connect($strHost, $strPort, $intTimeout);
            Zend_Registry::set($strRegistryKey, $redis);
        }
        if (!is_null($configSessions->getSaveHandler())) {
            Zend_Session::setSaveHandler($configSessions->getSaveHandler());
        }
        if (!is_null($configSessions->getSessionOptions())) {
            Zend_Session::setOptions($configSessions->getSessionOptions());
        }
        Zend_Session::start();
    }

    protected function _bootstrap($resource = null)
    {
        try {
            parent::_bootstrap($resource);
        } catch(Exception $e) {
            echo '<h1>'.$e->getMessage().'</h1>';
        }
    } 

    public function run()
    {
       try {
           parent::run();
       } catch(Exception $e) {
           echo '<h1>'.$e->getMessage().'</h1>';
       }
    }
}
