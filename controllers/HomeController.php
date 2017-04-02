<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 08/01/17
 * Time: 17:22
 */

namespace Bitefight\Controllers;

use Bitefight\Library\Translate;
use ORM;
use Phalcon\Http\Response;

class HomeController extends BaseController
{
    public function getIndex()
    {
        $this->view->menu_active = 'home';
        $this->view->pick('home/index');
    }

    public function getRegister($id = 0)
    {
        $this->view->id = $id;
        $this->view->menu_active = 'register';

        $this->view->name = $this->getFlashData('register_name');
        $this->view->email = $this->getFlashData('register_email');
        $this->view->pass = $this->getFlashData('register_pass');
        $this->view->agb = $this->getFlashData('register_agb');

        $this->view->pick('home/register');
    }

    public function postRegister($id = 0)
    {
        $name = $this->request->get('name');
        $email = $this->request->get('email');
        $pass = $this->request->get('pass');
        $agb = $this->request->get('agb');
        $error = false;

        if ($id == 0) {
            return $this->dispatcher->forward(array(
                'controller' => 'error',
                'action'     => 'show404',
            ));
        }

        if (strlen($name) < 3) {
            $this->flashSession->error(Translate::_('validation_username_character_error'));
            $error = true;
        } else {
            $user = ORM::for_table('user')
                ->where('name', $name)
                ->count();

            if ($user) {
                $this->flashSession->error(Translate::_('validation_username_used'));
                $error = true;
            }
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flashSession->error(Translate::_('validation_email_invalid'));
            $error = true;
        } else {
            $user = ORM::for_table('user')
                ->where('mail', $email)
                ->count();

            if ($user) {
                $this->flashSession->error(Translate::_('validation_email_used'));
                $error = true;
            }
        }

        if (!$agb) {
            $this->flashSession->error(Translate::_('validation_terms_required'));
            $error = true;
        }

        if (strlen($pass) < 4) {
            $this->flashSession->error(Translate::_('validation_password_character_error'));
            $error = true;
        }

        if (!$error) {
            $user = ORM::for_table('user')->create();
            $user->name = $name;
            $user->mail = $email;
            $user->race = $id;
            $user->pass = $pass;
            $success = $user->save();

            if ($success) {
                return $this->response->redirect(getUrl('user/profile'));
            } else {
                $this->flashSession->error(Translate::_('system_error'));
                return $this->response->redirect(getUrl('register/'.$id));
            }
        } else {
            $this->setFlashData('register_name', $name);
            $this->setFlashData('register_name', $name);
            $this->setFlashData('register_email', $email);
            $this->setFlashData('register_pass', $pass);
            $this->setFlashData('register_agb', $agb);

            return $this->response->redirect(getUrl('register/'.$id));
        }
    }

    public function postAjaxCheck()
    {
        if (!$this->request->isAjax()) {
            return $this->dispatcher->forward(array(
                'controller' => 'error',
                'action'     => 'show404',
            ));
        }

        $name = $this->request->get('name');
        $email = $this->request->get('email');
        $this->view->disable();
        $response = new Response();

        if (strlen($name) > 0) {
            if (strlen($name) < 3) {
                $response->setJsonContent(array(
                    'status' => false,
                    'messages' => array(Translate::_('validation_ajax_username_short'))
                ));
            } else {
                $user = ORM::for_table('user')
                    ->where('name', $name)
                    ->count();

                if ($user) {
                    $response->setJsonContent(array(
                        'status' => false,
                        'messages' => array(Translate::_('validation_ajax_username_used'))
                    ));
                } else {
                    $response->setJsonContent(array(
                        'status' => true,
                        'messages' => null
                    ));
                }
            }
        } elseif (strlen($email) > 0) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response->setJsonContent(array(
                    'status' => false,
                    'messages' => array(Translate::_('validation_ajax_email_invalid'))
                ));
            } else {
                $user = ORM::for_table('user')
                    ->where('mail', $email)
                    ->count();

                if ($user) {
                    $response->setJsonContent(array(
                        'status' => false,
                        'messages' => array(Translate::_('validation_ajax_email_used'))
                    ));
                } else {
                    $response->setJsonContent(array(
                        'status' => true,
                        'messages' => null
                    ));
                }
            }
        }

        return $response;
    }

    public function getLogin()
    {
        $this->view->menu_active = 'login';
        $this->view->pick('home/login');
    }

    public function postLogin()
    {
        $name = $this->request->get('user');
        $pass = $this->request->get('pass');

        $user = ORM::for_table('user')
            ->where('name', $name)
            ->where('pass', $pass)
            ->find_one();

        if ($user) {
            $this->session->set('user_id', $user->id);
            return $this->response->redirect(getUrl('user/profile'));
        } else {
            $this->flashSession->error(Translate::_('validation_login_invalid_credentials'));
            return $this->response->redirect(getUrl('login'));
        }
    }

    public function getLostPassword()
    {
        $this->view->menu_active = 'login';
        return $this->view->pick('home/lostpw');
    }

    public function postLostPassword()
    {
        $name = $this->request->get('name');
        $email = $this->request->get('email');

        $user = ORM::for_table('user')
            ->where('name', $name)
            ->where('mail', $email)
            ->find_one();

        if ($user) {
            $this->sendEmail($email, Translate::_('fp_email_subject'), 'lostpw', ['password' => $user->pass]);
            $this->flashSession->error(Translate::_('fp_email_sent'));
        } else {
            $this->flashSession->error(Translate::_('fp_no_user'));
        }

        return $this->response->redirect(getUrl('user/lostpw'));
    }
}
