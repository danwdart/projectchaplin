<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'testing'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Chaplin_');
$loader->registerNamespace('Hamcrest_');
$loader->registerNamespace('Mongo_');

require_once 'Mockery/Loader.php';
require_once 'Mockery/Configuration.php';
$mockery_loader = new \Mockery\Loader;
$mockery_loader->register();
Mockery::getConfiguration()->allowMockingNonExistentMethods(false);

require_once 'Hamcrest/hamcrest.php';
