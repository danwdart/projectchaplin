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
 * @link      https://github.com/kathiedart/projectchaplin
**/
namespace Chaplin\Module\Cli\Controller;

use Zend_Controller_Action as Controller;
use Zend_Controller_Plugin_ErrorHandler as ErrorHandler;

class ClierrorController extends Controller
{
    public function preDispatch()
    {
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        switch ($errors->type) {
        case ErrorHandler::EXCEPTION_NO_ROUTE:
        case ErrorHandler::EXCEPTION_NO_CONTROLLER:
        case ErrorHandler::EXCEPTION_NO_ACTION:
            echo 'Not Found - no route, controller or action'.PHP_EOL;
            var_dump($this->_request->getParams());
            ob_flush();
            flush();
            break;
        default:
            echo 'something broke horribly'.PHP_EOL;
            echo get_class($errors->exception).PHP_EOL;
            echo $errors->exception->getMessage().PHP_EOL;
            echo $errors->exception->getTraceAsString().PHP_EOL;
            ob_flush();
            flush();
            break;
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            echo get_class($this->exception);
            echo $this->exception->getMessage();
            echo $this->exception->getTraceAsString();
            ob_flush();
            flush();
        }
        //$this->_request);
    }
}
