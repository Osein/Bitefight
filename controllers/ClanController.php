<?php

/**
 * Created by PhpStorm.
 * User: osein
 * Date: 22/01/17
 * Time: 01:16
 */

namespace Bitefight\Controllers;

use ORM;
use Phalcon\Filter;

class ClanController extends GameController
{

    public function initialize()
    {
        $this->view->menu_active = 'clan';
        return parent::initialize();
    }

    public function getUserRankOptions()
    {
        $rank = ORM::for_table('clan_rank')
            ->where('id', $this->user->clan_rank);

        if ($this->user->clan_rank > 3) {
            $rank = $rank->where('clan_id', $this->user->clan_id);
        }

        return $rank->find_one();
    }

    public function getIndex()
    {
        if ($this->user->clan_id > 0) {
            $this->view->clan = ORM::for_table('clan')
                ->left_outer_join('clan_description', ['clan.id', '=', 'clan_description.clan_id'])
                ->find_one($this->user->clan_id);

            $this->view->rank = $this->getUserRankOptions();

            $this->view->totalBlood = ORM::for_table('user')
                ->where('clan_id', $this->user->clan_id)
                ->sum('s_booty');

            $this->view->member_count = ORM::for_table('user')
                ->where('clan_id', $this->user->clan_id)
                ->count();

            if ($this->view->rank->read_message) {
                $this->view->clan_messages = ORM::for_table('clan_message')
                    ->select_many('user.name', 'clan_message.*', 'clan_rank.rank_name')
                    ->where('clan_message.clan_id', $this->user->clan_id)
                    ->left_outer_join('user', ['user.id', '=', 'clan_message.user_id'])
                    ->left_outer_join('clan_rank', ['user.clan_rank', '=', 'clan_rank.id'])
                    ->find_many();
            }
        }

        $this->view->pick('clan/index');
    }

    public function postHideoutUpgrade()
    {
        $token = $this->request->get('_token');
        $tokenKey = $this->request->get('_tkey');

        if (!$this->security->checkToken($tokenKey, $token)) {
            return $this->response->redirect(getUrl('clan/index'));
        }

        $clan = ORM::for_table('clan')
            ->find_one($this->user->clan_id);

        $rank = $this->getUserRankOptions();

        if (!$rank->spend_gold) {
            return $this->notFound();
        }

        $hideoutCost = getClanHideoutCost($clan->stufe + 1);
        if ($clan->capital < $hideoutCost) {
            return $this->notFound();
        }

        $clan->capital -= $hideoutCost;
        $clan->stufe++;
        $clan->save();

        return $this->response->redirect(getUrl('clan/index'));
    }

    public function postDonate()
    {
        $donate_amount = $this->request->getPost('donation', Filter::FILTER_INT, 0);

        if ($donate_amount == 0 || $this->user->gold < $donate_amount) {
            return $this->notFound();
        }

        $clan = ORM::for_table('clan')
            ->find_one($this->user->clan_id);

        $clan->capital += $donate_amount;
        $this->user->gold -= $donate_amount;

        $donate = ORM::for_table('clan_donate')->create();
        $donate->clan_id = $clan->id;
        $donate->user_id = $this->user->id;
        $donate->donate = $donate_amount;
        $donate->donate_date = time();
        $donate->save();
        $clan->save();

        return $this->response->redirect(getUrl('clan/index'));
    }

    public function postNewMessage()
    {
        $messageText = $this->request->getPost('message');

        $userRankOptions = $this->getUserRankOptions();

        if (!$userRankOptions->write_message || strlen($messageText) > 2000) {
            return $this->notFound();
        }

        $message = ORM::for_table('clan_message')->create();
        $message->clan_id = $this->user->clan_id;
        $message->user_id = $this->user->id;
        $message->clan_message = $messageText;
        $message->clan_message_date = time();
        $message->save();

        return $this->response->redirect(getUrl('clan/index'));
    }

    public function postDeleteMessage()
    {
        $token = $this->request->get('_token');
        $tokenKey = $this->request->get('_tkey');
        $rank = $this->getUserRankOptions();
        $message_id = $this->request->get('message_id', Filter::FILTER_INT, 0);

        if (!$this->security->checkToken($tokenKey, $token) || !$rank->delete_message) {
            return $this->response->redirect(getUrl('clan/index'));
        }

        ORM::raw_execute('DELETE FROM clan_message WHERE clan_id = ? AND id = ?', [$this->user->clan_id, $message_id]);

        return $this->response->redirect(getUrl('clan/index'));
    }

    public function getCreate()
    {
        $this->view->pick('clan/create');
    }

    public function postCreate()
    {
        $tag = $this->request->get('tag');
        $name = $this->request->get('name');

        $prevClan = ORM::for_table('clan')
            ->where_raw('name = ? OR tag = ?', [$name, $tag])
            ->find_one();

        if ($prevClan) {
            if ($prevClan->name == $name) {
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
        $clan->race = $this->user->race;
        $clan->save();

        $this->user->clan_id = $clan->id();
        $this->user->clan_rank = 1;

        return $this->response->redirect(getUrl('clan/index'));
    }

    public function getLeave()
    {
        $this->view->pick('clan/leave');
    }

    public function postLeave()
    {
        $token = $this->request->get('_token');
        $tokenKey = $this->request->get('_tkey');

        if (!$this->security->checkToken($tokenKey, $token)) {
            return $this->response->redirect(getUrl('clan/index'));
        }

        if ($this->user->clan_rank == 1) {
            ORM::raw_execute('DELETE FROM clan WHERE id = ?', [$this->user->clan_id]);
        }

        $this->user->clan_id = 0;
        $this->user->clan_rank = 0;

        return $this->response->redirect(getUrl('clan/index'));
    }

    public function getLogoBackground()
    {
        $this->getLogoPage('background');
    }

    public function getLogoSymbol()
    {
        $this->getLogoPage('symbol');
    }

    public function getLogoPage($type)
    {
        $this->view->type = $type;
        $this->view->clan = ORM::for_table('clan')
            ->find_one($this->user->clan_id);
        $this->view->pick('clan/logo');
    }

    public function postLogoBackground()
    {
        $bg = $this->request->getPost('bg', Filter::FILTER_INT, 1);
        ORM::raw_execute("UPDATE clan SET logo_bg = ? WHERE id = ?", [$bg, $this->user->clan_id]);
        return $this->response->redirect(getUrl('clan/logo/background'));
    }

    public function postLogoSymbol()
    {
        $symbol = $this->request->getPost('symbol', Filter::FILTER_INT, 1);
        ORM::raw_execute("UPDATE clan SET logo_sym = ? WHERE id = ?", [$symbol, $this->user->clan_id]);
        return $this->response->redirect(getUrl('clan/logo/symbol'));
    }

    public function getDescription()
    {
        $this->view->description = ORM::for_table('clan_description')
            ->where('clan_id', $this->user->clan_id)
            ->find_one();

        $this->view->pick('clan/description');
    }

    public function postDescription()
    {
        $save = $this->request->getPost('save');

        if ($save) {
            $description = ORM::for_table('clan_description')->where('clan_id', $this->user->clan_id)->find_one();
            $descText = $this->request->getPost('description');

            if (!$description) {
                $description = ORM::for_table('clan_description')->create();
                $description->clan_id = $this->user->clan_id;
            }

            $description->description = $descText;
            $description->descriptionHtml = parseBBCodes($descText);
            $description->save();
        } else {
            ORM::raw_execute('DELETE FROM clan_description WHERE clan_id = ?', [$this->user->clan_id]);
        }

        $this->response->redirect(getUrl('clan/description'));
    }
}
