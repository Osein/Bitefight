<?php

/**
 * Created by PhpStorm.
 * User: osein
 * Date: 22/01/17
 * Time: 01:16
 */

class ClanController extends GameController
{

    public function initialize()
    {
        $this->view->menu_active = 'clan';
        return parent::initialize();
    }

    public function getIndex() {
        $this->view->pick('clan/index');
    }

    public function getCreate() {
        $this->view->pick('clan/create');
    }

    public function postCreate() {
        $tag = $this->request->get('tag');
        $name = $this->request->get('name');

        $prevClan = ORM::for_table('clan')
            ->where_raw('name = ? OR tag = ?', [$name, $tag])
            ->find_one();

        if($prevClan) {
            if($prevClan->name == $name) {
                $this->flashSession->error('Sorry, this clan name is already in use');
            } else {
                $this->flashSession->error('Sorry, this clan tag is already in use');
            }

            return $this->response->redirect(getUrl('clan/create'));
        }

        $clan = ORM::for_table('clan')->create();
        $clan->name = $name;
        $clan->tag = $tag;
        $clan->found_date = time();
        $clan->save();

        $this->user->clan_id = $clan->id();
        $this->user->clan_rank = 1;

        return $this->response->redirect(getUrl('clan/index'));
    }

}