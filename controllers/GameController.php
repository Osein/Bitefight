<?php

/**
 * Created by PhpStorm.
 * User: osein
 * Date: 08/01/17
 * Time: 17:23
 */

namespace Bitefight\Controllers;

use ORM;

class GameController extends BaseController
{
    public function getSearch()
    {
        $this->view->menu_active = 'search';
        $this->view->pick('user/search');
    }

    public function postSearch()
    {
        $searchType = $this->request->get('searchtyp');
        $search = $this->request->get('text');
        $exact = $this->request->get('exakt');
        $this->view->searchString = $search;
        $this->view->searchType = $searchType;
        $this->view->exact = $exact;

        if ($searchType == 'name') {
            $this->view->search_type = 'player';
            $result = ORM::for_table('user')
                ->select_many('id', 'name', 'race', 's_booty');

            if ($exact) {
                $result = $result->where('name', $search);
            } else {
                $result = $result->where_like('name', '%'.$search.'%');
            }

            $this->view->results = $result
                ->limit(25)
                ->find_many();
        } else {
            $this->view->search_type = 'clan';
            $result = ORM::for_table('clan')
                ->select_many('id', 'name', 'tag', 'stufe')
                ->selectExpr('(SELECT SUM(s_booty) FROM user WHERE clan_id = clan.id)', 'booty')
                ->selectExpr('(SELECT COUNT(1) FROM user WHERE clan_id = clan.id)', 'members')
                ->selectExpr('(SELECT race FROM user WHERE clan_id = clan.id LIMIT 1)', 'race');

            if ($searchType == 'clan') {
                if ($exact) {
                    $result = $result->where('name', $search);
                } else {
                    $result = $result->where_like('name', '%'.$search.'%');
                }
            } else {
                if ($exact) {
                    $result = $result->where('tag', $search);
                } else {
                    $result = $result->where_like('tag', '%'.$search.'%');
                }
            }

            $this->view->results = $result
                ->limit(25)
                ->find_many();
        }

        $this->view->menu_active = 'search';
        $this->view->pick('user/search');
    }
}
