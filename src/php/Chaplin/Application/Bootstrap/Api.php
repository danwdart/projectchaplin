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
namespace Chaplin\Application\Bootstrap;

use Chaplin\Config\Sessions as ConfigSessions;
use Chaplin_Controller_Action_Helper_RestContextSwitch as RestContextSwitch;
use Chaplin_Controller_Plugin_Acl as PluginAcl;
use Chaplin_Controller_Plugin_Api as PluginApi;
use Chaplin_Model_User_Helper_UserType as UserType;
use Exception;
use Zend_Acl as Acl;
use Zend_Acl_Role as Role;
use Zend_Application_Bootstrap_Bootstrap as ZendBootstrap;
use Zend_Controller_Action_HelperBroker as HelperBroker;
use Zend_Controller_Front as Front;
use Zend_Controller_Router_Route as Route;
use Zend_Controller_Router_Route_Hostname as RouteHostname;
use Zend_Controller_Router_Route_Static as RouteStatic;
use Zend_Mail as ZendMail;
use Zend_Mail_Transport_Smtp as TransportSmtp;
use Zend_Registry as Reg;
use Zend_Session as Session;
use Zend_View_Helper_Navigation_HelperAbstract as HelperAbstract;

class Api extends ZendBootstrap
{
    protected function _initAcl()
    {
        $acl = new Acl();

        $acl->addRole(
            new Role(
                UserType::TYPE_GUEST
            )
        );

        $acl->addRole(
            new Role(
                UserType::TYPE_USER
            ),
            UserType::TYPE_GUEST
        );

        $acl->addRole(
            new Role(
                UserType::TYPE_SILVER
            ),
            UserType::TYPE_USER
        );

        $acl->addRole(
            new Role(
                UserType::TYPE_GOLD
            ),
            UserType::TYPE_SILVER
        );

        $acl->addRole(
            new Role(
                UserType::TYPE_MINION
            ),
            UserType::TYPE_GOLD
        );

        $acl->addRole(
            new Role(
                UserType::TYPE_GOD
            ),
            UserType::TYPE_MINION
        );

        $this->bootstrap('frontController');
        $this->frontController->registerPlugin(new PluginAcl($acl));
        HelperAbstract::setDefaultAcl($acl);
        HelperAbstract::setDefaultRole(UserType::TYPE_GUEST);
        Reg::set('acl', $acl);
    }

    protected function _initApi()
    {
        $this->bootstrap('frontController');
        HelperBroker::addPrefix("Chaplin_Controller_Action_Helper");
        HelperBroker::addHelper(new RestContextSwitch());
        $this->frontController->registerPlugin(new PluginApi());
    }

    protected function _initRoutes()
    {
        $router = Front::getInstance()->getRouter();
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

        $chapli = new RouteHostname(
            getenv("VHOST_SHORT"),
            array(
                'controller' => 'video',
                'action' => 'watchshort'
            )
        );

        $route = new Route(
            '/:id',
            array(
                'controller' => 'video',
                'action' => 'watchshort',
                'id' => null
            )
        );
        $router->addRoute('watchshort', $chapli->chain($route));
        $route = new Route(
            'user/:id/:action',
            array(
                'controller' => 'user',
                'action' => 'index',
                'id' => null
            )
        );
        $router->addRoute('user', $route);

        $route = new RouteStatic(
            'logout',
            array(
                'controller' => 'login',
                'action' => 'logout'
            )
        );
        $router->addRoute('logout', $route);

        $route = new RouteStatic(
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
        $configSessions = ConfigSessions::getInstance();
        if (!is_null($configSessions->getSaveHandler())) {
            Session::setSaveHandler($configSessions->getSaveHandler());
        }
        if (!is_null($configSessions->getSessionOptions())) {
            Session::setOptions($configSessions->getSessionOptions());
        }
        Session::start();
    }

    protected function _initSmtp()
    {
        $transport = new TransportSmtp(
            getenv("SMTP_HOST"),
            [
                "port"      => getenv("SMTP_PORT"),
                "username"  => getenv("SMTP_USER"),
                "password"  => getenv("SMTP_PASSWORD"),
                "auth"      => "login",
                "ssl"       => "tls"
            ]
        );
        ZendMail::setDefaultTransport($transport);
    }

    protected function _bootstrap($resource = null)
    {
        try {
            parent::_bootstrap($resource);
        } catch (Exception $e) {
            echo '<pre>'.$e->getMessage().PHP_EOL.$e->getTraceAsString().'</pre>';
        }
    }

    public function run()
    {
        try {
            parent::run();
        } catch (Exception $e) {
            echo '<pre>'.$e->getMessage().PHP_EOL.$e->getTraceAsString().'</pre>';
        }
    }
}
