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

    public function getUserRankOptions() {
        $rank = ORM::for_table('clan_rank')
            ->where('id', $this->user->clan_rank);

        if($this->user->clan_rank > 3) {
            $rank = $rank->where('clan_id', $this->user->clan_id);
        }

        return $rank->find_one();
    }

    public function getIndex() {
        $this->view->rank = $this->getUserRankOptions();

        $this->view->clan_messages = ORM::for_table('clan_message')
            ->where('clan_id', $this->user->clan_id)
            ->find_many();

        if($this->user->clan_id > 0) {
            $this->view->clan = ORM::for_table('clan')
                ->find_one($this->user->clan_id);
        }

        $this->view->pick('clan/index');
    }

    public function postHideoutUpgrade() {
        $token = $this->request->get('_token');
        $tokenKey = $this->request->get('_tkey');

        if(!$this->security->checkToken($tokenKey, $token)) {
            return $this->response->redirect(getUrl('clan/index'));
        }

        $clan = ORM::for_table('clan')
            ->find_one($this->user->clan_id);

        $rank = $this->getUserRankOptions();

        if(!$rank->spend_gold) {
            return $this->notFound();
        }

        $hideoutCost = getClanHideoutCost($clan->stufe + 1);
        if($clan->capital < $hideoutCost) {
            return $this->notFound();
        }

        $clan->capital -= $hideoutCost;
        $clan->stufe++;
        $clan->save();

        return $this->response->redirect(getUrl('clan/index'));
    }

    public function postDonate() {
        $donate = $this->request->get('donation', \Phalcon\Filter::FILTER_INT, 0);
        var_dump(2); die;

        if($donate == 0 || $this->user->gold < donate) {
            var_dump(1); die;
            return $this->notFound();
        }

        $clan = ORM::for_table('clan')
            ->find_one($this->user->clan_id);

        $clan->capital += $donate;
        $this->user->gold -= $donate;

        $donate = ORM::for_table('clan_donate')->create();
        $donate->clan_id = $clan->id;
        $donate->user_id = $this->user->id;
        $donate->donate = $donate;
        $donate->donate_date = time();
        $donate->save();
        $clan->save();

        return $this->response->redirect(getUrl('clan/index'));
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

    public function getLeave() {
        $this->view->pick('clan/leave');
    }

    public function postLeave() {
        $token = $this->request->get('_token');
        $tokenKey = $this->request->get('_tkey');

        if(!$this->security->checkToken($tokenKey, $token)) {
            return $this->response->redirect(getUrl('clan/index'));
        }

        if($this->user->clan_rank == 1) {
            ORM::for_table('clan')
                ->where('id', $this->user->clan_id)
                ->delete();
        }

        $this->user->clan_id = 0;
        $this->user->clan_rank = 0;

        return $this->response->redirect(getUrl('clan/index'));
    }

}