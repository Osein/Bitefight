<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 08/01/17
 * Time: 17:22
 */

namespace Bitefight\Controllers;

use Bitefight\Config;
use ORM;
use Phalcon\Filter;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View;

class BaseController extends Controller
{
    /**
     * @var \Bitefight\Models\User $user
     */
    protected $user;

    /**
     * @var array
     */
    protected $flashData = array();

    /**
     * @var array
     */
    protected $oldFlashData;

    public function initialize()
    {
        if ($this->session->get('user_id', false)) {
            $this->user = ORM::for_table('user')->find_one($this->session->get('user_id'));
            $this->view->user = $this->user;

            $this->view->user_new_message_count = ORM::for_table('message')
                ->where('receiver_id', $this->user->id)
                ->where('read', false)
                ->count();

            $this->view->clan_application_count = ORM::for_table('clan_application')
                ->where('clan_id', $this->user->clan_id)
                ->count();

            $lastUpdate = $this->user->last_update;
            $timeNow = time();
            $timeDiff = $timeNow - $lastUpdate;

            if ($timeDiff > 0) {
                if ($this->user->ap_now < $this->user->ap_max) {
                    $apPerSecond = Config::AP_PER_HOUR / 3600;
                    $apDelta = $apPerSecond * $timeDiff;
                    $this->user->ap_now = min($apDelta + $this->user->ap_now, $this->user->ap_max);
                }

                if ($this->user->hp_now < $this->user->hp_max) {
                    $hpPerSecond = (Config::BASIC_REGEN + Config::END_REGEN_RATIO * $this->user->end) / 3600;
                    $hpDelta = $hpPerSecond * $timeDiff;
                    $this->user->hp_now = min($hpDelta + $this->user->hp_now, $this->user->hp_max);
                }

                $this->user->last_activity = $timeNow;
            }
        }

        $this->oldFlashData = $this->session->get('bf.flash', array());
        $this->session->remove('bf.flash');

        return true;
    }

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->request->isPost() && !$this->security->checkToken() && $dispatcher->getControllerName() != 'Error') {
            $dispatcher->forward(array(
                'controller' => 'Error',
                'action'     => 'show404',
            ));

            return false;
        }

        if($this->session->get('user_id', false)) {
            if($dispatcher->getControllerName() == 'Home') {
                $this->response->redirect(getUrl('user/profile'));
                return false;
            }
        } else {
            if(!in_array($dispatcher->getControllerName(), ['Home', 'Base'])) {
                $this->response->redirect(getUrl(''));
                return false;
            }
        }

        return true;
    }

    public function afterExecuteRoute()
    {
        // Idiorm will look for dirty fields.
        // If there is none, it will not send query.
        // So I think this is a good practice for me.
        if ($this->user) {
            $this->user->save();
        }

        $this->session->set('bf.flash', $this->flashData);
        $this->view->executeTime = microtime(true) - APP_START_TIME;
    }

    public function notFound()
    {
        return $this->dispatcher->forward(array(
            'controller' => 'error',
            'action'     => 'show404',
        ));
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getFlashData($key, $default = false)
    {
        if(key_exists($key, $this->oldFlashData)) {
            return $this->oldFlashData[$key];
        } else {
            return $default;
        }
    }

    /**
     * @param string $key
     * @param bool $default
     * @return mixed
     */
    public function getNewFlashData($key, $default = false)
    {
        if(key_exists($key, $this->flashData)) {
            return $this->flashData[$key];
        } else {
            return $default;
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setFlashData($key, $value)
    {
        $this->flashData[$key] = $value;
    }

    /**
     * Send an email with view file
     * @param $to
     * @param $subject
     * @param $view
     * @param array $data
     */
    public function sendEmail($to, $subject, $view, $data = array())
    {
        $headers = "From: " . Config::EMAIL_FROM . "\r\n";
        $headers .= "Reply-To: " . Config::EMAIL_REPLY_TO . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $message =  $this->view->getRender('emails', $view, $data, function ($view) {
            /** @var View $view */
            $view->setRenderLevel(View::LEVEL_ACTION_VIEW);
        });

        mail($to, $subject, $message, $headers);
    }

    public function getHighscore()
    {
        $this->view->menu_active = 'highscore';

        $this->view->race = $this->request->get('race', Filter::FILTER_INT, 0);
        $this->view->type = $this->request->get('type', Filter::FILTER_STRING, 'player');
        $this->view->page = $this->request->get('page', Filter::FILTER_INT, 1);
        $this->view->order = $this->request->get('order', Filter::FILTER_STRING, 'raid');

        if ($this->view->type == 'player') {
            $this->view->show = array_slice(
                $this->request->get('show', null, array('level', 'raid', 'fightvalue')),
                0,
                5
            );

            if (!in_array($this->view->order, $this->view->show)) {
                if (in_array('raid', $this->view->show)) {
                    $this->view->order = 'raid';
                } else {
                    $this->view->order = $this->view->show[0];
                }
            }

            $result = ORM::for_table('user')
                ->select_many('name', 'id', 'race');

            if ($this->view->race > 0 && $this->view->race < 3) {
                $result = $result->where('race', $this->view->race);
            }

            foreach ($this->view->show as $show) {
                if ($show == 'level') {
                    $result = $result->selectExpr('FLOOR(SQRT(exp / 5)) + 1', 'level');
                } elseif ($show == 'raid') {
                    $result = $result->select('s_booty', 'raid');
                } elseif ($show == 'fightvalue') {
                    $result = $result->select('battle_value', 'fightvalue');
                } elseif ($show == 'fights') {
                    $result = $result->select('s_fight', 'fights');
                } elseif ($show == 'fight1') {
                    $result = $result->select('s_victory', 'fight1');
                } elseif ($show == 'fight2') {
                    $result = $result->select('s_defeat', 'fight2');
                } elseif ($show == 'fight0') {
                    $result = $result->select('s_draw', 'fight0');
                } elseif ($show == 'goldwin') {
                    $result = $result->select('s_gold_captured', 'goldwin');
                } elseif ($show == 'goldlost') {
                    $result = $result->select('s_gold_lost', 'goldlost');
                } elseif ($show == 'hits1') {
                    $result = $result->select('s_damage_caused', 'hits1');
                } elseif ($show == 'hits2') {
                    $result = $result->select('s_hp_lost', 'hits2');
                } elseif ($show == 'trophypoints') {
                    // I dont know what is this lol
                } elseif ($show == 'henchmanlevels') {
                    // I dont know this too, but oh lol well find out later
                }
            }

            $resultCount = $result->count();
        } else {
            $this->view->show = array_slice(
                $this->request->get('show', null, array('castle', 'raid', 'warraid')),
                0,
                5
            );

            if (!in_array($this->view->order, $this->view->show)) {
                if (in_array('raid', $this->view->show)) {
                    $this->view->order = 'raid';
                } else {
                    $this->view->order = $this->view->show[0];
                }
            }

            $resultCount = ORM::for_table('clan');

            $result = ORM::for_table('clan')
                ->select_many('clan.id', 'clan.name', 'clan.tag', 'clan.race')
                ->left_outer_join('user', array('user.clan_id', '=', 'clan.id'))
                ->group_by('clan.id');

            if ($this->view->race > 0 && $this->view->race < 3) {
                $result = $result->where('race', $this->view->race);
                $resultCount->where('race', $this->view->race);
            }

            $resultCount = $resultCount->count();

            foreach ($this->view->show as $show) {
                if ($show == 'castle') {
                    $result = $result->select('clan.stufe', 'castle');
                } elseif ($show == 'raid') {
                    $result = $result->selectExpr('SUM(user.s_booty)', 'raid');
                } elseif ($show == 'warraid') {
                    $result = $result->selectExpr('SUM(user.battle_value)', 'warraid');
                } elseif ($show == 'fights') {
                    $result = $result->selectExpr('SUM(user.s_fight)', 'fights');
                } elseif ($show == 'fight1') {
                    $result = $result->selectExpr('SUM(user.s_victory)', 'fight1');
                } elseif ($show == 'fight2') {
                    $result = $result->selectExpr('SUM(user.s_defeat)', 'fight2');
                } elseif ($show == 'fight0') {
                    $result = $result->selectExpr('SUM(user.s_draw)', 'fight0');
                } elseif ($show == 'members') {
                    $result = $result->selectExpr('COUNT(1)', 'members');
                } elseif ($show == 'ppm') {
                    // Average booty
                } elseif ($show == 'seals') {
                    //$result = $result->select('s_damage_caused', 'hits1');
                } elseif ($show == 'gatesopen') {
                    //$result = $result->select('s_hp_lost', 'hits2');
                } elseif ($show == 'lastgateopen') {
                    // Last gate opening
                }
            }
        }

        $result = $result->orderByDesc($this->view->order);
        $result->orderByDesc(($this->view->type == 'player'?'user':'clan').'.id');

        $this->view->results = $result->limit(50)->offset(($this->view->page - 1) * 50)->find_many();
        $this->view->maxPage = ceil($resultCount / 50);
        $this->view->startRank = ($this->view->page - 1) * 50 + 1;

        $this->view->vampireCount = ORM::for_table('user')->where('race', 1)
            ->count();
        $this->view->werewolfCount = ORM::for_table('user')->where('race', 2)
            ->count();
        $this->view->vampireBlood = ORM::for_table('user')
            ->where('race', 1)->selectExpr('SUM(s_booty)', 'booty')->find_one()->booty;
        $this->view->werewolfBlood = ORM::for_table('user')
            ->where('race', 2)->selectExpr('SUM(s_booty)', 'booty')->find_one()->booty;
        $this->view->vampireBattle = ORM::for_table('user')
            ->where('race', 1)->selectExpr('SUM(s_fight)', 'booty')->find_one()->booty;
        $this->view->werewolfBattle = ORM::for_table('user')
            ->where('race', 2)->selectExpr('SUM(s_fight)', 'booty')->find_one()->booty;
        $this->view->vampireGold = ORM::for_table('user')
            ->where('race', 1)->selectExpr('SUM(gold)', 'booty')->find_one()->booty;
        $this->view->werewolfGold = ORM::for_table('user')
            ->where('race', 2)->selectExpr('SUM(gold)', 'booty')->find_one()->booty;

        $this->view->pick('user/highscore');
    }

    public function postHighscoreMyPosition()
    {
        $this->view->race = $this->request->get('race', Filter::FILTER_INT, 0);
        $this->view->type = $this->request->get('type', Filter::FILTER_STRING, 'player');
        $this->view->page = $this->request->get('page', Filter::FILTER_INT, 1);
        $this->view->order = $this->request->get('order', Filter::FILTER_STRING, 'raid');

        if ($this->view->type == 'player') {
            $this->view->show = array_slice(
                $this->request->get('show', null, array('level', 'raid', 'fightvalue')),
                0,
                5
            );

            if (!in_array($this->view->order, $this->view->show)) {
                if (in_array('raid', $this->view->show)) {
                    $this->view->order = 'raid';
                } else {
                    $this->view->order = $this->view->show[0];
                }
            }

            $result = ORM::for_table('user');

            if ($this->view->race > 0 && $this->view->race < 3) {
                $result = $result->where('race', $this->view->race);
            }

            if ($this->view->order == 'level') {
                $result = $result->where_gte('exp', $this->user->exp)->orderByDesc('exp');
            } elseif ($this->view->order == 'raid') {
                $result = $result->where_gte('s_booty', $this->user->s_booty)->orderByDesc('s_booty');
            } elseif ($this->view->order == 'fightvalue') {
                $result = $result->where_gte('battle_value', $this->user->battle_value)->orderByDesc('battle_value');
            } elseif ($this->view->order == 'fights') {
                $result = $result->where_gte('s_fight', $this->user->s_fight)->orderByDesc('s_fight');
            } elseif ($this->view->order == 'fight1') {
                $result = $result->where_gte('s_victory', $this->user->s_victory)->orderByDesc('s_victory');
            } elseif ($this->view->order == 'fight2') {
                $result = $result->where_gte('s_defeat', $this->user->s_defeat)->orderByDesc('s_defeat');
            } elseif ($this->view->order == 'fight0') {
                $result = $result->where_gte('s_draw', $this->user->s_draw)->orderByDesc('s_draw');
            } elseif ($this->view->order == 'goldwin') {
                $result = $result->where_gte('s_gold_captured', $this->user->s_gold_captured)->orderByDesc('s_gold_captured');
            } elseif ($this->view->order == 'goldlost') {
                $result = $result->where_gte('s_gold_lost', $this->user->s_gold_lost)->orderByDesc('s_gold_lost');
            } elseif ($this->view->order == 'hits1') {
                $result = $result->where_gte('s_damage_caused', $this->user->s_damage_caused)->orderByDesc('s_damage_caused');
            } elseif ($this->view->order == 'hits2') {
                $result = $result->where_gte('s_hp_lost', $this->user->s_hp_lost)->orderByDesc('s_hp_lost');
            } elseif ($this->view->order == 'trophypoints') {
                // I dont know what is this lol
            } elseif ($this->view->order == 'henchmanlevels') {
                // I dont know this too, but oh lol well find out later
            }

            $resultCount = $result->count();
        } else {
            $this->view->show = array_slice(
                $this->request->get('show', null, array('castle', 'raid', 'warraid')),
                0,
                5
            );

            if (!in_array($this->view->order, $this->view->show)) {
                if (in_array('raid', $this->view->show)) {
                    $this->view->order = 'raid';
                } else {
                    $this->view->order = $this->view->show[0];
                }
            }

            // <editor-fold desc="get user clan object">
            /**
             * @var ORM $userClanOrm
             */
            $userClanOrm = ORM::for_table('clan')
                ->select_many('clan.id', 'clan.name', 'clan.tag', 'clan.race', 'clan.stufe')
                ->left_outer_join('user', array('user.clan_id', '=', 'clan.id'))
                ->group_by('clan.id')
                ->where('clan.id', $this->user->clan_id);

            if ($this->view->race > 0 && $this->view->race < 3) {
                $userClanOrm = $userClanOrm->where('race', $this->view->race);
            }

            if ($this->view->order == 'castle') {
                $userClanOrm = $userClanOrm->select('clan.stufe', 'castle');
            } elseif ($this->view->order == 'raid') {
                $userClanOrm = $userClanOrm->selectExpr('SUM(user.s_booty)', 'raid');
            } elseif ($this->view->order == 'warraid') {
                $userClanOrm = $userClanOrm->selectExpr('SUM(user.battle_value)', 'warraid');
            } elseif ($this->view->order == 'fights') {
                $userClanOrm = $userClanOrm->selectExpr('SUM(user.s_fight)', 'fights');
            } elseif ($this->view->order == 'fight1') {
                $userClanOrm = $userClanOrm->selectExpr('SUM(user.s_victory)', 'fight1');
            } elseif ($this->view->order == 'fight2') {
                $userClanOrm = $userClanOrm->selectExpr('SUM(user.s_defeat)', 'fight2');
            } elseif ($this->view->order == 'fight0') {
                $userClanOrm = $userClanOrm->selectExpr('SUM(user.s_draw)', 'fight0');
            } elseif ($this->view->order == 'members') {
                $userClanOrm = $userClanOrm->selectExpr('COUNT(1)', 'members');
            } elseif ($this->view->order == 'ppm') {
                // Average booty
            } elseif ($this->view->order == 'seals') {
                //$userClanOrm = $userClanOrm->select('s_damage_caused', 'hits1');
            } elseif ($this->view->order == 'gatesopen') {
                //$userClanOrm = $userClanOrm->select('s_hp_lost', 'hits2');
            } elseif ($this->view->order == 'lastgateopen') {
                // Last gate opening
            }

            $userClanObj = $userClanOrm->find_one();
            // </editor-fold>

            $countQuery = 'SELECT SQL_CALC_FOUND_ROWS clan.stufe FROM clan
                          LEFT JOIN user ON user.clan_id = clan.id
                          GROUP BY clan.id
                          HAVING %havingcond%
                          LIMIT 1';

            if ($this->view->order == 'castle') {
                $countQuery = 'SELECT SQL_CALC_FOUND_ROWS 1 FROM clan WHERE stufe > '.$userClanObj->stufe.' OR (stufe = '.$userClanObj->stufe.' AND id >= '.$userClanObj->id.') LIMIT 1';
            } elseif ($this->view->order == 'raid') {
                $countQuery = str_replace('%havingcond%', 'SUM(user.s_booty) >= '.$userClanObj->raid, $countQuery);
            } elseif ($this->view->order == 'warraid') {
                $countQuery = str_replace('%havingcond%', 'SUM(user.battle_value) >= '.$userClanObj->warraid, $countQuery);
            } elseif ($this->view->order == 'fights') {
                $countQuery = str_replace('%havingcond%', 'SUM(user.s_fight) >= '.$userClanObj->fights, $countQuery);
            } elseif ($this->view->order == 'fight1') {
                $countQuery = str_replace('%havingcond%', 'SUM(user.s_victory) >= '.$userClanObj->fight1, $countQuery);
            } elseif ($this->view->order == 'fight2') {
                $countQuery = str_replace('%havingcond%', 'SUM(user.s_defeat) >= '.$userClanObj->fight2, $countQuery);
            } elseif ($this->view->order == 'fight0') {
                $countQuery = str_replace('%havingcond%', 'SUM(user.s_draw) >= '.$userClanObj->fight0, $countQuery);
            } elseif ($this->view->order == 'members') {
                $countQuery = str_replace('%havingcond%', 'COUNT(user.id) >= '.$userClanObj->members, $countQuery);
            } elseif ($this->view->order == 'ppm') {
                // Average booty
            } elseif ($this->view->order == 'seals') {
                //$result = $result->select('s_damage_caused', 'hits1');
            } elseif ($this->view->order == 'gatesopen') {
                //$result = $result->select('s_hp_lost', 'hits2');
            } elseif ($this->view->order == 'lastgateopen') {
                // Last gate opening
            }

            ORM::raw_execute($countQuery);

            $resultCount = ORM::for_table('clan')->raw_query('SELECT FOUND_ROWS() AS count')->find_one()->count;
        }
        $this->view->page = ceil($resultCount / 50);
        $linkShowPart = '';

        foreach ($this->view->show as $s2) {
            $linkShowPart .= '&show[]='.$s2;
        }

        return $this->response->redirect(getUrl('highscore').'?type='.$this->view->type.'&race='.$this->view->race.'&page='.$this->view->page.'&order='.$this->view->order.$linkShowPart);
    }

    public function getNews()
    {
        $this->view->menu_active = 'news';
        $news = ORM::for_table('news')
            ->find_many();

        $this->view->news = $news;
        $this->view->pick('home/news');
    }

    public function getPreview($id)
    {
        $user = ORM::for_table('user')
            ->select_many(
                'user.*',
                'user_description.descriptionHtml',
                'clan_rank.rank_name',
                'clan_rank.war_minister',
                'clan.logo_sym',
                'clan.logo_bg'
            )->select('clan.id', 'clan_id')
            ->select('clan.name', 'clan_name')
            ->select('clan.tag', 'clan_tag')
            ->left_outer_join('user_description', ['user.id', '=', 'user_description.user_id'])
            ->left_outer_join('clan', ['clan.id', '=', 'user.clan_id'])
            ->left_outer_join('clan_rank', ['user.clan_rank', '=', 'clan_rank.id'])
            ->find_one($id);

        if (!$user) {
            return $this->notFound();
        }

        $stat_max = max($user->str, $user->dex, $user->dex, $user->end, $user->cha);
        $userLevel = getLevel($user->exp);
        $previousLevelExp = getPreviousExpNeeded($userLevel);
        $nextLevelExp = getExpNeeded($userLevel);
        $levelExpDiff = $nextLevelExp - $previousLevelExp;

        $this->view->puser = $user;
        $this->view->exp_red_long = ($user->exp - $previousLevelExp) / $levelExpDiff * 400;
        $this->view->str_red_long = $user->str / $stat_max * 400;
        $this->view->def_red_long = $user->def / $stat_max * 400;
        $this->view->dex_red_long = $user->dex / $stat_max * 400;
        $this->view->end_red_long = $user->end / $stat_max * 400;
        $this->view->cha_red_long = $user->cha / $stat_max * 400;
        $this->view->menu_active = 'profile';
        return $this->view->pick('user/preview');
    }

    public function getClanPreview($id)
    {
        $this->view->menu_active = 'clan_preview';
        $this->view->clan = ORM::for_table('clan')
            ->leftOuterJoin('user', ['user.clan_id', '=', 'clan.id'])
            ->leftOuterJoin('clan_description', ['clan.id', '=', 'clan_description.clan_id'])
            ->select('clan.*')
            ->select('clan_description.descriptionHtml')
            ->selectExpr('SUM(user.s_booty)', 'total_booty')
            ->selectExpr('COUNT(1)', 'member_count')
            ->selectExpr('SUM(user.gold)', 'gold_count')
            ->selectExpr('IF(clan.stufe = 0, 1, clan.stufe * 3)', 'max_members')
            ->where('id', $id)
            ->find_one();
        $this->view->pick('clan/preview');
    }

    public function postClanVisitHomepage($id)
    {
        $clan = ORM::for_table('clan')
            ->find_one($id);

        if(!$clan || !strlen($clan->website)) {
            return $this->response->redirect(getUrl(''));
        }

        $token = $this->request->get('_token');
        $tokenKey = $this->request->get('_tkey');

        if (!$this->security->checkToken($tokenKey, $token, false)) {
            return $this->response->redirect(getUrl(''));
        }

        $clan->website_counter++;
        $clan->save();

        return $this->response->redirect($clan->website);
    }

    public function getClanMemberListExt($id)
    {
        $this->view->clan = ORM::for_table('clan')->find_one($id);

        if(!$this->view->clan) {
            return $this->notFound();
        }

        $this->view->memberList = ORM::for_table('user')
            ->select('user.*')->select('clan_rank.rank_name')
            ->left_outer_join('clan_rank', ['user.clan_rank', '=', 'clan_rank.id'])
            ->where('clan_id', $id)
            ->find_many();

        $this->view->menu_active = 'clan_memberlist_ext';
        $this->view->pick('clan/memberlist_ext');
    }
}
