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
 * @author    Kathie Dart <chaplin@kathiedart.uk>
 * @copyright 2012-2017 Project Chaplin
 * @license   http://www.gnu.org/licenses/agpl-3.0.html GNU AGPL 3.0
 * @version   GIT: $Id$
 * @link      https://github.com/kathiedart/projectchaplin
**/
namespace Chaplin\Application\Bootstrap;

use Chaplin\Config\Env;
use Exception;
use Zend_Application_Bootstrap_Bootstrap as ZendBootstrap;
use Zend_Mail as ZendMail;
use Zend_Mail_Transport_Smtp as TransportSmtp;

class Cli extends ZendBootstrap
{
    protected function _initEnvs()
    {
        Env::init();
    }

    protected function _initSmtp()
    {
        $this->bootstrap('env');

        $transport = new TransportSmtp(
            SMTP_HOST,
            [
                "port"      => SMTP_PORT,
                "user"      => SMTP_USER,
                "password"  => SMTP_PASSWORD,
                "tls"       => SMTP_USE_TLS
            ]
        );
        ZendMail::setDefaultTransport($transport);
    }

    protected function _bootstrap($resource = null)
    {
        try {
            parent::_bootstrap($resource);
        } catch(Exception $e) {
            echo $e->getMessage();
            flush();
        }
    }
    public function run()
    {
        try {
            parent::run();
        } catch(Exception $e) {
            echo $e->getMessage();
            ob_flush();
            flush();
        }
    }
}
