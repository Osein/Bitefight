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
$router->setDefaultNamespace('Bitefight\\Controllers');

$router->addGet('/news', ['controller' => 'Base', 'action' => 'getNews']);
$router->addGet('/highscore', ['controller' => 'Base', 'action' => 'getHighscore']);
$router->addPost('/highscore/mypos', ['controller' => 'Base', 'action' => 'postHighscoreMyPosition']);
$router->addGet('/profile/player/{id:[0-9]+}', ['controller' => 'Base', 'action' => 'getPreview']);

$register = new \Phalcon\Mvc\Router\Group(['controller' => 'Home']);
$register->setPrefix('/register');
$register->addGet('/{id:[0-2]}', ['action' => 'getRegister']);
$register->addPost('/{id:[1-2]}', ['action' => 'postRegister']);
$register->addGet('/ajaxcheck', ['action' => 'postAjaxCheck']);
$router->mount($register);

$router->addGet('/login', ['controller' => 'Home', 'action' => 'getLogin']);
$router->addPost('/login', ['controller' => 'Home', 'action' => 'postLogin']);
$router->addGet('/user/lostpw', ['controller' => 'Home', 'action' => 'getLostPassword']);
$router->addPost('/user/lostpw', ['controller' => 'Home', 'action' => 'postLostPassword']);

$user = new \Phalcon\Mvc\Router\Group(['controller' => 'User']);
$user->setPrefix('/user');
$user->addGet('/profile', ['action' => 'getProfile']);
$user->addPost('/profile/item/equip/{id:[0-9]+}', ['action' => 'postItemEquip']);
$user->addPost('/profile/training', ['action' => 'postTrainingUp']);
$user->addGet('/talents', ['action' => 'getTalents']);
$user->addPost('/talents', ['action' => 'postTalentTopForm']);
$user->addPost('/talent/reset/single', ['action' => 'postTalentResetSingle']);
$user->addPost('/talent/use', ['action' => 'postTalentUse']);
$user->addGet('/profile/logo', ['action' => 'getProfileLogo']);
$user->addPost('/profile/logo', ['action' => 'postProfileLogo']);
$user->addGet('/settings', ['action' => 'getSettings']);
$user->addPost('/settings', ['action' => 'postSettings']);
$router->mount($user);

$router->addGet('/hideout', ['controller' => 'User', 'action' => 'getHideout']);
$router->addGet('/hideout/upgrade', ['controller' => 'User', 'action' => 'getHideoutUpgrade']);

$message = new \Phalcon\Mvc\Router\Group(['controller' => 'Message']);
$message->setPrefix('/message');
$message->addGet('', ['action' => 'getIndex']);
$message->addGet('/ajax/checkreceiver', ['action' => 'jsonCheckReceiver']);
$message->addGet('/ajax/writemessage', ['action' => 'jsonWriteMessage']);
$message->addGet('/ajax/readmessage', ['action' => 'jsonReadMessage']);
$message->addGet('/ajax/sendanswer', ['action' => 'jsonSendAnswer']);
$message->addGet('/folders', ['action' => 'getFolders']);
$message->addPost('/folders', ['action' => 'postFolders']);
$message->addGet('/settings', ['action' => 'getSettings']);
$message->addPost('/settings', ['action' => 'postSettings']);
$message->addGet('/block', ['action' => 'getBlockedPlayers']);
$message->addPost('/block', ['action' => 'postBlockedPlayers']);
$message->addGet('/read', ['action' => 'getRead']);
$message->addPost('/read', ['action' => 'postRead']);
$router->mount($message);

$city = new \Phalcon\Mvc\Router\Group(['controller' => 'City']);
$city->setPrefix('/city');
$city->addGet('/index', ['action' => 'getIndex']);
$city->addGet('/library', ['action' => 'getLibrary']);
$city->addPost('/library', ['action' => 'postLibrary']);
$city->addGet('/church', ['action' => 'getChurch']);
$city->addPost('/church', ['action' => 'postChurch']);
$city->addGet('/graveyard', ['action' => 'getGraveyard']);
$city->addPost('/graveyard', ['action' => 'postGraveyard']);
$city->addPost('/graveyard/cancel', ['action' => 'postGraveyardCancel']);
$city->addGet('/shop', ['action' => 'getShop']);
$city->addPost('/shop/item/buy/{id:[0-9]+}', ['action' => 'postShopItemBuy']);
$city->addPost('/shop/item/sell/{id:[0-9]+}', ['action' => 'postShopItemSell']);
$router->mount($city);

$hunt = new \Phalcon\Mvc\Router\Group(['controller' => 'Hunt']);
$hunt->setPrefix('/hunt');
$hunt->addGet('/index', ['action' => 'getHunt']);
$hunt->addGet('/human/{id:[1-5]}', ['action' => 'getHumanHunt']);
$hunt->addGet('/attack/{id:[0-9]+}', ['action' => 'postRaceAttack']);
$router->mount($hunt);

$clan = new \Phalcon\Mvc\Router\Group(['controller' => 'Clan']);
$clan->setPrefix('/clan');
$clan->addGet('/{id:[0-9]+}', ['action' => 'getClanPreview', 'controller' => 'Base']);
$clan->addGet('/index', ['action' => 'getIndex']);
$clan->addGet('/create', ['action' => 'getCreate']);
$clan->addPost('/create', ['action' => 'postCreate']);
$clan->addGet('/leave', ['action' => 'getLeave']);
$clan->addGet('/clanleave', ['action' => 'postLeave']);
$clan->addGet('/hideout/upgrade', ['action' => 'postHideoutUpgrade']);
$clan->addPost('/donate', ['action' => 'postDonate']);
$clan->addPost('/newmessage', ['action' => 'postNewMessage']);
$clan->addGet('/deletemessage', ['action' => 'postDeleteMessage']);
$clan->addGet('/logo/background', ['action' => 'getLogoBackground']);
$clan->addGet('/logo/symbol', ['action' => 'getLogoSymbol']);
$clan->addPost('/logo/background', ['action' => 'postLogoBackground']);
$clan->addPost('/logo/symbol', ['action' => 'postLogoSymbol']);
$clan->addGet('/description', ['action' => 'getDescription']);
$clan->addPost('/description', ['action' => 'postDescription']);
$clan->addGet('/change/name', ['action' => 'getRename']);
$clan->addGet('/change/homepage', ['action' => 'getChangeHomePage']);
$clan->addPost('/change/name', ['action' => 'postRename']);
$clan->addPost('/change/homepage', ['action' => 'postChangeHomePage']);
$clan->addGet('/memberrights', ['action' => 'getMemberRights']);
$clan->addGet('/memberrights/setowner/{id:[0-9]+}', ['action' => 'getSetOwner']);
$clan->addGet('/memberrights/kickuser/{id:[0-9]+}', ['action' => 'getKickUser']);
$clan->addGet('/memberrights/kick/{id:[0-9]+}', ['action' => 'postKickUser']);
$clan->addPost('/memberrights/addrank', ['action' => 'postAddRank']);
$clan->addPost('/memberrights/editranks', ['action' => 'postEditRankOptions']);
$clan->addPost('/memberrights/editrights', ['action' => 'postEditRights']);
$clan->addGet('/memberrights/deleterank/{id:[0-9]+}', ['action' => 'postDeleteRank']);
$clan->addGet('/view/homepage/{id:[0-9]+}', ['action' => 'postClanVisitHomepage', 'controller' => 'Base']);
$clan->addGet('/apply/{id:[0-9]+}', ['action' => 'getApply']);
$clan->addPost('/apply/{id:[0-9]+}', ['action' => 'postApply']);
$clan->addGet('/applications', ['action' => 'getApplications']);
$clan->addPost('/applications/{id:[0-9]+}', ['action' => 'postApplications']);
$clan->addGet('/memberlistExt/{id:[0-9]+}', ['action' => 'getClanMemberListExt', 'controller' => 'Base']);
$clan->addGet('/memberlist', ['action' => 'getMemberList']);
$clan->addGet('/donationlist', ['action' => 'getDonationList']);
$clan->addGet('/mail', ['action' => 'getClanMail']);
$clan->addPost('/mail', ['action' => 'postClanMail']);
$router->mount($clan);

$router->addGet('/search', ['controller' => 'Game', 'action' => 'getSearch']);
$router->addPost('/search', ['controller' => 'Game', 'action' => 'postSearch']);
$router->addGet('/notepad', ['controller' => 'User', 'action' => 'getNotepad']);
$router->addPost('/notepad', ['controller' => 'User', 'action' => 'postNotepad']);
$router->addGet('/logout', ['controller' => 'User', 'action' => 'getLogout']);

return $router;