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
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
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

    protected function _initApi()
    {
        $this->bootstrap('frontController');
        Zend_Controller_Action_HelperBroker::addPrefix('Chaplin_Controller_Action_Helper');
        $this->frontController->registerPlugin(new Chaplin_Controller_Plugin_Api());
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

        $chapli = new Zend_Controller_Router_Route_Hostname(
            'chap.li',
            array(
                'controller' => 'video',
                'action' => 'watchshort'
            )
        );

        $route = new Zend_Controller_Router_Route(
            '/:id',
            array(
                'controller' => 'video',
                'action' => 'watchshort',
                'id' => null
            )
        );
        $router->addRoute('watchshort', $chapli->chain($route));
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
        if (!is_null($configSessions->getSaveHandler())) {
            Zend_Session::setSaveHandler($configSessions->getSaveHandler());
        }
        if (!is_null($configSessions->getSessionOptions())) {
            Zend_Session::setOptions($configSessions->getSessionOptions());
        }
        Zend_Session::start();
    }

    protected function _initSmtp()
    {
        // Don't initialise on setup - configs don't exist yet
        if (0 === strpos($_SERVER['REQUEST_URI'], '/admin/setup')) return;

        $configSmtp = Chaplin_Config_Servers::getInstance();
        $arrSmtp = $configSmtp->getSmtpSettings();
        $transport = new Zend_Mail_Transport_Smtp(
            $arrSmtp['server']['host'],
            $arrSmtp['server']['options']
        );
        Zend_Mail::setDefaultTransport($transport);
    }

    protected function _bootstrap($resource = null)
    {
        try {
            parent::_bootstrap($resource);
        } catch(Exception $e) {
            echo '<pre>'.$e->getMessage().PHP_EOL.$e->getTraceAsString().'</pre>';
        }
    }

    public function run()
    {
       try {
           parent::run();
       } catch(Exception $e) {
           echo '<pre>'.$e->getMessage().PHP_EOL.$e->getTraceAsString().'</pre>';
       }
    }
}
