<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 08/01/17
 * Time: 17:09
 */

define('APP_PATH', dirname(dirname(__FILE__)));
define('APP_START_TIME', microtime(true));
define('APP_START_MEMORY', memory_get_usage());

setlocale(LC_ALL, 'en_US.utf-8');

if (function_exists('mb_internal_encoding')) {
    mb_internal_encoding('utf-8');
}

if (function_exists('mb_substitute_character')) {
    mb_substitute_character('none');
}

include_once APP_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
include_once APP_PATH . DIRECTORY_SEPARATOR . 'config.php';
include_once APP_PATH . DIRECTORY_SEPARATOR . 'helpers.php';

/** @noinspection PhpUndefinedFieldInspection */
ORM::configure(\Bitefight\Config::DB_ADAPTER.':host='.\Bitefight\Config::DB_HOST.';dbname='.\Bitefight\Config::DB_NAME);
/** @noinspection PhpUndefinedFieldInspection */
ORM::configure('username', \Bitefight\Config::DB_USERNAME);
/** @noinspection PhpUndefinedFieldInspection */
ORM::configure('password', \Bitefight\Config::DB_PASSWORD);

/*
 * If you don't want your ide to warn you about missing phalcon
 * classes, go download phalcon developer tools
 * and add phalcon stubs to your include path
 */
$loader = new \Phalcon\Loader();

$loader->registerNamespaces([
    'Bitefight\\Controllers' => APP_PATH . DIRECTORY_SEPARATOR . 'controllers'
]);

$loader->register();

$di = new \Phalcon\Di\FactoryDefault();

$di->set('router', function () {
    $router = require APP_PATH . DIRECTORY_SEPARATOR . 'routes.php';
    return $router;
});

$di->set('dispatcher', function() use ($di) {
    $dispatcher = new \Phalcon\Mvc\Dispatcher();
    $dispatcher->setActionSuffix('');

    $evManager = $di->getShared('eventsManager');

    /** @noinspection PhpUnusedParameterInspection */
    $evManager->attach("dispatch:beforeException", function($event, $dispatcher, $exception) {
            /**
             * @var Exception $exception
             * @var \Phalcon\Mvc\Dispatcher $dispatcher
             */
            switch ($exception->getCode()) {
                case \Phalcon\Mvc\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                case \Phalcon\Mvc\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                    $dispatcher->forward(
                        array(
                            'controller' => 'error',
                            'action'     => 'show404',
                        )
                    );
                    return false;
            }

            return true;
        }
    );

    return $dispatcher;
});

$di->setShared("session", function () {
    $session = new \Phalcon\Session\Adapter\Files();
    $session->start();
    return $session;
});

$di->set("flashSession", function () {
    $flash = new \Phalcon\Flash\Session(
        [
            "error"   => "error",
            "success" => "success",
            "notice"  => "info",
            "warning" => "warning",
        ]
    );

    return $flash;
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
    /** @noinspection PhpUndefinedClassInspection */
    $application = new \Phalcon\Mvc\Application($di);
    echo $application->handle()->getContent();
} catch (Exception $e) {
    echo $e->getMessage();
}