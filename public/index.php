<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 08/01/17
 * Time: 17:09
 */

define('APP_PATH', dirname(dirname(__FILE__)));

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

$di->set('view', function () {
    $view = new \Phalcon\Mvc\View\Simple();

    $view->setViewsDir("../app/views/");

    return $view;
}, true);

try {
    $application = new \Phalcon\Mvc\Application($di);

    $application->useImplicitView(false);

    $response = $application->handle();

    $response->send();
} catch (Exception $e) {
    echo $e->getMessage();
}