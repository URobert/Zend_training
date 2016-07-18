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

$router->addRoute('city.newmap', new Zend_Controller_Router_Route(
	'/city/newmap/:mapid',
	array(
		'controller' => 'city',
		'action' => 'mapcity'
	)
));

$router->addRoute('home.search', new Zend_Controller_Router_Route(
	'/home/search',
	array(
		'controller' => 'searchlocation',
		'action' => 'search'
	)
));

$router->addRoute('home.users', new Zend_Controller_Router_Route(
	'/home/users',
	array(
		'controller' => 'user',
		'action' => 'list'
	)
));

$router->addRoute('home.login', new Zend_Controller_Router_Route(
	'/home/login',
	array(
		'controller' => 'login',
		'action' => 'loginpage'
	)
));

$router->addRoute('home.logout', new Zend_Controller_Router_Route(
	'/home/logout',
	array(
		'controller' => 'login',
		'action' => 'logoutpage'
	)
));

$router->addRoute('home.signup', new Zend_Controller_Router_Route(
	'/home/signup',
	array(
		'controller' => 'signup',
		'action' => 'signupform'
	)
));

function changeQueryString($name, $value)
{
    parse_str($_SERVER['QUERY_STRING'], $temporary);
    $temporary[$name] = $value;
    return http_build_query($temporary);
}

function changePage($base, $target_page)
{
    return $base . "?" . changeQueryString('pn', $target_page);
}

$application->bootstrap()
            ->run();