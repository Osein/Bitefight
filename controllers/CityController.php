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

    public function getChurch() {
        $activity = ORM::for_table('user_activity')
            ->where('activity_type', ACTIVITY_TYPE_CHURCH)
            ->where('user_id', $this->user->id)
            ->find_one();

        $this->view->delta = max($activity?$activity->end_time - time():0, 0);
        $this->view->usedTimes = ceil($this->view->delta / 3600);
        $this->view->requiredAp = 5 * pow(2, $this->view->usedTimes);
        $this->view->pick('city/church');
    }

    public function postChurch() {
        if($this->user->hp_now >= $this->user->hp_max) {
            return $this->response->redirect(getUrl('city/church'));
        }

        $activity = ORM::for_table('user_activity')
            ->where('activity_type', ACTIVITY_TYPE_CHURCH)
            ->where('user_id', $this->user->id)
            ->find_one();

        $delta = max($activity?$activity->end_time - time():0, 0);
        $usedTimes = ceil($delta / 3600);
        $requiredAp = 5 * pow(2, $usedTimes);

        if($this->user->ap_now < $requiredAp) {
            return $this->response->redirect(getUrl('city/church'));
        }

        if(!$activity) {
            $activity = ORM::for_table('user_activity')->create();
            $activity->user_id = $this->user->id;
            $activity->activity_type = ACTIVITY_TYPE_CHURCH;
            $activity->end_time = time() + 3600;
        } else {
            if($activity->end_time < time()) {
                $activity->end_time = time() + 3600;
            } else {
                $activity->end_time = $activity->end_time + 3600;
            }
        }

        $activity->save();

        $this->user->hp_now = $this->user->hp_max;
        $this->user->ap_now -= $requiredAp;

        $this->flashSession->warning('The reverend tends to your wounds and you immediately feel better.');
        return $this->response->redirect(getUrl('city/church'));
    }

}