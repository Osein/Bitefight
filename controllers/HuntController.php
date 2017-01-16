<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 16/01/17
 * Time: 15:49
 */

class HuntController extends GameController
{

    public function getHunt() {
        $this->view->menu_active = 'hunt';
        $this->view->pick('hunt/index');
    }

}