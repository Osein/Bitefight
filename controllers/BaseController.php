<?php
use Phalcon\Mvc\View;

/**
 * Created by PhpStorm.
 * User: osein
 * Date: 08/01/17
 * Time: 17:22
 */

class BaseController extends \Phalcon\Mvc\Controller
{

    protected $user;

    public function initialize() {
        global $config;

        if($this->session->get('user_id', false)) {
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
    }

    /**
     * Called before the execution of handler
     *
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        if ($this->request->isPost() && !$this->security->checkToken() && $dispatcher->getControllerName() != 'Error') {
            $dispatcher->forward(array(
                'controller' => 'Error',
                'action'     => 'show404',
            ));

            return false;
        }

        return true;
    }

    public function afterExecuteRoute()
    {
        // Idiorm will look for dirty fields.
        // If there is none, it will not send query.
        // So I think this is a good practice for me.
        if($this->user) {
            $this->user->save();
        }
    }

    /**
     * Here to replace the ugly code
     * @return mixed
     */
    public function notFound() {
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
    public function sendEmail($to, $subject, $view, $data = array()) {
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

    public function getHighscore() {
        $this->view->menu_active = 'highscore';
        $result = ORM::for_table('user')
            ->select_many('name', 'id', 'race');

        $race = $this->request->get('race', \Phalcon\Filter::FILTER_INT, 0);

        if($race > 0 && $race < 3) {
            $result = $result->where('race', $race);
        }

        $this->view->page = $this->request->get('page', \Phalcon\Filter::FILTER_INT, 1);
        $this->view->show = array_slice($this->request->get('show', null, array('level', 'raid', 'fightvalue')), 0, 5);
        $this->view->order = $this->request->get('order', \Phalcon\Filter::FILTER_STRING, 'raid');

        if(!in_array($this->view->order, $this->view->show)) {
            $this->view->order = $this->view->show[0];
        }

        if($this->view->order == 'level') {
            $result = $result->orderByDesc('exp');
        } elseif($this->view->order == 'raid') {
            $result = $result->orderByDesc('s_booty');
        } elseif($this->view->order == 'fightvalue') {
            $result = $result->orderByDesc('battle_value');
        } elseif($this->view->order == 'fights') {
            $result = $result->orderByDesc('s_fight');
        } elseif($this->view->order == 'fight1') {
            $result = $result->orderByDesc('s_victory');
        } elseif($this->view->order == 'fight2') {
            $result = $result->orderByDesc('s_defeat');
        } elseif($this->view->order == 'fight0') {
            $result = $result->orderByDesc('s_draw');
        } elseif($this->view->order == 'goldwin') {
            $result = $result->orderByDesc('s_gold_captured');
        } elseif($this->view->order == 'goldlost') {
            $result = $result->orderByDesc('s_gold_lost');
        } elseif($this->view->order == 'hits1') {
            $result = $result->orderByDesc('s_damage_caused');
        } elseif($this->view->order == 'hits2') {
            $result = $result->orderByDesc('s_hp_lost');
        }

        foreach($this->view->show as $show) {
            if($show == 'level') {
                $result = $result->selectExpr('FLOOR(SQRT(exp / 5)) + 1', 'level');
            } elseif($show == 'raid') {
                $result = $result->select('s_booty', 'raid');
            } elseif($show == 'fightvalue') {
                $result = $result->select('battle_value', 'fightvalue');
            } elseif($show == 'fights') {
                $result = $result->select('s_fight', 'fights');
            } elseif($show == 'fight1') {
                $result = $result->select('s_victory', 'fight1');
            } elseif($show == 'fight2') {
                $result = $result->select('s_defeat', 'fight2');
            } elseif($show == 'fight0') {
                $result = $result->select('s_draw', 'fight0');
            } elseif($show == 'goldwin') {
                $result = $result->select('s_gold_captured', 'goldwin');
            } elseif($show == 'goldlost') {
                $result = $result->select('s_gold_lost', 'goldlost');
            } elseif($show == 'hits1') {
                $result = $result->select('s_damage_caused', 'hits1');
            } elseif($show == 'hits2') {
                $result = $result->select('s_hp_lost', 'hits2');
            }
        }

        $resultCount = $result->count();

        $this->view->results = $result->limit(50)->offset(($this->view->page - 1) * 50)->find_many();
        $this->view->maxPage = ceil($resultCount / 50);
        $this->view->startRank = ($this->view->page - 1) * 50 + 1;

        $this->view->vampireCount = ORM::for_table('user')->where('race', 1)->count();
        $this->view->werewolfCount = ORM::for_table('user')->where('race', 2)->count();
        $this->view->vampireBlood = ORM::for_table('user')->where('race', 1)->selectExpr('SUM(s_booty)', 'booty')->find_one()->booty;
        $this->view->werewolfBlood = ORM::for_table('user')->where('race', 2)->selectExpr('SUM(s_booty)', 'booty')->find_one()->booty;
        $this->view->vampireBattle = ORM::for_table('user')->where('race', 1)->selectExpr('SUM(s_fight)', 'booty')->find_one()->booty;
        $this->view->werewolfBattle = ORM::for_table('user')->where('race', 2)->selectExpr('SUM(s_fight)', 'booty')->find_one()->booty;
        $this->view->vampireGold = ORM::for_table('user')->where('race', 1)->selectExpr('SUM(gold)', 'booty')->find_one()->booty;
        $this->view->werewolfGold = ORM::for_table('user')->where('race', 2)->selectExpr('SUM(gold)', 'booty')->find_one()->booty;

        $this->view->pick('user/highscore');
    }

    public function getNews() {
        $this->view->menu_active = 'news';
        $news = ORM::for_table('news')
            ->find_many();

        $this->view->news = $news;
        $this->view->pick('home/news');
    }

    public function getPreview($id) {
        $user = ORM::for_table('user')
            ->select('user.*')
            ->select('user_description.descriptionHtml')
            ->left_outer_join('user_description', ['user.id', '=', 'user_description.user_id'])
            ->find_one($id);

        if(!$user) {
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
        $this->view->pick('user/preview');
    }

}