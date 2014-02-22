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
class cliBootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initSmtp()
    {
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
            echo $e->getException();
            flush();
        }
    }
    public function run()
    {
        try {
            parent::run();
        } catch(Exception $e) {
            echo $e->getException();
            ob_flush();
            flush();
        }
    }
}

