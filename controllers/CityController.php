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

    public function getLibrary() {
        $this->view->pick('city/library');
    }

    public function postLibrary() {
        $method = $this->request->get('method');
        $name = $this->request->get('name');

        if(strlen($name) < 3) {
            $this->flashSession->warning('Username must contain at least 3 letters.');
            return $this->response->redirect(getUrl('city/library'));
        }

        $userCheck = ORM::for_table('user')
            ->where('name', $name)
            ->find_one();

        if($userCheck) {
            $this->flashSession->warning('Username is already in use.');
            return $this->response->redirect(getUrl('city/library'));
        }

        if($method == 1) {
            $gcost = getNameChangeCost($this->user->name_change, $this->user->exp);

            if($this->user->gold < $gcost) {
                $this->flashSession->warning('Username is already in use.');
            } else {
                $this->user->name = $name;
                $this->user->gold -= $gcost;
                $this->user->s_booty = $this->user->s_booty * 9 / 10;
                $this->user->name_change++;
                $this->flashSession->warning('Name change success.');
            }

            return $this->response->redirect(getUrl('city/library'));
        } else {
            if($this->user->hellstone < 10) {
                $this->flashSession->warning('Not enough hellstone.');
            } else {
                $this->user->name = $name;
                $this->user->hellstone -= 10;
                $this->flashSession->warning('Name change success.');
            }

            return $this->response->redirect(getUrl('city/library'));
        }
    }

}