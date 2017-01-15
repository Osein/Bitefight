<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 14/01/17
 * Time: 03:26
 */

class UserController extends GameController
{

    public function getProfile() {
        $this->view->pick('user/profile');
    }

    public function getNews() {
        $this->view->menu_active = 'news';
        $news = ORM::for_table('news')
            ->find_many();

        $this->view->news = $news;
        $this->view->pick('home/news');
    }

}