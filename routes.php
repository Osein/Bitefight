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
$user->addPost('/profile/training', ['action' => 'postTrainingUp']);
$user->addGet('/profile/logo', ['action' => 'getProfileLogo']);
$user->addPost('/profile/logo', ['action' => 'postProfileLogo']);
$user->addGet('/news', ['action' => 'getNews']);
$router->mount($user);

$hunt = new \Phalcon\Mvc\Router\Group(['controller' => 'Hunt']);
$hunt->setPrefix('/hunt');
$hunt->addGet('/index', ['action' => 'getHunt']);
$hunt->addGet('/human/{id:[1-5]}', ['action' => 'getHumanHunt']);
$router->mount($hunt);

$router->addGet('/logout', [
    'controller' => 'User',
    'action' => 'getLogout'
]);

return $router;