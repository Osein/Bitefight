<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 08/01/17
 * Time: 17:22
 */

class HomeController extends BaseController
{

    public function getIndex() {
        $this->view->menu_active = 'home';
        $this->view->pick('home/index');
    }

    public function getNews() {
        $this->view->menu_active = 'news';
        $news = ORM::for_table('news')
            ->find_many();

        $this->view->news = $news;
        $this->view->pick('home/news');
    }

    public function getRegister($id = 0) {
        $this->view->id = $id;
        $this->view->menu_active = 'register';

        $this->view->name = $this->session->get('register_name');
        $this->view->email = $this->session->get('register_email');
        $this->view->pass = $this->session->get('register_pass');
        $this->view->agb = $this->session->get('register_agb');

        $this->session->remove('register_name');
        $this->session->remove('register_email');
        $this->session->remove('register_pass');
        $this->session->remove('register_agb');

        $this->view->pick('home/register');
    }

    public function postRegister($id = 0) {
        $name = $this->request->get('name');
        $email = $this->request->get('email');
        $pass = $this->request->get('pass');
        $agb = $this->request->get('agb');
        $error = false;

        if($id == 0) {
            return $this->dispatcher->forward(array(
                'controller' => 'error',
                'action'     => 'show404',
            ));
        }

        if(strlen($name) < 3) {
            $this->flashSession->error('Please enter at least 3 characters for your name.');
            $error = true;
        } else {
            $user = ORM::for_table('user')
                ->where('name', $name)
                ->count();

            if($user) {
                $this->flashSession->error('Chosen username is already in use.');
                $error = true;
            }
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flashSession->error('Please enter an invalid email.');
            $error = true;
        } else {
            $user = ORM::for_table('user')
                ->where('mail', $email)
                ->count();

            if($user) {
                $this->flashSession->error('Chosen email is already in use.');
                $error = true;
            }
        }

        if(!$agb) {
            $this->flashSession->error('Please accept the terms and conditions.');
            $error = true;
        }

        if(strlen($pass) < 4) {
            $this->flashSession->error('Please enter a valid password.');
            $error = true;
        }

        if(!$error) {
            $user = ORM::for_table('user')->create();
            $user->name = $name;
            $user->mail = $email;
            $user->race = $id;
            $user->pass = $pass;
            $success = $user->save();

            if($success) {
                return $this->response->redirect(getUrl('user/profile'));
            } else {
                $this->flashSession->error('System error, please try again later.');

                return $this->response->redirect(getUrl('register/'.$id));
            }
        } else {
            $this->session->set('register_name', $name);
            $this->session->set('register_email', $email);
            $this->session->set('register_pass', $pass);
            $this->session->set('register_agb', $agb);

            return $this->response->redirect(getUrl('register/'.$id));
        }
    }

    public function postAjaxCheck() {
        if(!$this->request->isAjax()) {
            return $this->dispatcher->forward(array(
                'controller' => 'error',
                'action'     => 'show404',
            ));
        }

        $name = $this->request->get('name');
        $email = $this->request->get('email');
        $this->view->disable();
        $response = new \Phalcon\Http\Response();

        if(strlen($name) > 0) {
            if(strlen($name) < 3) {
                $response->setJsonContent(array(
                    'status' => false,
                    'messages' => array('name too short')
                ));
            } else {
                $user = ORM::for_table('user')
                    ->where('name', $name)
                    ->count();

                if($user) {
                    $response->setJsonContent(array(
                        'status' => false,
                        'messages' => array('name in use')
                    ));
                } else {
                    $response->setJsonContent(array(
                        'status' => true,
                        'messages' => null
                    ));
                }
            }

        } elseif(strlen($email) > 0) {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response->setJsonContent(array(
                    'status' => false,
                    'messages' => array('invalid mail')
                ));
            } else {
                $user = ORM::for_table('user')
                    ->where('mail', $email)
                    ->count();

                if($user) {
                    $response->setJsonContent(array(
                        'status' => false,
                        'messages' => array('mail in use')
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

    public function getLogin() {
        $this->view->error = $this->session->get('login_error');
        $this->session->remove('login_error');

        $this->view->menu_active = 'login';
        $this->view->pick('home/login');
    }

    public function postLogin() {
        $name = $this->request->get('user');
        $pass = $this->request->get('pass');

        $user = ORM::for_table('user')
            ->where('name', $name)
            ->where('pass', $pass)
            ->find_one();

        if($user) {
            $this->session->set('user_id', $user->id);
            return $this->response->redirect(getUrl('user/profile'));
        } else {
            $this->session->set('login_error', true);
            return $this->response->redirect(getUrl('login'));
        }
    }

}