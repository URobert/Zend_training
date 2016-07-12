<?php

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
    APPLICATION_PATH . '/configs/application.ini'
);

$router = Zend_Controller_Front::getInstance()->getRouter();
$router->addRoute('county.edit', new Zend_Controller_Router_Route(
	'county/edit/:id',
	array(
		'controller' => 'county',
		'action' => 'edit'
	)
));
$router->addRoute('weather.list', new Zend_Controller_Router_Route(
	'city/weather',
	array(
		'controller' => 'weatherforecast',
		'action' => 'list'
	)
));

$router->addRoute('weather.import', new Zend_Controller_Router_Route(
	'weather/weatherImport',
	array(
		'controller' => 'weatherforecast',
		'action' => 'import'
	)
));

$router->addRoute('city.search', new Zend_Controller_Router_Route(
	'city/search/:mapid',
	array(
		'controller' => 'city',
		'action' => 'search'
	)
));

$router->addRoute('home.search', new Zend_Controller_Router_Route(
	'/home/search',
	array(
		'controller' => 'searchlocation',
		'action' => 'search'
	)
));

$application->bootstrap()
            ->run();