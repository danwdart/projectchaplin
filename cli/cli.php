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
require __DIR__.'/../vendor/autoload.php';

set_time_limit(0);

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/../cli/config/application.ini'
);
$application->bootstrap();//            ->run();

$strController = isset($argv[1])?$argv[1]:'cli';
$strAction = isset($argv[2])?$argv[2]:'cli';

$arrParams = array();

$front = Zend_Controller_Front::getInstance();
$front->setRequest(
    new Zend_Controller_Request_Simple(
        $strAction,
        $strController,
        'cli',
        $arrParams
    )
);

$front->setRouter(	new Chaplin_Controller_Router_Cli());
$front->setResponse(new Zend_Controller_Response_Cli());
$errorHandler = new Zend_Controller_Plugin_ErrorHandler();
$front->registerPlugin($errorHandler, 100);
$error = $front->getPlugin('Zend_Controller_Plugin_ErrorHandler');
$error->setErrorHandler(
    array(
        'controller'	=> 'clierror',
        'action'		=> 'error'
    )
);
$front->dispatch();
