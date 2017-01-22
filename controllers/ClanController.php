<?php

/**
 * Created by PhpStorm.
 * User: osein
 * Date: 22/01/17
 * Time: 01:16
 */

class ClanController extends GameController
{

    public function getIndex() {
        $this->view->menu_active = 'clan';
        $this->view->pick('clan/index');
    }

    public function getCreate() {
        $this->view->menu_active = 'clan';
        $this->view->pick('clan/create');
    }

}