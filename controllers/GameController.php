<?php

/**
 * Created by PhpStorm.
 * User: osein
 * Date: 08/01/17
 * Time: 17:23
 */

class GameController extends BaseController
{

    protected $user;

    public function initialize()
    {
        global $config;

        if(!$this->session->get('user_id')) {
            return $this->response->redirect(getUrl(''));
        }

        $this->user = ORM::for_table('user')->find_one($this->session->get('user_id'));
        $this->view->user = $this->user;

        $lastUpdate = $this->user->last_update;
        $timeNow = time();
        $timeDiff = $timeNow - $lastUpdate;

        if($timeDiff > 0) {
            if($this->user->ap_now < $this->user->ap_max) {
                $apPerSecond = $config->apPerHour / 3600;
                $apDelta = $apPerSecond * $timeDiff;
                $this->user->ap_now = min($apDelta + $this->user->ap_now, $this->user->ap_max);
            }

            if($this->user->hp_now < $this->user->hp_max) {
                $hpPerSecond = ($config->basicRegen + $config->endRegenRatio * $this->user->end) / 3600;
                $hpDelta = $hpPerSecond * $timeDiff;
                $this->user->hp_now = min($hpDelta + $this->user->hp_now, $this->user->hp_max);
            }

            $this->user->last_update = $timeNow;
        }
    }

    public function afterExecuteRoute()
    {
        // Idiorm will look for dirty fields.
        // If there is none, it will not send query.
        // So I think this is a good practice for me.
        $this->user->save();
    }

    public function getSearch() {
        $this->view->menu_active = 'search';
        $this->view->pick('user/search');
    }

    public function postSearch() {
        $searchType = $this->request->get('searchtyp');
        $search = $this->request->get('text');
        $exact = $this->request->get('exakt');
        $this->view->searchString = $search;
        $this->view->searchType = $searchType;
        $this->view->exact = $exact;

        if($searchType == 'name') {
            $this->view->search_type = 'player';
            $result = ORM::for_table('user')
                ->select_many('id', 'name', 'race', 's_booty');

            if($exact) {
                $result = $result->where('name', $search);
            } else {
                $result = $result->where_like('name', '%'.$search.'%');
            }

            $this->view->results = $result
                ->limit(25)
                ->find_many();
        }

        $this->view->menu_active = 'search';
        $this->view->pick('user/search');
    }

}