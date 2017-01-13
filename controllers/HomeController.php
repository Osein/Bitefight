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
        $this->view->pick('home/index');
    }

    public function getNews() {
        $news = ORM::for_table('news')
            ->find_many();

        $this->view->news = $news;
        $this->view->pick('home/news');
    }

    public function getRegister($id = 0) {
        $this->view->id = $id;
        $this->view->pick('home/register');
    }

    public function postRegister($id) {

    }

    public function postAjaxCheck() {
        if(!$this->request->isAjax()) {
            $this->dispatcher->forward(array(
                'controller' => 'error',
                'action'     => 'show404',
            ));
        }

        $name = $this->request->get('name');
        $email = $this->request->get('email');
        $this->view->disable();
        $response = new \Phalcon\Http\Response();

        if(strlen($name) > 0) {
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

    }

}