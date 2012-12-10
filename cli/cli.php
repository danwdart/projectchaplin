<?php
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

/** Zend_Application */
require_once 'Zend/Application.php';

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
        'controller'	=> 'error',
        'action'		=> 'error'
    )
);
$front->dispatch();
