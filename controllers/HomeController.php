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

    public function getLogin() {

    }

}