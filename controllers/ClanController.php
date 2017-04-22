<?php

/**
 * Created by PhpStorm.
 * User: osein
 * Date: 22/01/17
 * Time: 01:16
 */

namespace Bitefight\Controllers;

use Bitefight\Library\Translate;
use Bitefight\Models\ClanRank;
use ORM;
use PDO;
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

            $this->view->application_count = ORM::for_table('clan_application')
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

        if(strlen($name) < 2 || strlen($tag) < 2) {
            $this->flashSession->error(Translate::_('validation_clan_name_or_tag_short'));
            return $this->response->redirect(getUrl('clan/create'));
        }

        $prevClan = ORM::for_table('clan')
            ->where_raw('name = ? OR tag = ?', [$name, $tag])
            ->find_one();

        if ($prevClan) {
            if ($prevClan->name == $name) {
                $this->flashSession->error(Translate::_('validation_clan_name_used'));
            } else {
                $this->flashSession->error(Translate::_('validation_clan_tag_used'));
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

    public function getChangeHomePage()
    {
        $clanObj = ORM::for_table('clan')
            ->select('clan.*')->select('user.name', 'website_editor_name')
            ->left_outer_join('user', ['user.id', '=', 'clan.website_set_by'])
            ->where('id', $this->user->clan_id)
            ->find_one();
        $this->view->clan = $clanObj;
        $this->view->pick('clan/homepage');
    }

    public function postChangeHomePage()
    {
        $clanObj = ORM::for_table('clan')->find_one($this->user->clan_id);

        if(!empty($this->request->get('delete', Filter::FILTER_STRING, '')) && $clanObj) {
            $clanObj->website = '';
            $clanObj->website_set_by = $this->user->id;
            $clanObj->save();
        } else {
            $homepage = $this->request->get('homepage', Filter::FILTER_STRING, '');

            if($clanObj && filter_var($homepage, FILTER_VALIDATE_URL)) {
                $clanObj->website = $homepage;
                $clanObj->website_set_by = $this->user->id;
                $clanObj->save();
            }
        }

        return $this->response->redirect(getUrl('clan/change/homepage'));
    }

    public function getRename()
    {
        $clanObj = ORM::for_table('clan')->find_one($this->user->clan_id);

        if(!$clanObj) {
            return $this->notFound();
        }

        $this->view->clan = $clanObj;
        $this->view->pick('clan/change_name');
    }

    public function postRename()
    {
        $tag = $this->request->get('tag');
        $name = $this->request->get('name');

        if(strlen($name) < 2 || strlen($tag) < 2) {
            $this->flashSession->error(Translate::_('validation_clan_name_or_tag_short'));
            return $this->response->redirect(getUrl('clan/change/name'));
        }

        $prevClan = ORM::for_table('clan')
            ->where_raw('name = ? OR tag = ?', [$name, $tag])
            ->find_one();

        if ($prevClan) {
            if ($prevClan->name == $name) {
                $this->flashSession->error(Translate::_('validation_clan_name_used'));
            } else {
                $this->flashSession->error(Translate::_('validation_clan_tag_used'));
            }

            return $this->response->redirect(getUrl('clan/change/name'));
        }

        $clan = ORM::for_table('clan')->find_one($this->user->clan_id);
        $clan->name = $name;
        $clan->tag = $tag;
        $clan->save();

        return $this->response->redirect(getUrl('clan/change/name'));
    }

    public function getMemberRights()
    {
        $users = ORM::for_table('user')
            ->select('user.id')->select('user.name')
            ->select('clan_rank.id', 'rank_id')->select('clan_rank.rank_name')
            ->left_outer_join('clan_rank', 'clan_rank.id = user.clan_rank')
            ->where('user.clan_id', $this->user->clan_id)
            ->find_many();

        $ranks = ORM::for_table('clan_rank')
            ->where_raw('clan_id = ? OR clan_id = 0', [$this->user->clan_id])
            ->find_many();

        $user_rank = ORM::for_table('clan_rank')
            ->find_one($this->user->clan_rank);

        $this->view->user_rank = $user_rank;
        $this->view->ranks = $ranks;
        $this->view->users = $users;
        $this->view->pick('clan/memberrights');
    }

    public function getSetOwner($id)
    {
        $token = $this->request->get('_token');
        $tokenKey = $this->request->get('_tkey');

        if (!$this->security->checkToken($tokenKey, $token)) {
            return $this->notFound();
        }

        if($this->getFlashData('declare_new_master', false)) {
            $user = ORM::for_table('user')->find_one($id);

            if(!$user) {
                return $this->notFound();
            }

            $user->clan_rank = 1;
            $user->save();

            $this->user->clan_rank = 2;

            return $this->response->redirect(getUrl('clan/memberrights'));
        }

        $this->view->declare_master_form = $id;
        $this->setFlashData('declare_new_master', $id);

        self::getMemberRights();
    }

    public function getKickUser($id)
    {
        $token = $this->request->get('_token');
        $tokenKey = $this->request->get('_tkey');

        if (!$this->security->checkToken($tokenKey, $token)) {
            return $this->notFound();
        }

        $kick_user = ORM::for_table('user')->find_one($id);
        $this->view->kick_user = $kick_user;
        $this->view->pick('clan/kick_user');
    }

    public function postKickUser($id)
    {
        $token = $this->request->get('_token');
        $tokenKey = $this->request->get('_tkey');

        if (!$this->security->checkToken($tokenKey, $token)) {
            return $this->notFound();
        }

        $kick_user = ORM::for_table('user')->find_one($id);

        if($kick_user) {
            $kick_user->clan_id = 0;
            $kick_user->save();
        }

        return $this->response->redirect(getUrl('clan/memberrights'));
    }

    public function postAddRank()
    {
        $rank_name = $this->request->get('newRank', Filter::FILTER_STRING, '');

        if(empty($rank_name)) {
            return $this->notFound();
        }

        $rank = ORM::for_table('clan_rank')->create();
        $rank->clan_id = $this->user->clan_id;
        $rank->rank_name = $rank_name;
        $rank->save();

        return $this->response->redirect(getUrl('clan/memberrights'));
    }

    public function postEditRankOptions()
    {
        $ranks = $this->request->get('ranks', null, array());

        foreach($ranks as $rank_id => $properties)
        {
            $rank = ORM::for_table('clan_rank')->find_one($rank_id);

            if(!$rank) {
                return $this->notFound();
            }

            foreach($properties as $property => $tmp)
            {
                if(ClanRank::checkColumnExists($property))
                {
                    $rank->{$property} = 1;
                }
            }

            $rank->save();
        }

        return $this->response->redirect(getUrl('clan/memberrights'));
    }

    public function postEditRights()
    {
        $users = $this->request->get('users', null, array());

        if(empty($users)) {
            return $this->response->redirect(getUrl('clan/memberrights'));
        }

        foreach($users as $user_id => $rank)
        {
            ORM::raw_execute('UPDATE user SET clan_rank = ? WHERE id = ?', [intval($rank), $user_id]);
        }

        return $this->response->redirect(getUrl('clan/memberrights'));
    }

    public function postDeleteRank($id = 0)
    {
        if($id < 4) {
            return $this->response->redirect(getUrl('clan/memberrights'));
        }

        $users = ORM::for_table('user')
            ->where('clan_id', $this->user->clan_id)
            ->where('clan_rank', $id)
            ->count();

        if(!$users) {
            ORM::raw_execute('DELETE FROM clan_rank WHERE clan_id = ? AND id = ?', [$this->user->clan_id, $id]);
        }

        return $this->response->redirect(getUrl('clan/memberrights'));
    }

    public function getApply($id)
    {
        if($this->user->clan_id > 0) {
            return $this->response->redirect(getUrl(''));
        }

        $pdo = ORM::getDb();
        $stmt = $pdo->prepare('
            SELECT clan.name, clan.tag, clan_application.id AS application_id
            FROM clan
            LEFT JOIN clan_application ON clan.id = clan_application.clan_id AND clan_application.user_id = ?
            WHERE clan.id = ?');
        $stmt->execute([$this->user->id, $id]);
        $this->view->clan = $stmt->fetch(PDO::FETCH_OBJ);

        $this->view->pick('clan/apply');
    }

    public function postApply($id)
    {
        $appText = $this->request->get('applicationText', null, '');

        if(strlen($appText) > 2000) {
            return $this->notFound();
        }

        $clanExists = ORM::for_table('clan')->find_one($id);

        if(!$clanExists) {
            return $this->notFound();
        }

        $application = ORM::for_table('clan_application')->create();
        $application->clan_id = $id;
        $application->user_id = $this->user->id;
        $application->note = $appText;
        $application->save();

        $this->view->form_sent = true;
        $this->view->pick('clan/apply');
    }

    public function getApplications()
    {
        $this->view->applications = ORM::for_table('clan_application')
            ->left_outer_join('user', ['user.id', '=', 'clan_application.user_id'])
            ->select('clan_application.*')->select('user.name')
            ->where('clan_application.clan_id', $this->user->clan_id)
            ->find_many();

        if(count($this->view->applications) == 0) {
            return $this->response->redirect(getUrl('clan/index'));
        }

        $this->view->pick('clan/applications');
    }

    public function postApplications($id)
    {
        $application = ORM::for_table('clan_application')->where('clan_id', $this->user->clan_id)->find_one($id);

        if(!$application) {
            return $this->notFound();
        }

        $reject = $this->request->get('abl');
        $rejectText = $this->request->get('abltext');
        $accept = $this->request->get('ann');

        if($accept) {
            $user = ORM::for_table('user')->find_one($application->user_id);
            $user->clan_id = $this->user->clan_id;
            $user->clan_rank = 3;
            $user->save();
            $application->delete();
        } elseif($reject) {
            // send message to user
            $application->delete();
        }

        return $this->response->redirect(getUrl('clan/applications'));
    }

    public function getMemberList()
    {
        $this->view->clan = ORM::for_table('clan')->find_one($this->user->clan_id);

        $order = $this->request->get('order', null, 'exp');
        $type = $this->request->get('type', null, 'desc');
        $this->view->type = $type;
        $this->view->order = $order;

        if(!$this->view->clan) {
            return $this->notFound();
        }

        $users = ORM::for_table('user')
            ->select('user.*')->select('clan_rank.rank_name')
            ->left_outer_join('clan_rank', ['user.clan_rank', '=', 'clan_rank.id'])
            ->where('user.clan_id', $this->user->clan_id);

        if($order == 'name') {
            $order = 'user.name';
        } elseif($order == 'level') {
            $order = 'user.exp';
        } elseif($order == 'rank') {
            $order = 'clan_rank.rank_name';
        } elseif($order == 'res1') {
            $order = 'user.s_booty';
        } elseif($order == 'goldwon') {
            $order = 'user.s_gold_captured';
        } elseif($order == 'goldlost') {
            $order = 'user.s_gold_lost';
        } elseif($order == 'status') {
            $order = 'user.last_activity';
        }

        if($type == 'desc') {
            $users = $users->orderByDesc($order);
        } else {
            $users = $users->orderByAsc($order);
        }

        $this->view->members = $users
            ->find_many();

        $this->view->pick('clan/memberlist');
    }

    public function getDonationList()
    {
        if($this->request->get('action') == 'refresh') {
            $this->user->clan_dtime = time();
        }

        $order = $this->request->get('order', null, 'name');
        $type = $this->request->get('type', null, 'desc') == 'desc' ? 'desc' : 'asc';
        $this->view->type = $type;
        $this->view->order = $order;

        if($order == 'status') {
            $order = 'clan_rank.rank_name';
        } elseif($order == 'amount') {
            $order = 'total_donate';
        } elseif($order == 'time') {
            $order = 'user.last_activity';
        } else {
            $order = 'user.name';
        }

        $pdo = ORM::getDb();

        $stmt = $pdo->prepare('
            SELECT
              user.id,
              user.name,
              clan_rank.rank_name,
              SUM(clan_donate.donate) AS total_donate,
              user.last_activity,
              (SELECT COUNT(1) FROM clan_donate WHERE donate_date >= ?) AS donate_amount
            FROM user
            LEFT JOIN clan_rank ON clan_rank.id = user.clan_rank
            LEFT JOIN clan_donate ON user.id = clan_donate.user_id
            WHERE user.clan_id = ?
            GROUP BY user.id
            ORDER BY '.$order.' '.$type.'
        ');

        $stmt->execute(array(
            $this->user->clan_dtime,
            $this->user->clan_id
        ));

        $this->view->userList = $stmt->fetchAll(PDO::FETCH_OBJ);

        $order2 = $this->request->get('order2', null, 'time');
        $type2 = $this->request->get('type2', null, 'desc') == 'desc' ? 'desc' : 'asc';
        $this->view->type2 = $type2;
        $this->view->order2 = $order2;

        if($order2 == 'status') {
            $order2 = 'clan_rank.rank_name';
        } elseif($order2 == 'amount') {
            $order2 = 'clan_donate.donate';
        } elseif($order2 == 'time') {
            $order2 = 'clan_donate.donate_date';
        } else {
            $order2 = 'user.name';
        }

        $stmt = $pdo->prepare('
            SELECT clan_donate.*, user.id, user.name, clan_rank.rank_name
            FROM clan_donate
            LEFT JOIN user ON clan_donate.user_id = user.id
            LEFT JOIN clan_rank ON user.clan_rank = clan_rank.id
            WHERE clan_donate.clan_id = ?
            ORDER BY '.$order2.' '.$type2.'
        ');

        $stmt->execute(array(
            $this->user->clan_id
        ));

        $this->view->donateList = $stmt->fetchAll(PDO::FETCH_OBJ);

        $this->view->pick('clan/donationlist');
    }
}
