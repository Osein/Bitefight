<?php

/**
 * Created by PhpStorm.
 * User: osein
 * Date: 08/01/17
 * Time: 17:23
 */

class GameController extends BaseController
{

    /** @noinspection PhpInconsistentReturnPointsInspection */
    public function initialize()
    {
        if(!$this->session->get('user_id')) {
            return $this->response->redirect(getUrl(''));
        }

        parent::initialize();
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