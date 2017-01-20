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

$router->addGet('/news', ['controller' => 'Home', 'action' => 'getNews']);
$router->addGet('/highscore', ['controller' => 'Home', 'action' => 'getHighscore']);

$register = new \Phalcon\Mvc\Router\Group(['controller' => 'Home']);
$register->setPrefix('/register');
$register->addGet('/{id:[0-9]}', ['action' => 'getRegister']);
$register->addPost('/{id:[0-9]}', ['action' => 'postRegister']);
$register->addGet('/ajaxcheck', ['action' => 'postAjaxCheck']);
$router->mount($register);

$router->addGet('/login', ['controller' => 'Home', 'action' => 'getLogin']);
$router->addPost('/login', ['controller' => 'Home', 'action' => 'postLogin']);
$router->addGet('/user/lostpw', ['controller' => 'Home', 'action' => 'getLostPassword']);
$router->addPost('/user/lostpw', ['controller' => 'Home', 'action' => 'postLostPassword']);

$user = new \Phalcon\Mvc\Router\Group(['controller' => 'User']);
$user->setPrefix('/user');
$user->addGet('/profile', ['action' => 'getProfile']);
$user->addPost('/profile/training', ['action' => 'postTrainingUp']);
$user->addGet('/profile/logo', ['action' => 'getProfileLogo']);
$user->addPost('/profile/logo', ['action' => 'postProfileLogo']);
$user->addGet('/news', ['action' => 'getNews']);
$user->addGet('/settings', ['action' => 'getSettings']);
$user->addPost('/settings', ['action' => 'postSettings']);
$user->addGet('/highscore', ['action' => 'getHighscore']);
$router->mount($user);

$hunt = new \Phalcon\Mvc\Router\Group(['controller' => 'Hunt']);
$hunt->setPrefix('/hunt');
$hunt->addGet('/index', ['action' => 'getHunt']);
$hunt->addGet('/human/{id:[1-5]}', ['action' => 'getHumanHunt']);
$router->mount($hunt);

$hideout = new \Phalcon\Mvc\Router\Group(['controller' => 'User']);
$hideout->setPrefix('/hideout');
$hideout->addGet('', ['action' => 'getHideout']);
$hideout->addGet('/upgrade', ['action' => 'getHideoutUpgrade']);
$router->mount($hideout);

$router->addGet('/profile/player/{id:[0-9]+}', ['controller' => 'User', 'action' => 'getPreview']);
$router->addGet('/search', ['controller' => 'Game', 'action' => 'getSearch']);
$router->addPost('/search', ['controller' => 'Game', 'action' => 'postSearch']);
$router->addGet('/notepad', ['controller' => 'User', 'action' => 'getNotepad']);
$router->addPost('/notepad', ['controller' => 'User', 'action' => 'postNotepad']);
$router->addGet('/logout', ['controller' => 'User', 'action' => 'getLogout']);

return $router;