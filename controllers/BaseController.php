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
use Phalcon\Translate\Adapter\NativeArray;

class BaseController extends Controller
{
    /**
     * @var \Bitefight\Models\User $user
     */
    protected $user;

    /**
     * @return bool
     */
    public function initialize()
    {
        if ($this->session->get('user_id', false)) {
            $this->user = ORM::for_table('user')->find_one($this->session->get('user_id'));
            $this->view->user = $this->user;

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

                $this->user->last_update = $timeNow;
            }
        }

        $language = $this->request->getBestLanguage();
        $translationFile = APP_PATH . DIRECTORY_SEPARATOR . 'lang/' . $language . '.php';

        // Check if we have a translation file for that lang
        if (file_exists($translationFile)) {
            /** @noinspection PhpIncludeInspection */
            require $translationFile;
        } else {
            // Fallback to some default
            $messages = require APP_PATH . DIRECTORY_SEPARATOR . 'lang/en.php';
        }

        $this->view->t = new NativeArray(
            [
                "content" => $messages,
            ]
        );

        return true;
    }

    /**
     * Called before the execution of handler
     *
     * @param Dispatcher $dispatcher
     * @return bool
     */
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
            if($dispatcher->getControllerName() != 'Home') {
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
    }

    /**
     * Here to replace the ugly code
     * @return mixed
     */
    public function notFound()
    {
        return $this->dispatcher->forward(array(
            'controller' => 'error',
            'action'     => 'show404',
        ));
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
        $headers = "From: noreply@osein.net\r\n";
        $headers .= "Reply-To: noreply@osein.net\r\n";
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

            $result = ORM::for_table('clan')
                ->select_many('id', 'name', 'tag', 'race');

            if ($this->view->race > 0 && $this->view->race < 3) {
                $result = $result->where('race', $this->view->race);
            }

            foreach ($this->view->show as $show) {
                if ($show == 'castle') {
                    $result = $result->select('stufe', 'castle');
                } elseif ($show == 'raid') {
                    $result = $result->selectExpr(
                        '(SELECT SUM(s_booty) FROM user WHERE clan_id = clan.id)',
                        'raid'
                    );
                } elseif ($show == 'warraid') {
                    $result = $result->selectExpr(
                        '(SELECT SUM(battle_value) FROM user WHERE clan_id = clan.id)',
                        'warraid'
                    );
                } elseif ($show == 'fights') {
                    $result = $result->selectExpr(
                        '(SELECT SUM(s_fight) FROM user WHERE clan_id = clan.id)',
                        'fights'
                    );
                } elseif ($show == 'fight1') {
                    $result = $result->selectExpr(
                        '(SELECT SUM(s_victory) FROM user WHERE clan_id = clan.id)',
                        'fight1'
                    );
                } elseif ($show == 'fight2') {
                    $result = $result->selectExpr(
                        '(SELECT SUM(s_defeat) FROM user WHERE clan_id = clan.id)',
                        'fight2'
                    );
                } elseif ($show == 'fight0') {
                    $result = $result->selectExpr(
                        '(SELECT SUM(s_draw) FROM user WHERE clan_id = clan.id)',
                        'fight0'
                    );
                } elseif ($show == 'members') {
                    $result = $result->selectExpr(
                        '(SELECT COUNT(1) FROM user WHERE clan_id = clan.id)',
                        'members'
                    );
                } elseif ($show == 'ppm') {
                    //$result = $result->select('s_gold_lost', 'goldlost');
                } elseif ($show == 'seals') {
                    //$result = $result->select('s_damage_caused', 'hits1');
                } elseif ($show == 'gatesopen') {
                    //$result = $result->select('s_hp_lost', 'hits2');
                } elseif ($show == 'lastgateopen') {
                    //$result = $result->select('s_hp_lost', 'hits2');
                }
            }

            $resultCount = $result->count();
        }

        $result = $result->orderByDesc($this->view->order);
        $result->orderByAsc('id');

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

            $result = ORM::for_table('clan')
                ->select_many('id', 'name', 'tag', 'race');

            if ($this->view->race > 0 && $this->view->race < 3) {
                $result = $result->where('race', $this->view->race);
            }

            foreach ($this->view->show as $show) {
                if ($show == 'castle') {
                    $result = $result->select('stufe', 'castle');
                } elseif ($show == 'raid') {
                    $result = $result->selectExpr(
                        '(SELECT SUM(s_booty) FROM user WHERE clan_id = clan.id)',
                        'raid'
                    );
                } elseif ($show == 'warraid') {
                    $result = $result->selectExpr(
                        '(SELECT SUM(battle_value) FROM user WHERE clan_id = clan.id)',
                        'warraid'
                    );
                } elseif ($show == 'fights') {
                    $result = $result->selectExpr(
                        '(SELECT SUM(s_fight) FROM user WHERE clan_id = clan.id)',
                        'fights'
                    );
                } elseif ($show == 'fight1') {
                    $result = $result->selectExpr(
                        '(SELECT SUM(s_victory) FROM user WHERE clan_id = clan.id)',
                        'fight1'
                    );
                } elseif ($show == 'fight2') {
                    $result = $result->selectExpr(
                        '(SELECT SUM(s_defeat) FROM user WHERE clan_id = clan.id)',
                        'fight2'
                    );
                } elseif ($show == 'fight0') {
                    $result = $result->selectExpr(
                        '(SELECT SUM(s_draw) FROM user WHERE clan_id = clan.id)',
                        'fight0'
                    );
                } elseif ($show == 'members') {
                    $result = $result->selectExpr(
                        '(SELECT COUNT(1) FROM user WHERE clan_id = clan.id)',
                        'members'
                    );
                } elseif ($show == 'ppm') {
                    //$result = $result->select('s_gold_lost', 'goldlost');
                } elseif ($show == 'seals') {
                    //$result = $result->select('s_damage_caused', 'hits1');
                } elseif ($show == 'gatesopen') {
                    //$result = $result->select('s_hp_lost', 'hits2');
                } elseif ($show == 'lastgateopen') {
                    //$result = $result->select('s_hp_lost', 'hits2');
                }
            }

            $resultCount = $result->count();
        }
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
        var_dump($id);
    }
}
