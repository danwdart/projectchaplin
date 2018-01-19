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
namespace Chaplin\Module\Api\Controller;

use Chaplin_Exception_NotFound as ExceptionNotFound;
use Zend_Controller_Action as Controller;
use Zend_Controller_Plugin_ErrorHandler as ErrorHandler;

class ErrorController extends Controller
{
    public function getError()
    {
        $errors = $this->_getParam('error_handler');

        if (in_array(
            $errors->type,
            [
                ErrorHandler::EXCEPTION_NO_ROUTE,
                ErrorHandler::EXCEPTION_NO_CONTROLLER,
                ErrorHandler::EXCEPTION_NO_ACTION
            ]
        )
            || $errors->exception instanceof ExceptionNotFound
        ) {
            $this->getResponse()->setHttpResponseCode(404);
            $this->view->message = 'Page not found';
        } else {
            // application error
            $this->getResponse()->setHttpResponseCode(500);
            $this->view->message = 'Application error';
            if ($log = $this->_getLog()) {
                $log->crit($this->view->message . ': ' . $errors->exception->getMessage() . PHP_EOL . 'Exception Class: ' . get_class($errors->exception) . PHP_EOL . 'My Vhost: ' . $this->_request->getServer('HTTP_HOST') . PHP_EOL . 'My Host: ' . gethostname() . PHP_EOL . $errors->exception->getTraceAsString(), $errors->exception);
            }
        }

        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }

        $this->view->request   = $errors->request;
    }

    private function _getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasPluginResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
}
