<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 16/01/17
 * Time: 15:49
 */

namespace Bitefight\Controllers;

use Bitefight\Config;
use Bitefight\Library\Translate;
use ORM;
use Phalcon\Http\Response;
use Phalcon\Mvc\View;
use StdClass;

class HuntController extends GameController
{
    /**
     * @return bool
     */
    public function initialize()
    {
        $this->view->menu_active = 'hunt';
        return parent::initialize();
    }

    /**
     * @return View
     */
    public function getHunt()
    {
        $activity = ORM::for_table('user_activity')
            ->where('user_id', $this->user->id)
            ->where('activity_type', ACTIVITY_TYPE_GRAVEYARD)
            ->find_one();

        $activity && $activity->end_time > time() ? $this->view->onGraveyardWork = true : $this->view->onGraveyardWork = false;

        $this->view->hunt1Chance = $this->getHuntChance(1);
        $this->view->hunt2Chance = $this->getHuntChance(2);
        $this->view->hunt3Chance = $this->getHuntChance(3);
        $this->view->hunt4Chance = $this->getHuntChance(4);
        $this->view->hunt5Chance = $this->getHuntChance(5);
        $this->view->hunt1Exp = $this->getHuntExp(1);
        $this->view->hunt2Exp = $this->getHuntExp(2);
        $this->view->hunt3Exp = $this->getHuntExp(3);
        $this->view->hunt4Exp = $this->getHuntExp(4);
        $this->view->hunt5Exp = $this->getHuntExp(5);
        $this->view->hunt1Reward = $this->getHuntReward(1);
        $this->view->hunt2Reward = $this->getHuntReward(2);
        $this->view->hunt3Reward = $this->getHuntReward(3);
        $this->view->hunt4Reward = $this->getHuntReward(4);
        $this->view->hunt5Reward = $this->getHuntReward(5);

        $huntSearchError = $this->getFlashData('hunt_race_search_empty');

        if(!empty($huntSearchError)) {
            $this->view->setVar('race_search_error', $huntSearchError);
        }

        $huntSearchErrorTop = $this->getFlashData('hunt_race_search_empty_top');

        if(!empty($huntSearchErrorTop)) {
            $this->view->setVar('race_search_error_top', $huntSearchErrorTop);
        }

        return $this->view->pick('hunt/index');
    }

    /**
     * @param int $id
     * @return Response|View
     */
    public function getHumanHunt($id)
    {
        $token = $this->request->get('_token');
        $tokenKey = $this->request->get('_tkey');

        if (!$this->security->checkToken($tokenKey, $token)) {
            return $this->response->redirect(getUrl('hunt/index'));
        }

        if ($id < 0 || $id > 5) {
            return $this->notFound();
        }

        $requiredAp = ceil($id / 2);

        if ($this->user->ap_now < $requiredAp) {
            return $this->response->redirect(getUrl('hunt/index'));
        }

        $activity = ORM::for_table('user_activity')
            ->where('user_id', $this->user->id)
            ->where('activity_type', ACTIVITY_TYPE_GRAVEYARD)
            ->find_one();

        if($activity && $activity->end_time > time()) {
            return $this->response->redirect(getUrl('hunt/index'));
        }

        $this->user->ap_now -= $requiredAp;
        $rewardExp = $this->getHuntExp($id);
        $rewardGold = $this->getHuntReward($id);
        $huntChance = $this->getHuntChance($id);
        $rand = rand(1, 100);
        $success = false;

        if ($rand <= $huntChance) {
            $userLastLevel = getLevel($this->user->exp);
            $this->user->exp += $rewardExp;
            $userNewLevel = getLevel($this->user->exp);

            if($userNewLevel > $userLastLevel) {
                $this->user->battle_value += 4;

                $levelUpMessage = ORM::for_table('message')->create();
                $levelUpMessage->sender_id = 0;
                $levelUpMessage->receiver_id = $this->user->id;
                $levelUpMessage->folder_id = 0;
                $levelUpMessage->subject = 'You have levelled up';
                $levelUpMessage->message = 'Congratulations! You have gained enough experience to reach the next character level. Your new level: '.$userNewLevel;
                $levelUpMessage->save();
            }

            $this->user->gold += $rewardGold;
            $this->user->s_booty += $rewardGold;

            // With %3 chance user can get fragments
            if ($rand < 4) {
                $this->user->fragment += 1;
                $this->view->rewardFragment = 1;
            }

            $success = true;
        }

        $this->view->success = $success;
        $this->view->huntId = $id;
        $this->view->rewardExp = $rewardExp;
        $this->view->rewardGold = $rewardGold;
        $this->view->menu_active = 'hunt';
        return $this->view->pick('hunt/humanresult');
    }

    /**
     * @param int $huntNo
     * @return int
     */
    public function getHuntReward($huntNo)
    {
        $user_level = getLevel($this->user->exp);
        $userTalentCha = ORM::for_table('user_talent')
            ->left_outer_join('talent', ['user_talent.talent_id', '=', 'talent.id'])
            ->selectExpr('SUM(talent.cha)', 'totalTalentCha')
            ->where('user_talent.user_id', $this->user->id)
            ->find_one();
        $userItemCha = ORM::for_table('user_item')
            ->left_outer_join('item', ['user_item.item_id', '=', 'item.id'])
            ->selectExpr('SUM(item.cha)', 'totalItemCha')
            ->where_raw('user_item.user_id = ? AND ((item.model = 2 AND user_item.expire > ?) OR (item.model != 2 AND user_item.equipped = 1))', [$this->user->id, time()])
            ->find_one();
        $serendipity = ORM::for_table('user_item')
            ->where('item_id', 156)
            ->where('user_id', $this->user->id)
            ->where_gte('expire', time())
            ->count();
        $userTotalCha = $userTalentCha->totalTalentCha + $userItemCha->totalItemCha + $this->user->cha;

        if ($huntNo == 1) {
            $reward = ($userTotalCha * 2) + ($user_level * 1) + 450;
        } elseif ($huntNo == 2) {
            $reward = ($userTotalCha * 3) + ($user_level * 2) + 540;
        } elseif ($huntNo == 3) {
            $reward = ($userTotalCha * 3) + ($user_level * 3) + 609;
        } elseif ($huntNo == 4) {
            $reward = ($userTotalCha * 4) + ($user_level * 4) + 714;
        } else {
            $reward = ($userTotalCha * 5) + ($user_level * 5) + 860;
        }

        if($serendipity) {
            $reward *= 2;
        }

        return $reward;
    }

    /**
     * @param int $huntNo
     * @return int
     */
    public function getHuntExp($huntNo)
    {
        $user_level = getLevel($this->user->exp);
        return ($huntNo+(ceil(pow($user_level, 0.1*$huntNo))));
    }

    /**
     * @param int $huntNo
     * @return float
     */
    public function getHuntChance($huntNo)
    {
        $user_level = getLevel($this->user->exp);

        if ($huntNo == 1) {
            if ($user_level < 75) {
                return floor(($user_level*0.2)+75);
            } elseif ($user_level > 74 && $user_level < 165) {
                return floor(($user_level*0.1)+82.5);
            } else {
                return 99;
            }
        } elseif ($huntNo == 2) {
            if ($user_level < 125) {
                return floor(($user_level*0.2)+47);
            } elseif ($user_level > 124 && $user_level < 289) {
                return floor(($user_level*0.083)+72);
            } else {
                return 96;
            }
        } elseif ($huntNo == 3) {
            if ($user_level < 225) {
                return floor(($user_level*0.15)+32);
            } elseif ($user_level > 224 && $user_level < 420) {
                return floor(($user_level*0.065)+65.75);
            } else {
                return 93;
            }
        } elseif ($huntNo == 4) {
            if ($user_level < 350) {
                return floor(($user_level*0.09)+31);
            } elseif ($user_level > 349 && $user_level < 600) {
                return floor(($user_level*0.046)+62.5);
            } else {
                return 90;
            }
        } else {
            if ($user_level < 550) {
                return floor(($user_level*0.06)+21);
            } elseif ($user_level > 499 && $user_level < 850) {
                return floor(($user_level*0.037)+54);
            } else {
                return 85;
            }
        }
    }

    public function postRaceSearch()
    {
        $enemyType = $this->request->get('enemy_type');

        if(!in_array($enemyType, [1, 2])) {
            return $this->response->redirect(getUrl('hunt/index'));
        }

        $enemy = ORM::for_table('user')
            ->left_outer_join('clan', ['clan.id', '=', 'user.clan_id'])
            ->left_outer_join('clan_rank', ['clan_rank.id', '=', 'user.clan_rank'])
            ->left_outer_join('user_description', ['user.id', '=', 'user_description.user_id'])
            ->select('user.*')->select_many('clan.logo_bg', 'clan.logo_sym')
            ->select('clan.name', 'clan_name')->select('clan.tag', 'clan_tag')
            ->select('clan_rank.rank_name')->select('clan_rank.war_minister')
            ->select('user_description.descriptionHtml')
            ->where('user.race', $this->user->race == 1 ? 2 : 1)
            ->where_lte('user.battle_value', $this->user->battle_value * 1.25)
            ->where_gte('user.battle_value', $this->user->battle_value * ($enemyType == 1 ? 0.8 : 1))
            ->orderByExpr('RAND()')
            ->find_one();

        if(empty($enemy)) {
            $this->setFlashData('hunt_race_search_empty_top', 'You were unable to find a victim');
            return $this->response->redirect(getUrl('hunt/index'));
        }

        $stat_max = max($enemy->str, $enemy->dex, $enemy->dex, $enemy->end, $enemy->cha);
        $userLevel = getLevel($enemy->exp);
        $previousLevelExp = getPreviousExpNeeded($userLevel);
        $nextLevelExp = getExpNeeded($userLevel);
        $levelExpDiff = $nextLevelExp - $previousLevelExp;

        $this->view->exp_red_long = ($enemy->exp - $previousLevelExp) / $levelExpDiff * 200;

        $this->view->str_red_long = $enemy->str / $stat_max * 200;
        $this->view->def_red_long = $enemy->def / $stat_max * 200;
        $this->view->end_red_long = $enemy->end / $stat_max * 200;
        $this->view->dex_red_long = $enemy->dex / $stat_max * 200;
        $this->view->cha_red_long = $enemy->cha / $stat_max * 200;

        $this->view->setVar('puser', $enemy);
        $this->view->setVar('search_again', true);
        $this->view->setVar('enemy_type', $enemyType);

        return $this->view->pick('hunt/preview');
    }

    public function postRaceSearchExact()
    {
        $name = $this->request->get('name');

        if(empty($name)) {
            $this->setFlashData('hunt_race_search_empty', 'You were unable to find a victim');
            return $this->response->redirect(getUrl('hunt/index'));
        }

        $enemy = ORM::for_table('user')->where('user.name', $name)
            ->left_outer_join('clan', ['clan.id', '=', 'user.clan_id'])
            ->left_outer_join('clan_rank', ['clan_rank.id', '=', 'user.clan_rank'])
            ->left_outer_join('user_description', ['user.id', '=', 'user_description.user_id'])
            ->select('user.*')->select_many('clan.logo_bg', 'clan.logo_sym')
            ->select('clan.name', 'clan_name')->select('clan.tag', 'clan_tag')
            ->select('clan_rank.rank_name')->select('clan_rank.war_minister')
            ->select('user_description.descriptionHtml')
            ->where('user.race', $this->user->race == 1 ? 2 : 1)
            ->find_one();

        if(empty($enemy)) {
            $this->setFlashData('hunt_race_search_empty', 'You were unable to find a victim');
            return $this->response->redirect(getUrl('hunt/index'));
        }

        $stat_max = max($enemy->str, $enemy->dex, $enemy->dex, $enemy->end, $enemy->cha);
        $userLevel = getLevel($enemy->exp);
        $previousLevelExp = getPreviousExpNeeded($userLevel);
        $nextLevelExp = getExpNeeded($userLevel);
        $levelExpDiff = $nextLevelExp - $previousLevelExp;

        $this->view->exp_red_long = ($enemy->exp - $previousLevelExp) / $levelExpDiff * 200;

        $this->view->str_red_long = $enemy->str / $stat_max * 200;
        $this->view->def_red_long = $enemy->def / $stat_max * 200;
        $this->view->end_red_long = $enemy->end / $stat_max * 200;
        $this->view->dex_red_long = $enemy->dex / $stat_max * 200;
        $this->view->cha_red_long = $enemy->cha / $stat_max * 200;

        $this->view->setVar('puser', $enemy);

        return $this->view->pick('hunt/preview');
    }

    public function postRaceAttack($id)
    {
        $attacker = ORM::for_table('user')->left_outer_join('clan', ['clan.id', '=', 'user.clan_id'])->select('user.*')->select('clan.tag')->where('user.id', $this->user->id)->find_one();
        $defender = ORM::for_table('user')->left_outer_join('clan', ['clan.id', '=', 'user.clan_id'])->select('user.*')->select('clan.tag')->where('user.id', $id)->find_one();

        if(!$defender || $this->user->ap_now < 1) {
            $this->notFound();
        }

        $attacker->ap_now--;

        $attacker_items = ORM::for_table('user_item')
            ->left_outer_join('item', ['item.id', '=', 'user_item.item_id'])
            ->where_raw('user_item.user_id = ? AND (user_item.equipped = 1 OR user_item.expire > ?)', [$attacker->id, time()])
            ->find_many();

        $defender_items = ORM::for_table('user_item')
            ->left_outer_join('item', ['item.id', '=', 'user_item.item_id'])
            ->where_raw('user_item.user_id = ? AND (user_item.equipped = 1 OR user_item.expire > ?)', [$defender->id, time()])
            ->find_many();

        $report = new StdClass();
        $report->attacker = new StdClass();
        $report->defender = new StdClass();
        $report->rounds = array();

        $report->attacker->id = $attacker->id;
        $report->defender->id = $defender->id;
        $report->attacker->level = getLevel($attacker->exp);
        $report->defender->level = getLevel($defender->exp);
        $report->attacker->battle_value = $attacker->battle_value;
        $report->defender->battle_value = $defender->battle_value;
        $report->defender->wall = $defender->h_wall;
        $report->defender->land = $defender->h_land;
        $report->attacker->total_damage = 0;
        $report->defender->total_damage = 0;

        $report->attacker->items = array();
        $report->defender->items = array();

        $report->attacker->str = $attacker->str;
        $report->defender->str = $defender->str;
        $report->attacker->def = $attacker->def;
        $report->defender->def = $defender->def;
        $report->attacker->dex = $attacker->dex;
        $report->defender->dex = $defender->dex;
        $report->attacker->end = $attacker->end;
        $report->defender->end = $defender->end;
        $report->attacker->cha = $attacker->cha;
        $report->defender->cha = $defender->cha;

        $report->attacker->str_extra = 0;
        $report->defender->str_extra = 0;
        $report->attacker->def_extra = 0;
        $report->defender->def_extra = 0;
        $report->attacker->dex_extra = 0;
        $report->defender->dex_extra = 0;
        $report->attacker->end_extra = 0;
        $report->defender->end_extra = 0;
        $report->attacker->cha_extra = 0;
        $report->defender->cha_extra = 0;

        $report->attacker->str_extra_tooltip = array();
        $report->defender->str_extra_tooltip = array();
        $report->attacker->def_extra_tooltip = array();
        $report->defender->def_extra_tooltip = array();
        $report->attacker->dex_extra_tooltip = array();
        $report->defender->dex_extra_tooltip = array();
        $report->attacker->end_extra_tooltip = array();
        $report->defender->end_extra_tooltip = array();
        $report->attacker->cha_extra_tooltip = array();
        $report->defender->cha_extra_tooltip = array();

        $report->attacker->base_damage_tooltip = array([], []);
        $report->attacker->base_hit_chance_tooltip = array([], []);
        $report->attacker->base_talent_tooltip = array([], []);
        $report->defender->base_damage_tooltip = array([], []);
        $report->defender->base_hit_chance_tooltip = array([], []);
        $report->defender->base_talent_tooltip = array([], []);

        $report->attacker->total_basic_damage = Config::BASIC_DAMAGE;
        $report->attacker->total_basic_hit_chance = Config::HIT_CHANCE;
        $report->attacker->total_basic_talent = Config::BASIC_TALENT;
        $report->defender->total_basic_damage = Config::BASIC_DAMAGE;
        $report->defender->total_basic_hit_chance = Config::HIT_CHANCE;
        $report->defender->total_basic_talent = Config::BASIC_TALENT;

        $report->attacker->total_bonus_damage = Config::BONUS_DAMAGE;
        $report->attacker->total_bonus_hit_chance = Config::BONUS_HIT_CHANCE;
        $report->attacker->total_bonus_talent = Config::BONUS_TALENT;
        $report->defender->total_bonus_damage = Config::BONUS_DAMAGE;
        $report->defender->total_bonus_hit_chance = Config::BONUS_HIT_CHANCE;
        $report->defender->total_bonus_talent = Config::BONUS_TALENT;

        foreach ($attacker_items as $item) {
            if($item->model == 1) $report->attacker->items['weapon'] = ['id' => $item->id, 'name' => $item->name, 'stern' => $item->stern];
            if($item->model == 3) $report->attacker->items['helmet'] = ['id' => $item->id, 'name' => $item->name, 'stern' => $item->stern];
            if($item->model == 4) $report->attacker->items['armour'] = ['id' => $item->id, 'name' => $item->name, 'stern' => $item->stern];
            if($item->model == 5) $report->attacker->items['jewellery'] = ['id' => $item->id, 'name' => $item->name, 'stern' => $item->stern];
            if($item->model == 6) $report->attacker->items['glove'] = ['id' => $item->id, 'name' => $item->name, 'stern' => $item->stern];
            if($item->model == 7) $report->attacker->items['boot'] = ['id' => $item->id, 'name' => $item->name, 'stern' => $item->stern];
            if($item->model == 8) $report->attacker->items['shield'] = ['id' => $item->id, 'name' => $item->name, 'stern' => $item->stern];

            if($item->str > 0) {
                $report->attacker->str_extra_tooltip[] = array('name' => $item->name, 'val' => $item->str);
                $report->attacker->str_extra += $item->str;
            }

            if($item->def > 0) {
                $report->attacker->def_extra_tooltip[] = array('name' => $item->name, 'val' => $item->def);
                $report->attacker->def_extra += $item->def;
            }

            if($item->dex > 0) {
                $report->attacker->dex_extra_tooltip[] = array('name' => $item->name, 'val' => $item->dex);
                $report->attacker->dex_extra += $item->dex;
            }

            if($item->end > 0) {
                $report->attacker->end_extra_tooltip[] = array('name' => $item->name, 'val' => $item->end);
                $report->attacker->end_extra += $item->end;
            }

            if($item->cha > 0) {
                $report->attacker->cha_extra_tooltip[] = array('name' => $item->name, 'val' => $item->cha);
                $report->attacker->cha_extra += $item->cha;
            }

            if($item->sbscdmg != 0) {
                $report->attacker->base_damage_tooltip[] = array('name' => $item->name, 'val' => $item->sbscdmg);
                $report->attacker->total_basic_damage += $item->sbscdmg;
            }

            if($item->sbsctlnt != 0) {
                $report->attacker->base_talent_tooltip[] = array('name' => $item->name, 'val' => $item->sbsctlnt);
                $report->attacker->total_basic_talent += $item->sbsctlnt;
            }

            if($item->sbschc != 0) {
                $report->attacker->base_hit_chance_tooltip[] = array('name' => $item->name, 'val' => $item->sbschc);
                $report->attacker->total_basic_hit_chance += $item->sbschc;
            }

            if($item->sbnsdmg != 0) {
                $report->attacker->total_bonus_damage += $item->sbnsdmg;
            }

            if($item->sbnstlnt != 0) {
                $report->attacker->total_bonus_talent += $item->sbnstlnt;
            }

            if($item->sbnshc != 0) {
                $report->attacker->total_bonus_hit_chance += $item->sbnshc;
            }

            if($item->ebscdmg != 0) {
                $report->defender->base_damage_tooltip[] = array('name' => $item->name, 'val' => $item->ebscdmg);
                $report->defender->total_basic_damage += $item->ebscdmg;
            }

            if($item->ebsctlnt != 0) {
                $report->defender->base_talent_tooltip[] = array('name' => $item->name, 'val' => $item->ebsctlnt);
                $report->defender->total_basic_talent += $item->ebsctlnt;
            }

            if($item->ebschc != 0) {
                $report->defender->base_hit_chance_tooltip[] = array('name' => $item->name, 'val' => $item->ebschc);
                $report->defender->total_basic_hit_chance += $item->ebschc;
            }

            if($item->ebnsdmg != 0) {
                $report->defender->total_bonus_damage += $item->ebnsdmg;
            }

            if($item->ebnstlnt != 0) {
                $report->defender->total_bonus_talent += $item->ebnstlnt;
            }

            if($item->ebnshc != 0) {
                $report->defender->total_bonus_hit_chance += $item->ebnshc;
            }
        }

        foreach ($defender_items as $item) {
            if($item->model == 1) $report->defender->items['weapon'] = ['id' => $item->id, 'name' => $item->name, 'stern' => $item->stern];
            if($item->model == 3) $report->defender->items['helmet'] = ['id' => $item->id, 'name' => $item->name, 'stern' => $item->stern];
            if($item->model == 4) $report->defender->items['armour'] = ['id' => $item->id, 'name' => $item->name, 'stern' => $item->stern];
            if($item->model == 5) $report->defender->items['jewellery'] = ['id' => $item->id, 'name' => $item->name, 'stern' => $item->stern];
            if($item->model == 6) $report->defender->items['glove'] = ['id' => $item->id, 'name' => $item->name, 'stern' => $item->stern];
            if($item->model == 7) $report->defender->items['boot'] = ['id' => $item->id, 'name' => $item->name, 'stern' => $item->stern];
            if($item->model == 8) $report->defender->items['shield'] = ['id' => $item->id, 'name' => $item->name, 'stern' => $item->stern];

            if($item->str != 0) {
                $report->defender->str_extra_tooltip[] = array('name' => $item->name, 'val' => $item->str);
                $report->defender->str_extra += $item->str;
            }

            if($item->def != 0) {
                $report->defender->def_extra_tooltip[] = array('name' => $item->name, 'val' => $item->def);
                $report->defender->def_extra += $item->def;
            }

            if($item->dex != 0) {
                $report->defender->dex_extra_tooltip[] = array('name' => $item->name, 'val' => $item->dex);
                $report->defender->dex_extra += $item->dex;
            }

            if($item->end != 0) {
                $report->defender->end_extra_tooltip[] = array('name' => $item->name, 'val' => $item->end);
                $report->defender->end_extra += $item->end;
            }

            if($item->cha != 0) {
                $report->defender->cha_extra_tooltip[] = array('name' => $item->name, 'val' => $item->cha);
                $report->defender->cha_extra += $item->cha;
            }

            if($item->sbscdmg != 0) {
                $report->defender->base_damage_tooltip[] = array('name' => $item->name, 'val' => $item->sbscdmg);
                $report->defender->total_basic_damage += $item->sbscdmg;
            }

            if($item->sbsctlnt != 0) {
                $report->defender->base_talent_tooltip[] = array('name' => $item->name, 'val' => $item->sbsctlnt);
                $report->defender->total_basic_talent += $item->sbsctlnt;
            }

            if($item->sbschc != 0) {
                $report->defender->base_hit_chance_tooltip[] = array('name' => $item->name, 'val' => $item->sbschc);
                $report->defender->total_basic_hit_chance += $item->sbschc;
            }

            if($item->sbnsdmg != 0) {
                $report->defender->total_bonus_damage += $item->sbnsdmg;
            }

            if($item->sbnstlnt != 0) {
                $report->defender->total_bonus_talent += $item->sbnstlnt;
            }

            if($item->sbnshc != 0) {
                $report->defender->total_bonus_hit_chance += $item->sbnshc;
            }

            if($item->ebscdmg != 0) {
                $report->attacker->base_damage_tooltip[] = array('name' => $item->name, 'val' => $item->ebscdmg);
                $report->attacker->total_basic_damage += $item->ebscdmg;
            }

            if($item->ebsctlnt != 0) {
                $report->attacker->base_talent_tooltip[] = array('name' => $item->name, 'val' => $item->ebsctlnt);
                $report->attacker->total_basic_talent += $item->ebsctlnt;
            }

            if($item->ebschc != 0) {
                $report->attacker->base_hit_chance_tooltip[] = array('name' => $item->name, 'val' => $item->ebschc);
                $report->attacker->total_basic_hit_chance += $item->ebschc;
            }

            if($item->ebnsdmg != 0) {
                $report->attacker->total_bonus_damage += $item->ebnsdmg;
            }

            if($item->ebnstlnt != 0) {
                $report->attacker->total_bonus_talent += $item->ebnstlnt;
            }

            if($item->ebnshc != 0) {
                $report->attacker->total_bonus_hit_chance += $item->ebnshc;
            }
        }

        $report->attacker->hp_start = $attacker->hp_now;
        $report->defender->hp_start = $defender->hp_now;

        $attacker_total_attack = 5;
        $attacker_total_str = $report->attacker->str + $report->attacker->str_extra;
        $attacker_total_def = $report->attacker->def + $report->attacker->def_extra;
        $attacker_total_dex = $report->attacker->dex + $report->attacker->dex_extra;
        $attacker_total_end = $report->attacker->end + $report->attacker->end_extra;
        $attacker_total_cha = $report->attacker->cha + $report->attacker->cha_extra;

        $defender_total_attack = 5;
        $defender_total_str = $report->defender->str + $report->defender->str_extra;
        $defender_total_def = $report->defender->def + $report->defender->def_extra;
        $defender_total_dex = $report->defender->dex + $report->defender->dex_extra;
        $defender_total_end = $report->defender->end + $report->defender->end_extra;
        $defender_total_cha = $report->defender->cha + $report->defender->cha_extra;

        $attacker_talents = ORM::for_table('user_talent')
            ->left_outer_join('talent', ['talent.id', '=', 'user_talent.talent_id'])
            ->where('user_talent.user_id', $attacker->id)
            ->find_many();

        $attacker_talents_active = [];

        for($i = 0; $i < count($attacker_talents); $i++) {
            if($attacker_talents[$i]->duration > 0) {
                $attacker_talents_active[] = $attacker_talents[$i];
                unset($attacker_talents[$i]);
            }
        }

        foreach ($attacker_talents as $talent) {
            if($talent->attack != 0) {
                $attacker_total_attack += $talent->attack;
            }

            if($talent->eattack != 0) {
                $defender_total_attack += $talent->eattack;
            }

            if($talent->str != 0) {
                $attacker_total_str += $talent->str;
            }

            if($talent->def != 0) {
                $attacker_total_def += $talent->def;
            }

            if($talent->dex != 0) {
                $attacker_total_dex += $talent->dex;
            }

            if($talent->end != 0) {
                $attacker_total_end += $talent->end;
            }

            if($talent->cha != 0) {
                $attacker_total_cha += $talent->cha;
            }

            if($talent->estr != 0) {
                $defender_total_str += $talent->estr;
            }

            if($talent->edef != 0) {
                $defender_total_def += $talent->edef;
            }

            if($talent->edex != 0) {
                $defender_total_dex += $talent->edex;
            }

            if($talent->eend != 0) {
                $defender_total_end += $talent->eend;
            }

            if($talent->echa != 0) {
                $defender_total_cha += $talent->echa;
            }

            if($talent->sbscdmg != 0) {
                $report->attacker->base_damage_tooltip[] = array('name' => Translate::_tn($talent->id), 'val' => $talent->sbscdmg);
                $report->attacker->total_basic_damage += $talent->sbscdmg;
            }

            if($talent->sbsctlnt != 0) {
                $report->attacker->base_talent_tooltip[] = array('name' => Translate::_tn($talent->id), 'val' => $talent->sbsctlnt);
                $report->attacker->total_basic_talent += $talent->sbsctlnt;
            }

            if($talent->sbschc != 0) {
                $report->attacker->base_hit_chance_tooltip[] = array('name' => Translate::_tn($talent->id), 'val' => $talent->sbschc);
                $report->attacker->total_basic_hit_chance += $talent->sbschc;
            }

            if($talent->sbnsdmg != 0) {
                $report->attacker->total_bonus_damage += $talent->sbnsdmg;
            }

            if($talent->sbnstlnt != 0) {
                $report->attacker->total_bonus_talent += $talent->sbnstlnt;
            }

            if($talent->sbnshc != 0) {
                $report->attacker->total_bonus_hit_chance += $talent->sbnshc;
            }

            if($talent->ebscdmg != 0) {
                $report->defender->base_damage_tooltip[] = array('name' => Translate::_tn($talent->id), 'val' => $talent->ebscdmg);
                $report->defender->total_basic_damage += $talent->ebscdmg;
            }

            if($talent->ebsctlnt != 0) {
                $report->defender->base_talent_tooltip[] = array('name' => Translate::_tn($talent->id), 'val' => $talent->ebsctlnt);
                $report->defender->total_basic_talent += $talent->ebsctlnt;
            }

            if($talent->ebschc != 0) {
                $report->defender->base_hit_chance_tooltip[] = array('name' => Translate::_tn($talent->id), 'val' => $talent->ebschc);
                $report->defender->total_basic_hit_chance += $talent->ebschc;
            }

            if($talent->ebnsdmg != 0) {
                $report->defender->total_bonus_damage += $talent->ebnsdmg;
            }

            if($talent->ebnstlnt != 0) {
                $report->defender->total_bonus_talent += $talent->ebnstlnt;
            }

            if($talent->ebnshc != 0) {
                $report->defender->total_bonus_hit_chance += $talent->ebnshc;
            }
        }

        $defender_talents = ORM::for_table('user_talent')
            ->left_outer_join('talent', ['talent.id', '=', 'user_talent.talent_id'])
            ->where('user_talent.user_id', $defender->id)
            ->find_many();

        $defender_talents_active = [];

        for($i = 0; $i < count($defender_talents); $i++) {
            if($defender_talents[$i]->duration > 0) {
                $defender_talents_active[] = $defender_talents[$i];
                unset($defender_talents[$i]);
            }
        }

        foreach ($defender_talents as $talent) {
            if($talent->attack != 0) {
                $defender_total_attack += $talent->attack;
            }

            if($talent->eattack != 0) {
                $attacker_total_attack += $talent->eattack;
            }

            if($talent->str != 0) {
                $defender_total_str += $talent->str;
            }

            if($talent->def != 0) {
                $defender_total_def += $talent->def;
            }

            if($talent->dex != 0) {
                $defender_total_dex += $talent->dex;
            }

            if($talent->end != 0) {
                $defender_total_end += $talent->end;
            }

            if($talent->cha != 0) {
                $defender_total_cha += $talent->cha;
            }

            if($talent->estr != 0) {
                $attacker_total_str += $talent->estr;
            }

            if($talent->edef != 0) {
                $attacker_total_def += $talent->edef;
            }

            if($talent->edex != 0) {
                $attacker_total_dex += $talent->edex;
            }

            if($talent->eend != 0) {
                $attacker_total_end += $talent->eend;
            }

            if($talent->echa != 0) {
                $attacker_total_cha += $talent->echa;
            }

            if($talent->sbscdmg != 0) {
                $report->defender->base_damage_tooltip[] = array('name' => Translate::_tn($talent->id), 'val' => $talent->sbscdmg);
                $report->defender->total_basic_damage += $talent->sbscdmg;
            }

            if($talent->sbsctlnt != 0) {
                $report->defender->base_talent_tooltip[] = array('name' => Translate::_tn($talent->id), 'val' => $talent->sbsctlnt);
                $report->defender->total_basic_talent += $talent->sbsctlnt;
            }

            if($talent->sbschc != 0) {
                $report->defender->base_hit_chance_tooltip[] = array('name' => Translate::_tn($talent->id), 'val' => $talent->sbschc);
                $report->defender->total_basic_hit_chance += $talent->sbschc;
            }

            if($talent->sbnsdmg != 0) {
                $report->defender->total_bonus_damage += $talent->sbnsdmg;
            }

            if($talent->sbnstlnt != 0) {
                $report->defender->total_bonus_talent += $talent->sbnstlnt;
            }

            if($talent->sbnshc != 0) {
                $report->defender->total_bonus_hit_chance += $talent->sbnshc;
            }

            if($talent->ebscdmg != 0) {
                $report->attacker->base_damage_tooltip[] = array('name' => Translate::_tn($talent->id), 'val' => $talent->ebscdmg);
                $report->attacker->total_basic_damage += $talent->ebscdmg;
            }

            if($talent->ebsctlnt != 0) {
                $report->attacker->base_talent_tooltip[] = array('name' => Translate::_tn($talent->id), 'val' => $talent->ebsctlnt);
                $report->attacker->total_basic_talent += $talent->ebsctlnt;
            }

            if($talent->ebschc != 0) {
                $report->attacker->base_hit_chance_tooltip[] = array('name' => Translate::_tn($talent->id), 'val' => $talent->ebschc);
                $report->attacker->total_basic_hit_chance += $talent->ebschc;
            }

            if($talent->ebnsdmg != 0) {
                $report->attacker->total_bonus_damage += $talent->ebnsdmg;
            }

            if($talent->ebnstlnt != 0) {
                $report->attacker->total_bonus_talent += $talent->ebnstlnt;
            }

            if($talent->ebnshc != 0) {
                $report->attacker->total_bonus_hit_chance += $talent->ebnshc;
            }
        }

        $attacker_active_talents = [];
        $defender_active_talents = [];

        $report->rounds = array();

        for($r = 1; $r <= 10; $r++) {
            $round_object = array();

            $attacker_round_extra_cha = 0;
            $defender_round_extra_cha = 0;
            $attacker_round_extra_talent = 0;
            $defender_round_extra_talent = 0;
            $attacker_round_extra_bonus_talent = 0;
            $defender_round_extra_bonus_talent = 0;

            $attacker_talent_tooltip = $report->attacker->base_talent_tooltip;
            $defender_talent_tooltip = $report->defender->base_talent_tooltip;

            foreach ($attacker_active_talents as $index => $talent) {
                if($talent->remaining == 0) {
                    unset($attacker_active_talents[$index]);
                    continue;
                }

                if($talent->cha != 0) {
                    $attacker_round_extra_cha += $talent->cha;
                }

                if($talent->echa != 0) {
                    $defender_round_extra_cha += $talent->echa;
                }

                if($talent->sbsctlnt != 0) {
                    $attacker_talent_tooltip[] = ['name' => Translate::_tn($talent->id), 'val' => $talent->sbsctlnt];
                    $attacker_round_extra_talent += $talent->sbsctlnt;
                }

                if($talent->ebsctlnt != 0) {
                    $defender_talent_tooltip[] = ['name' => Translate::_tn($talent->id), 'val' => $talent->sbsctlnt];
                    $defender_round_extra_talent += $talent->ebsctlnt;
                }

                if($talent->sbnstlnt != 0) {
                    $attacker_round_extra_bonus_talent += $talent->sbnstlnt;
                }

                if($talent->ebnstlnt != 0) {
                    $defender_round_extra_bonus_talent += $talent->ebnstlnt;
                }
            }

            foreach ($defender_active_talents as $index => $talent) {
                if($talent->remaining == 0) {
                    unset($defender_active_talents[$index]);
                    continue;
                }

                if($talent->cha != 0) {
                    $defender_round_extra_cha += $talent->cha;
                }

                if($talent->echa != 0) {
                    $attacker_round_extra_cha += $talent->echa;
                }

                if($talent->sbsctlnt != 0) {
                    $defender_talent_tooltip[] = ['name' => Translate::_tn($talent->id), 'val' => $talent->sbsctlnt];
                    $defender_round_extra_talent += $talent->sbsctlnt;
                }

                if($talent->ebsctlnt != 0) {
                    $attacker_talent_tooltip[] = ['name' => Translate::_tn($talent->id), 'val' => $talent->sbsctlnt];
                    $attacker_round_extra_talent += $talent->ebsctlnt;
                }

                if($talent->ebnstlnt != 0) {
                    $attacker_round_extra_bonus_talent += $talent->ebnstlnt;
                }

                if($talent->sbnstlnt != 0) {
                    $defender_round_extra_bonus_talent += $talent->sbnstlnt;
                }
            }

            $attacker_round_cha = $attacker_round_extra_cha + $attacker_total_cha;
            $defender_round_cha = $defender_round_extra_cha + $defender_total_cha;

            $total_cha = $attacker_round_cha + $defender_round_cha;
            $attacker_bonus_talent_multiplier = $attacker_round_cha / $total_cha;
            $defender_bonus_talent_multiplier = $defender_round_cha / $total_cha;
            $attacker_bonus_talent = round(($report->attacker->total_bonus_talent + $attacker_round_extra_bonus_talent) * $attacker_bonus_talent_multiplier, 2);
            $defender_bonus_talent = round(($report->defender->total_bonus_talent + $defender_round_extra_bonus_talent) * $defender_bonus_talent_multiplier, 2);

            $attacker_talent_chance = Config::BASIC_TALENT + $attacker_bonus_talent;
            $defender_talent_chance = Config::BASIC_TALENT + $defender_bonus_talent;

            $attacker_talent_tooltip[0] = ['name' => 'Basic value', 'val' => Config::BASIC_TALENT];
            $attacker_talent_tooltip[1] = ['name' => 'Bonus talent', 'val' => $attacker_bonus_talent];

            $defender_talent_tooltip[0] = ['name' => 'Basic value', 'val' => Config::BASIC_TALENT];
            $defender_talent_tooltip[1] = ['name' => 'Bonus talent', 'val' => $defender_bonus_talent];

            if(mt_rand(1, 100) <= $attacker_talent_chance && count($attacker_talents_active) > 0) {
                $active_talent_index = mt_rand(0, count($attacker_talents_active) - 1);
                $attacker_round_active_talent = $attacker_talents_active[$active_talent_index];
                if(isset($attacker_active_talents[$attacker_round_active_talent->id])) {
                    $attacker_active_talents[$attacker_round_active_talent->id]->remaining += $attacker_round_active_talent->duration;
                } else {
                    $attacker_round_active_talent->remaining = $attacker_round_active_talent->duration;
                    $attacker_active_talents[] = $attacker_round_active_talent;
                }

                $round_object['attacker_talent'] = [
                    'id' => $attacker_round_active_talent->id,
                    'info' => getTalentPropertyArray($attacker_round_active_talent),
                    'duration' => $attacker_round_active_talent->duration
                ];
            }

            if(mt_rand(1, 100) <= $defender_talent_chance && count($defender_talents_active) > 0) {
                $active_talent_index = mt_rand(0, count($defender_talents_active) - 1);
                $defender_round_active_talent = $defender_talents_active[$active_talent_index];
                if(isset($defender_active_talents[$defender_round_active_talent->id])) {
                    $defender_active_talents[$defender_round_active_talent->id]->remaining += $defender_round_active_talent->duration;
                } else {
                    $defender_round_active_talent->remaining = $defender_round_active_talent->duration;
                    $defender_active_talents[] = $defender_round_active_talent;
                }

                $round_object['defender_talent'] = [
                    'id' => $defender_round_active_talent->id,
                    'info' => getTalentPropertyArray($defender_round_active_talent),
                    'duration' => $defender_round_active_talent->duration
                ];
            }

            $attacker_hit_chance_tooltip = $report->attacker->base_hit_chance_tooltip;
            $defender_hit_chance_tooltip = $report->defender->base_hit_chance_tooltip;

            $attacker_damage_tooltip = $report->attacker->base_damage_tooltip;
            $defender_damage_tooltip = $report->defender->base_damage_tooltip;

            $attacker_round_extra_str = 0;
            $attacker_round_extra_def = 0;
            $attacker_round_extra_dex = 0;
            $attacker_round_extra_end = 0;
            $attacker_round_extra_damage = 0;
            $attacker_round_extra_bdamage = 0;
            $attacker_round_extra_hitchance = 0;
            $attacker_round_extra_bhitchance = 0;
            $attacker_round_attack = $attacker_total_attack;

            $defender_round_extra_str = 0;
            $defender_round_extra_def = 0;
            $defender_round_extra_dex = 0;
            $defender_round_extra_end = 0;
            $defender_round_extra_damage = 0;
            $defender_round_extra_bdamage = 0;
            $defender_round_extra_hitchance = 0;
            $defender_round_extra_bhitchance = 0;
            $defender_round_attack = $defender_total_attack;

            foreach ($attacker_active_talents as $talent) {
                if($talent->attack != 0) {
                    $attacker_round_attack += $talent->attack;
                }
                if($talent->eattack != 0) {
                    $defender_round_attack += $talent->eattack;
                }
                if($talent->str != 0) {
                    $attacker_round_extra_str += $talent->str;
                }
                if($talent->def != 0) {
                    $attacker_round_extra_def += $talent->def;
                }
                if($talent->dex != 0) {
                    $attacker_round_extra_dex += $talent->dex;
                }
                if($talent->end != 0) {
                    $attacker_round_extra_end += $talent->end;
                }
                if($talent->estr != 0) {
                    $defender_round_extra_str += $talent->estr;
                }
                if($talent->edef != 0) {
                    $defender_round_extra_def += $talent->edef;
                }
                if($talent->edex != 0) {
                    $defender_round_extra_dex += $talent->edex;
                }
                if($talent->eend != 0) {
                    $defender_round_extra_end += $talent->eend;
                }
                if($talent->sbscdmg != 0) {
                    $attacker_damage_tooltip[] = ['name' => Translate::_tn($talent->id), 'val' => $talent->sbscdmg];
                    $attacker_round_extra_damage += $talent->sbscdmg;
                }
                if($talent->sbnsdmg != 0) {
                    $attacker_round_extra_bdamage += $talent->sbnsdmg;
                }
                if($talent->sbschc != 0) {
                    $attacker_hit_chance_tooltip[] = ['name' => Translate::_tn($talent->id), 'val' => $talent->sbschc];
                    $attacker_round_extra_hitchance += $talent->sbschc;
                }
                if($talent->sbnshc != 0) {
                    $attacker_round_extra_bhitchance += $talent->sbnshc;
                }
                if($talent->ebscdmg != 0) {
                    $defender_damage_tooltip[] = ['name' => Translate::_tn($talent->id), 'val' => $talent->ebscdmg];
                    $defender_round_extra_damage += $talent->ebscdmg;
                }
                if($talent->ebnsdmg != 0) {
                    $defender_round_extra_bdamage += $talent->ebnsdmg;
                }
                if($talent->ebschc != 0) {
                    $defender_hit_chance_tooltip[] = ['name' => Translate::_tn($talent->id), 'val' => $talent->ebschc];
                    $defender_round_extra_hitchance += $talent->ebschc;
                }
                if($talent->ebnshc != 0) {
                    $defender_round_extra_bhitchance += $talent->ebnshc;
                }
                $talent->remaining--;
            }

            foreach ($defender_active_talents as $talent) {
                if($talent->attack != 0) {
                    $defender_round_attack += $talent->attack;
                }
                if($talent->eattack != 0) {
                    $attacker_round_attack += $talent->eattack;
                }
                if($talent->str != 0) {
                    $defender_round_extra_str += $talent->str;
                }
                if($talent->def != 0) {
                    $defender_round_extra_def += $talent->def;
                }
                if($talent->dex != 0) {
                    $defender_round_extra_dex += $talent->dex;
                }
                if($talent->end != 0) {
                    $defender_round_extra_end += $talent->end;
                }
                if($talent->estr != 0) {
                    $attacker_round_extra_str += $talent->estr;
                }
                if($talent->edef != 0) {
                    $attacker_round_extra_def += $talent->edef;
                }
                if($talent->edex != 0) {
                    $attacker_round_extra_dex += $talent->edex;
                }
                if($talent->eend != 0) {
                    $attacker_round_extra_end += $talent->eend;
                }
                if($talent->ebscdmg != 0) {
                    $attacker_damage_tooltip[] = ['name' => Translate::_tn($talent->id), 'val' => $talent->ebscdmg];
                    $attacker_round_extra_damage += $talent->ebscdmg;
                }
                if($talent->ebnsdmg != 0) {
                    $attacker_round_extra_bdamage += $talent->ebnsdmg;
                }
                if($talent->ebschc != 0) {
                    $attacker_hit_chance_tooltip[] = ['name' => Translate::_tn($talent->id), 'val' => $talent->ebschc];
                    $attacker_round_extra_hitchance += $talent->ebschc;
                }
                if($talent->ebnshc != 0) {
                    $attacker_round_extra_bhitchance += $talent->ebnshc;
                }
                if($talent->sbscdmg != 0) {
                    $defender_damage_tooltip[] = ['name' => Translate::_tn($talent->id), 'val' => $talent->sbscdmg];
                    $defender_round_extra_damage += $talent->sbscdmg;
                }
                if($talent->sbnsdmg != 0) {
                    $defender_round_extra_bdamage += $talent->sbnsdmg;
                }
                if($talent->sbschc != 0) {
                    $defender_hit_chance_tooltip[] = ['name' => Translate::_tn($talent->id), 'val' => $talent->sbschc];
                    $defender_round_extra_hitchance += $talent->sbschc;
                }
                if($talent->sbnshc != 0) {
                    $defender_round_extra_bhitchance += $talent->sbnshc;
                }
                $talent->remaining--;
            }

            $attacker_round_str = $attacker_total_str + $attacker_round_extra_str;
            $attacker_round_def = $attacker_total_def + $attacker_round_extra_def;
            $attacker_round_dex = $attacker_total_dex + $attacker_round_extra_dex;
            $attacker_round_end = $attacker_total_end + $attacker_round_extra_end;

            $defender_round_str = $defender_total_str + $defender_round_extra_str;
            $defender_round_def = $defender_total_def + $defender_round_extra_def;
            $defender_round_dex = $defender_total_dex + $defender_round_extra_dex;
            $defender_round_end = $defender_total_end + $defender_round_extra_end;

            $attacker_bonus_damage_multiplier = $attacker_round_str / ($attacker_round_str + $defender_round_end);
            $defender_bonus_damage_multiplier = $defender_round_str / ($defender_round_str + $attacker_round_end);

            $attacker_bonus_hc_multiplier = $attacker_round_dex / ($attacker_round_dex + $defender_round_def);
            $defender_bonus_hc_multiplier = $defender_round_dex / ($defender_round_dex + $attacker_round_def);

            $attacker_bonus_damage = round(($report->attacker->total_bonus_damage + $attacker_round_extra_bdamage) * $attacker_bonus_damage_multiplier, 2);
            $defender_bonus_damage = round(($report->defender->total_bonus_damage + $defender_round_extra_bdamage) * $defender_bonus_damage_multiplier, 2);

            $attacker_bonus_hc = round(($report->attacker->total_bonus_hit_chance + $attacker_round_extra_bhitchance) * $attacker_bonus_hc_multiplier, 2);
            $defender_bonus_hc = round(($report->defender->total_bonus_hit_chance + $defender_round_extra_bhitchance) * $defender_bonus_hc_multiplier, 2);

            $attacker_damage_tooltip[1] = ['name' => 'Bonus damage', 'val' => $attacker_bonus_damage];
            $attacker_damage_tooltip[0] = ['name' => 'Basic value', 'val' => Config::BASIC_DAMAGE];
            $attacker_hit_chance_tooltip[1] = ['name' => 'Bonus hit chance', 'val' => $attacker_bonus_hc];
            $attacker_hit_chance_tooltip[0] = ['name' => 'Basic value', 'val' => Config::HIT_CHANCE];

            $defender_damage_tooltip[1] = ['name' => 'Bonus damage', 'val' => $defender_bonus_damage];
            $defender_damage_tooltip[0] = ['name' => 'Basic value', 'val' => Config::BASIC_DAMAGE];
            $defender_hit_chance_tooltip[1] = ['name' => 'Bonus hit chance', 'val' => $defender_bonus_hc];
            $defender_hit_chance_tooltip[0] = ['name' => 'Basic value', 'val' => Config::HIT_CHANCE];

            $attacker_round_damage = $report->attacker->total_basic_damage + $attacker_bonus_damage;
            $attacker_round_hit_chance = $report->attacker->total_basic_hit_chance + $attacker_bonus_hc;

            $defender_round_damage = $report->defender->total_basic_damage + $defender_bonus_damage;
            $defender_round_hit_chance = $report->defender->total_basic_hit_chance + $defender_bonus_hc;

            $attacker_round_total_damage = 0;
            $defender_round_total_damage = 0;
            $attacker_round_hit_count = 0;
            $defender_round_hit_count = 0;

            for($i = 1; $i <= $attacker_round_attack; $i++) {
                if(mt_rand(1, 100) <= $attacker_round_hit_chance) {
                    $attacker_round_total_damage += round($attacker_round_damage, 2);
                    $attacker_round_hit_count++;
                }
            }

            for($i = 1; $i <= $defender_round_attack; $i++) {
                if(mt_rand(1, 100) <= $defender_round_hit_chance) {
                    $defender_round_total_damage += round($defender_round_damage, 2);
                    $defender_round_hit_count++;
                }
            }

            $report->attacker->total_damage += $attacker_round_total_damage;
            $report->defender->total_damage += $defender_round_total_damage;
            $defender->hp_now = max(0, $defender->hp_now - $attacker_round_total_damage);
            $attacker->hp_now = max(0, $attacker->hp_now - $defender_round_total_damage);

            $report->rounds[$r] = array_merge($round_object, array(
                'attacker_tooltip_damage' => $attacker_damage_tooltip,
                'defender_tooltip_damage' => $defender_damage_tooltip,
                'attacker_tooltip_hc' => $attacker_hit_chance_tooltip,
                'defender_tooltip_hc' => $defender_hit_chance_tooltip,
                'attacker_tooltip_talent' => $attacker_talent_tooltip,
                'defender_tooltip_talent' => $defender_talent_tooltip,
                'attacker_hc' => $attacker_round_hit_chance,
                'defender_hc' => $defender_round_hit_chance,
                'attacker_tc' => $attacker_talent_chance,
                'defender_tc' => $defender_talent_chance,
                'attacker_damage' => $attacker_round_damage,
                'defender_damage' => $defender_round_damage,
                'attacker_total_damage' => $attacker_round_total_damage,
                'defender_total_damage' => $defender_round_total_damage,
                'attacker_hit_count' => $attacker_round_hit_count,
                'defender_hit_count' => $defender_round_hit_count,
                'attacker_attack_count' => $attacker_round_attack,
                'defender_attack_count' => $defender_round_attack
            ));
        }

        $report->attacker->hp_end = $attacker->hp_now;
        $report->defender->hp_end = $defender->hp_now;

        $report->attacker->max_stat = max($report->attacker->str + $report->attacker->str_extra, $report->attacker->def + $report->attacker->def_extra, $report->attacker->dex + $report->attacker->dex_extra, $report->attacker->end + $report->attacker->end_extra, $report->attacker->cha + $report->attacker->cha_extra);
        $report->defender->max_stat = max($report->defender->str + $report->defender->str_extra, $report->defender->def + $report->defender->def_extra, $report->defender->dex + $report->defender->dex_extra, $report->defender->end + $report->defender->end_extra, $report->defender->cha + $report->defender->cha_extra);

        $report_time = time();
        $report->earned_gold = 0;
        $report->earned_bonus_gold = 0;

        if($report->attacker->total_damage > $report->defender->total_damage) {
            $defender_gold = $defender->gold;

            if($defender->h_treasure > $report_time) {
                $defender_gold -= getLevel($defender->exp) * 4800;
            }

            if($defender->h_royal > $report_time) {
                $defender_gold -= getLevel($defender->exp) * 19200;
            }

            if($defender_gold > 0) {
                $report->earned_gold = $defender_gold / 10;
            }
        } else {
            $attacker_gold = $attacker->gold;

            if($attacker->h_treasure > $report_time) {
                $attacker_gold -= getLevel($attacker->exp) * 4800;
            }

            if($attacker->h_royal > $report_time) {
                $attacker_gold -= getLevel($attacker->exp) * 19200;
            }

            if($attacker_gold > 0) {
                $report->earned_gold = $attacker_gold / 10;
            }
        }

        $dbReport = ORM::for_table('report')->create();
        $dbReport->attacker_id = $report->attacker->id;
        $dbReport->defender_id = $report->defender->id;
        $dbReport->report = json_encode($report);
        $dbReport->attack_time = time();
        $dbReport->save();
        $reportId = $dbReport->id();
        return $this->response->redirect(getUrl('report/fightreport/'.$reportId.'?to=robbery'));
    }

    public function getFightReport($id)
    {
        $report = ORM::for_table('report')->find_one($id);
        $to = $this->request->get('to');

        if(!$report) {
            return $this->notFound();
        }

        $fightObj = json_decode($report->report, true);

        $attacker = ORM::for_table('user')->left_outer_join('clan', ['clan.id', '=', 'user.clan_id'])->select('user.*')->select('clan.tag')->where('user.id', $fightObj['attacker']['id'])->find_one();
        $defender = ORM::for_table('user')->left_outer_join('clan', ['clan.id', '=', 'user.clan_id'])->select('user.*')->select('clan.tag')->where('user.id', $fightObj['defender']['id'])->find_one();

        $this->view->setVars([
            'attacker' => $attacker,
            'defender' => $defender,
            'report' => $fightObj,
            'attack_date' => $report->attack_time,
            'to' => $to,
            'reportId' => $report->id
        ]);

        return $this->view->pick('hunt/report');
    }
}
