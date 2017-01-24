<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 24/01/17
 * Time: 21:12
 */

class CityController extends GameController
{

    public function initialize() {
        $this->view->menu_active = 'city';
        return parent::initialize();
    }

    public function getIndex() {
        $this->view->pick('city/index');
    }

}