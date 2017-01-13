<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 08/01/17
 * Time: 17:13
 */

$router = new \Phalcon\Mvc\Router(false);
$router->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);
$router->removeExtraSlashes(true);
$router->setDefaultController('Home');
$router->setDefaultAction('getIndex');

$news = new \Phalcon\Mvc\Router\Group(['controller' => 'Home']);
$news->setPrefix('/news');
$news->add('', ['action' => 'getNews']);
$router->mount($news);

return $router;