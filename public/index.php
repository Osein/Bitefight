<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 08/01/17
 * Time: 17:09
 */

define('APP_PATH', dirname(dirname(__FILE__)));

include APP_PATH . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . 'idiorm.php';
include APP_PATH . DIRECTORY_SEPARATOR . 'config.php';
include APP_PATH . DIRECTORY_SEPARATOR . 'helpers.php';

ORM::configure($config->database->adapter.':host='.$config->database->host.';dbname='.$config->database->dbname);
ORM::configure('username', $config->database->username);
ORM::configure('password', $config->database->password);

/*
 * If you don't want your ide to warn you about missing phalcon
 * classes, go download phalcon developer tools
 * and add phalcon stubs to your include path
 */
$loader = new \Phalcon\Loader();

$loader->registerDirs([
    APP_PATH . DIRECTORY_SEPARATOR . 'controllers'
]);

$loader->register();

$di = new \Phalcon\Di\FactoryDefault();

$di->set('router', function () {
    $router = require APP_PATH . DIRECTORY_SEPARATOR . 'routes.php';
    return $router;
});

$di->set('dispatcher', function() {
    $dispatcher = new \Phalcon\Mvc\Dispatcher();
    $dispatcher->setActionSuffix('');
    return $dispatcher;
});

$di->set('view', function () {
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir(APP_PATH . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);
    $view->disableLevel([
        \Phalcon\Mvc\View::LEVEL_LAYOUT
    ]);
    return $view;
}, true);

try {
    $application = new \Phalcon\Mvc\Application($di);
    $response = $application->handle();
    $response->send();
} catch (Exception $e) {
    echo $e->getMessage();
}