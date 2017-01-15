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
$news->addGet('', ['action' => 'getNews']);
$router->mount($news);

$register = new \Phalcon\Mvc\Router\Group(['controller' => 'Home']);
$register->setPrefix('/register');
$register->addGet('/{id:[0-9]}', ['action' => 'getRegister']);
$register->addPost('/{id:[0-9]}', ['action' => 'postRegister']);
$register->addGet('/ajaxcheck', ['action' => 'postAjaxCheck']);
$router->mount($register);

$login = new \Phalcon\Mvc\Router\Group(['controller' => 'Home']);
$login->setPrefix('/login');
$login->addGet('', ['action' => 'getLogin']);
$login->addPost('', ['action' => 'postLogin']);
$router->mount($login);

$user = new \Phalcon\Mvc\Router\Group(['controller' => 'User']);
$user->setPrefix('/user');
$user->addGet('/profile', ['action' => 'getProfile']);
$user->addGet('/news', ['action' => 'getNews']);
$router->mount($user);

return $router;