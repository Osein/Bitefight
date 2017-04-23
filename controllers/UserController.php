<?php
/**
 * Created by PhpStorm.
 * User: osein
 * Date: 14/01/17
 * Time: 03:26
 */

namespace Bitefight\Controllers;

use Bitefight\Config;
use Bitefight\Library\Translate;
use ORM;
use Phalcon\Filter;

class UserController extends GameController
{
    public function getProfile()
    {
        $this->view->menu_active = 'profile';

        $hsRow = ORM::for_table('')
            ->raw_query(
                'SELECT (SELECT COUNT(1) FROM user WHERE s_booty > ?) AS greater,
                (SELECT COUNT(1) FROM user WHERE id < ? AND s_booty = ?) AS equal',
                array($this->user->s_booty, $this->user->id, $this->user->s_booty)
            )->find_one();

        $this->view->highscorePosition = $hsRow->greater + $hsRow->equal + 1;

        $user_active_items = array();
        $user_items = array();

        $potions = array();
        $weapons = array();
        $helmets = array();
        $armour = array();
        $jewellery = array();
        $gloves = array();
        $boots = array();
        $shields = array();

        //Attributes
        $stat_str_tooltip = array(
            'detail' => array(array('Basic value', $this->user->str))
        );

        $stat_def_tooltip = array(
            'detail' => array(array('Basic value', $this->user->def))
        );

        $stat_dex_tooltip = array(
            'detail' => array(array('Basic value', $this->user->dex))
        );

        $stat_end_tooltip = array(
            'detail' => array(array('Basic value', $this->user->end))
        );

        $stat_cha_tooltip = array(
            'detail' => array(array('Basic value', $this->user->cha))
        );

        $stat_hp_tooltip = array(
            'detail' => array(
                array('Basic value', Config::BASIC_HP),
                array('Defence', Config::DEF_HP_RATIO * $this->user->def)
            )
        );

        // Fight Modifications
        $fm_attack_tooltip = array(
            'detail' => array(array('Basic value', Config::BASIC_ATTACK))
        );

        $fm_bsc_dmg_tooltip = array(
            'detail' => array(array('Basic value', Config::BASIC_DAMAGE))
        );

        $fm_bsc_hc_tooltip = array(
            'detail' => array(array('Basic value', Config::HIT_CHANCE))
        );

        $fm_bsc_tlnt_tooltip = array(
            'detail' => array(array('Basic value', Config::BASIC_TALENT))
        );

        $fm_bns_dmg_tooltip = array(
            'detail' => array(array('Basic value', Config::BONUS_DAMAGE))
        );

        $fm_bns_hc_tooltip = array(
            'detail' => array(array('Basic value', Config::BONUS_HIT_CHANCE))
        );

        $fm_bns_tlnt_tooltip = array(
            'detail' => array(array('Basic value', Config::BONUS_TALENT))
        );

        $fm_regen_tooltip = array(
            'detail' => array(
                array('Basic value', Config::BASIC_REGEN),
                array('Endurance', $this->user->end * Config::END_REGEN_RATIO)
            )
        );

        $fm_attack_total = Config::BASIC_ATTACK;
        $fm_bsc_dmg_total = Config::BASIC_DAMAGE;
        $fm_bsc_hc_total = Config::HIT_CHANCE;
        $fm_bsc_tlnt_total = Config::BASIC_TALENT;
        $fm_bns_dmg_total = Config::BONUS_DAMAGE;
        $fm_bns_hc_total = Config::BONUS_HIT_CHANCE;
        $fm_bns_tlnt_total = Config::BONUS_TALENT;
        $fm_regen_total = Config::BASIC_REGEN + $this->user->end * Config::END_REGEN_RATIO;

        $stat_str_total = $this->user->str;
        $stat_def_total = $this->user->def;
        $stat_dex_total = $this->user->dex;
        $stat_end_total = $this->user->end;
        $stat_cha_total = $this->user->cha;
        $stat_hp_total = Config::BASIC_HP + Config::DEF_HP_RATIO * $stat_def_total;

        $talents = ORM::for_table('user_talent')
            ->left_outer_join('talent', ['user_talent.talent_id', '=', 'talent.id'])
            ->where('user_talent.user_id', $this->user->id)
            ->where('talent.active', 0)
            ->find_many();

        foreach ($talents as $t) {

            if($t->attack > 0)
            {
                $fm_attack_tooltip['detail'][] = array(Translate::_tn($t->id), $t->str);
                $fm_attack_total += $t->attack;
            }

            if($t->str > 0)
            {
                $stat_str_tooltip['detail'][] = array(Translate::_tn($t->id), $t->str);
                $stat_str_total += $t->str;
            }

            if($t->def > 0)
            {
                $stat_def_tooltip['detail'][] = array(Translate::_tn($t->id), $t->def);
                $stat_def_total += $t->def;
            }

            if($t->dex > 0)
            {
                $stat_dex_tooltip['detail'][] = array(Translate::_tn($t->id), $t->dex);
                $stat_dex_total += $t->dex;
            }

            if($t->end > 0)
            {
                $stat_end_tooltip['detail'][] = array(Translate::_tn($t->id), $t->end);
                $stat_end_total += $t->end;
            }

            if($t->cha > 0)
            {
                $stat_cha_tooltip['detail'][] = array(Translate::_tn($t->id), $t->cha);
                $stat_cha_total += $t->cha;
            }

            if($t->hpbonus > 0)
            {
                $stat_hp_tooltip['detail'][] = array(Translate::_tn($t->id), $t->hpbonus);
                $stat_hp_total += $t->hpbonus;
            }

            if($t->regen > 0)
            {
                $fm_regen_tooltip['detail'][] = array(Translate::_tn($t->id), $t->regen);
                $fm_regen_total += $t->regen;
            }

            if($t->sbscdmg > 0)
            {
                $fm_bsc_dmg_tooltip['detail'][] = array(Translate::_tn($t->id), $t->sbscdmg);
                $fm_bsc_dmg_total += $t->sbscdmg;
            }

            if($t->sbnsdmg > 0)
            {
                $fm_bns_dmg_tooltip['detail'][] = array(Translate::_tn($t->id), $t->sbnsdmg);
                $fm_bns_dmg_total += $t->sbnsdmg;
            }

            if($t->sbschc > 0)
            {
                $fm_bsc_hc_tooltip['detail'][] = array(Translate::_tn($t->id), $t->sbschc);
                $fm_bsc_hc_total += $t->sbschc;
            }

            if($t->sbnshc > 0)
            {
                $fm_bns_hc_tooltip['detail'][] = array(Translate::_tn($t->id), $t->sbnshc);
                $fm_bns_hc_total += $t->sbnshc;
            }

            if($t->sbsctlnt > 0)
            {
                $fm_bsc_tlnt_tooltip['detail'][] = array(Translate::_tn($t->id), $t->sbsctlnt);
                $fm_bsc_tlnt_total += $t->sbsctlnt;
            }

            if($t->sbnstlnt > 0)
            {
                $fm_bns_tlnt_tooltip['detail'][] = array(Translate::_tn($t->id), $t->sbnstlnt);
                $fm_bns_tlnt_total += $t->sbnstlnt;
            }

        }

        $fm_attack_tooltip['total'] = array('Attack', $fm_attack_total);
        $stat_str_tooltip['total'] = array('Strength', $stat_str_total);
        $stat_def_tooltip['total'] = array('Defence', $stat_def_total);
        $stat_dex_tooltip['total'] = array('Dexterity', $stat_dex_total);
        $stat_end_tooltip['total'] = array('Endurance', $stat_end_total);
        $stat_cha_tooltip['total'] = array('Charisma', $stat_cha_total);
        $stat_hp_tooltip['total'] = array('Total Health', $stat_hp_total);
        $fm_regen_tooltip['total'] = array('Regeneration', $fm_regen_total);
        $fm_bsc_dmg_tooltip['total'] = array('Basic Damage', $fm_bsc_dmg_total);
        $fm_bns_dmg_tooltip['total'] = array('Bonus Damage', $fm_bns_dmg_total);
        $fm_bsc_hc_tooltip['total'] = array('Basic Hit Chance', $fm_bsc_hc_total);
        $fm_bns_hc_tooltip['total'] = array('Bonus Hit Chance', $fm_bns_hc_total);
        $fm_bsc_tlnt_tooltip['total'] = array('Basic Talent', $fm_bsc_tlnt_total);
        $fm_bns_tlnt_tooltip['total'] = array('Bonus Talent', $fm_bns_tlnt_total);

        $max_stat = max($stat_str_total, $stat_def_total, $stat_dex_total, $stat_end_total, $stat_cha_total);

        $str_total_long = $stat_str_total / $max_stat * 300;
        $def_total_long = $stat_def_total / $max_stat * 300;
        $dex_total_long = $stat_dex_total / $max_stat * 300;
        $end_total_long = $stat_end_total / $max_stat * 300;
        $cha_total_long = $stat_cha_total / $max_stat * 300;

        $user_tlnt_lvl_sqrt = floor(sqrt(getLevel($this->user->exp)));

        $userLevel = getLevel($this->user->exp);

        $previousLevelExp = getPreviousExpNeeded($userLevel);
        $nextLevelExp = getExpNeeded($userLevel);
        $levelExpDiff = $nextLevelExp - $previousLevelExp;

        $this->view->setVars(array(
            'str_total_long' => $str_total_long,
            'def_total_long' => $def_total_long,
            'dex_total_long' => $dex_total_long,
            'end_total_long' => $end_total_long,
            'cha_total_long' => $cha_total_long,

            'str_cost' => getSkillCost($this->user->str),
            'def_cost' => getSkillCost($this->user->def),
            'dex_cost' => getSkillCost($this->user->dex),
            'end_cost' => getSkillCost($this->user->end),
            'cha_cost' => getSkillCost($this->user->cha),

            'str_red_long' => $this->user->str / $max_stat * 300,
            'def_red_long' => $this->user->def / $max_stat * 300,
            'dex_red_long' => $this->user->dex / $max_stat * 300,
            'end_red_long' => $this->user->end / $max_stat * 300,
            'cha_red_long' => $this->user->cha / $max_stat * 300,

            'stat_str_tooltip' => $stat_str_tooltip,
            'stat_def_tooltip' => $stat_def_tooltip,
            'stat_dex_tooltip' => $stat_dex_tooltip,
            'stat_end_tooltip' => $stat_end_tooltip,
            'stat_cha_tooltip' => $stat_cha_tooltip,
            'stat_hp_tooltip' => $stat_hp_tooltip,

            'stat_str_total' => $stat_str_total,
            'stat_def_total' => $stat_def_total,
            'stat_dex_total' => $stat_dex_total,
            'stat_end_total' => $stat_end_total,
            'stat_cha_total' => $stat_cha_total,
            'stat_hp_total' => $stat_hp_total,

            'fm_attack_tooltip' => $fm_attack_tooltip,
            'fm_bsc_dmg_tooltip' => $fm_bsc_dmg_tooltip,
            'fm_bsc_hc_tooltip' => $fm_bsc_hc_tooltip,
            'fm_bsc_tlnt_tooltip' => $fm_bsc_tlnt_tooltip,
            'fm_bns_dmg_tooltip' => $fm_bns_dmg_tooltip,
            'fm_bns_hc_tooltip' => $fm_bns_hc_tooltip,
            'fm_bns_tlnt_tooltip' => $fm_bns_tlnt_tooltip,
            'fm_regen_tooltip' => $fm_regen_tooltip,

            'fm_attack_total' => $fm_attack_total,
            'fm_bsc_dmg_total' => $fm_bsc_dmg_total,
            'fm_bsc_hc_total' => $fm_bsc_hc_total,
            'fm_bsc_tlnt_total' => $fm_bsc_tlnt_total,
            'fm_bns_dmg_total' => $fm_bns_dmg_total,
            'fm_bns_hc_total' => $fm_bns_hc_total,
            'fm_bns_tlnt_total' => $fm_bns_tlnt_total,
            'fm_regen_total' => $fm_regen_total,

            'user_item_count' => count($user_items),
            'user_item_max_count' => 13 + $this->user->h_domicile * 2,

            'user_active_items' => $user_active_items,

            'potions' => $potions,
            'weapons' => $weapons,
            'helmets' => $helmets,
            'armour' => $armour,
            'jewellery' => $jewellery,
            'gloves' => $gloves,
            'boots' => $boots,
            'shields' => $shields,

            'hp_red_long' => $this->user->hp_now / $this->user->hp_max * 400,
            'exp_red_long' => ($this->user->exp - $previousLevelExp) / $levelExpDiff * 400,
            'required_exp' => $nextLevelExp,

            'user_tlnt_max' => $user_tlnt_lvl_sqrt * 2 - 1,
            'next_tlnt_level' => pow($user_tlnt_lvl_sqrt + 1, 2),
            'user_tlnt_used_count' => ORM::for_table('user_talent')
                ->where('user_id', $this->user->id)->count(),
        ));

        $this->view->pick('user/profile');
    }

    public function postTrainingUp()
    {
        $stat_type = $this->request->getPost('stat_type');

        if (!in_array($stat_type, array('str', 'def', 'dex', 'end', 'cha'))) {
            return $this->notFound();
        }

        $userStat = $this->user->{$stat_type};
        $cost = getSkillCost($userStat);

        if ($this->user->gold < $cost) {
            return $this->notFound();
        }

        $this->user->gold -= $cost;
        $this->user->{$stat_type}++;

        return $this->response->redirect(getUrl('user/profile#tabs-2'));
    }

    public function getProfileLogo()
    {
        $this->view->pick('user/logo');
    }

    public function postProfileLogo()
    {
        $gender = $this->request->getPost('gender');
        $type = $this->request->getPost('type');

        $this->user->gender = $gender;
        $this->user->image_type = $type;

        return $this->response->redirect(getUrl('user/profile'));
    }

    public function getTalents()
    {
        $this->view->menu_active = 'profile';
        $filter = $this->request->get('filter', Filter::FILTER_INT, 2);

        /**
         * @var ORM $talentsResult
         */
        $talentsResult = ORM::for_table('talent')
            ->select_many('talent.*', 'user_talent.user_id')
            ->left_outer_join('user_talent', array('user_talent.talent_id', '=', 'talent.id'))
            ->orderByAsc('talent.id');

        if($filter == 1) {
            $talentsResult = $talentsResult->where_not_null('user_id');
        } elseif($filter == 2) {
            $talentsResult = $talentsResult->where_null('user_id')
                ->where_lte('level', getLevel($this->user->exp));
        }

        $talentsResult = $talentsResult->find_many();
        $talents = array();

        foreach ($talentsResult as $talent) {
            if($talent->pair) {
                $talents[$talent->pair][] = $talent;
                continue;
            }

            $talents[$talent->id] = array($talent);
        }

        $userTalentCount = ORM::for_table('user_talent')->where('user_id', $this->user->id)->count();

        $user_tlnt_lvl_sqrt = floor(sqrt(getLevel($this->user->exp)));
        $this->view->max_points = $user_tlnt_lvl_sqrt * 2 - 1;
        $this->view->next_talent_level = pow($user_tlnt_lvl_sqrt + 1, 2);
        $this->view->available = $this->user->talent_points - $userTalentCount;
        $this->view->new_talent_price = pow($this->user->talent_points, 2.5) * 100;
        $this->view->talent_reset_price = floor(pow(14, $this->user->talent_resets) * 33);
        $this->view->used_points = $userTalentCount;
        $this->view->talents = $talents;
        $this->view->filter = $filter;
        $this->view->pick('user/talents');
    }

    public function postTalentTopForm()
    {
        $user_tlnt_lvl_sqrt = floor(sqrt(getLevel($this->user->exp)));
        $max_points = $user_tlnt_lvl_sqrt * 2 - 1;

        if($this->request->get('buypoint')) {
            $new_talent_price = pow($this->user->talent_points, 2.5) * 100;

            if($this->user->talent_points < $max_points && $this->user->gold >= $new_talent_price) {
                $this->user->gold -= $new_talent_price;
                $this->user->talent_points++;
            }
        } elseif($this->request->get('resetpoinths')) {
            $user_talent_count = ORM::for_table('user_talent')->where('user_id', $this->user->id)->count();

            if($user_talent_count > 0 && $this->user->hellstone >= 19) {
                $this->user->hellstone -= 19;
                $this->user->talent_resets = 1;
                ORM::raw_execute('DELETE FROM user_talent WHERE user_id = ?' [$this->user->id]);
            }
        } elseif($this->request->get('resetpointsg')) {
            $user_talent_count = ORM::for_table('user_talent')->where('user_id', $this->user->id)->count();
            $talent_reset_price = floor(pow(14, $this->user->talent_resets) * 33);

            if($user_talent_count > 0 && $this->user->gold >= $talent_reset_price) {
                $this->user->gold -= $talent_reset_price;
                $this->user->talent_resets++;
                ORM::raw_execute('DELETE FROM user_talent WHERE user_id = ?' [$this->user->id]);
            }
        }

        return $this->response->redirect(getUrl('user/talents?filter='.$this->request->get('filter', Filter::FILTER_INT, 2)));
    }

    public function postTalentUse()
    {
        $talentId = $this->request->get('talent_id', Filter::FILTER_INT, 0);

        if($talentId) {
            $userTalentCount = ORM::for_table('user_talent')->where('user_id', $this->user->id)->count();

            if($userTalentCount < $this->user->talent_points) {
                $user_talent = ORM::for_table('user_talent')->create();
                $user_talent->user_id = $this->user->id;
                $user_talent->talent_id = $talentId;
                $user_talent->save();
            }
        }

        return $this->response->redirect(getUrl('user/talents?filter='.$this->request->get('filter', Filter::FILTER_INT, 2)));
    }

    public function postTalentResetSingle()
    {
        $talentId = $this->request->get('talent_id', Filter::FILTER_INT, 0);

        if($talentId && $this->user->hellstone >= 2) {
            ORM::raw_execute('DELETE FROM user_talent WHERE user_id = ? AND talent_id = ?', [$this->user->id, $talentId]);
            $this->user->hellstone -= 2;
        }

        return $this->response->redirect(getUrl('user/talents?filter='.$this->request->get('filter', Filter::FILTER_INT, 2)));
    }

    public function getHideout()
    {
        $this->view->menu_active = 'hideout';

        $this->view->pick('user/hideout');
    }

    public function getHideoutUpgrade()
    {
        $token = $this->request->get('_token');
        $tokenKey = $this->request->get('_tkey');

        if (!$this->security->checkToken($tokenKey, $token)) {
            return $this->response->redirect(getUrl('hideout'));
        }

        $upgradeId = $this->request->get('id');

        switch ($upgradeId) {
            case 1:
                $upgradeCost = getHideoutCost('domi', $this->user->h_domicile);
                if ($this->user->gold >= $upgradeCost && $this->user->h_domicile < 14) {
                    $this->user->gold -= $upgradeCost;
                    $this->user->h_domicile++;
                }
                break;
            case 2:
                $upgradeCost = getHideoutCost('wall', $this->user->h_wall);
                if ($this->user->gold >= $upgradeCost && $this->user->h_wall < 6) {
                    $this->user->gold -= $upgradeCost;
                    $this->user->h_wall++;
                }
                break;
            case 3:
                $upgradeCost = getHideoutCost('path', $this->user->h_path);
                if ($this->user->gold >= $upgradeCost && $this->user->h_path < 6) {
                    $this->user->gold -= $upgradeCost;
                    $this->user->h_path++;
                    $this->user->ap_max++;
                }
                break;
            case 4:
                $upgradeCost = getHideoutCost('land', $this->user->h_land);
                if ($this->user->gold >= $upgradeCost && $this->user->h_land < 6) {
                    $this->user->gold -= $upgradeCost;
                    $this->user->h_land++;
                }
                break;
            case 5:
                if ($this->user->hellstone >= 20) {
                    $this->user->hellstone -= 20;
                    if ($this->user->h_treasure >= time()) {
                        $this->user->h_treasure += 2419200;
                    } else {
                        $this->user->h_treasure = time() + 2419200;
                    }
                }
                break;
            case 6:
                if ($this->user->hellstone >= 55) {
                    $this->user->hellstone -= 55;
                    if ($this->user->h_treasure >= time()) {
                        $this->user->h_treasure += 7257600;
                    } else {
                        $this->user->h_treasure = time() + 7257600;
                    }
                }
                break;
            case 7:
                if ($this->user->hellstone >= 20) {
                    $this->user->hellstone -= 20;
                    if ($this->user->h_gargoyle >= time()) {
                        $this->user->h_gargoyle += 2419200;
                    } else {
                        $this->user->h_gargoyle = time() + 2419200;
                    }
                }
                break;
            case 8:
                if ($this->user->hellstone >= 55) {
                    $this->user->hellstone -= 55;
                    if ($this->user->h_gargoyle >= time()) {
                        $this->user->h_gargoyle += 7257600;
                    } else {
                        $this->user->h_gargoyle = time() + 7257600;
                    }
                }
                break;
            case 9:
                if ($this->user->hellstone >= 20) {
                    $this->user->hellstone -= 20;
                    if ($this->user->h_book >= time()) {
                        $this->user->h_book += 2419200;
                    } else {
                        $this->user->h_book = time() + 2419200;
                    }
                }
                break;
            case 10:
                if ($this->user->hellstone >= 55) {
                    $this->user->hellstone -= 55;
                    if ($this->user->h_book >= time()) {
                        $this->user->h_book += 7257600;
                    } else {
                        $this->user->h_book = time() + 7257600;
                    }
                }
                break;
            case 11:
                if ($this->user->hellstone >= 69) {
                    $this->user->hellstone -= 69;
                    if ($this->user->h_royal >= time()) {
                        $this->user->h_royal += 3628800;
                    } else {
                        $this->user->h_royal = time() + 3628800;
                    }
                }
                break;
        }

        return $this->response->redirect(getUrl('hideout'));
    }

    public function getNotepad()
    {
        $this->view->menu_active = 'notepad';
        $note = ORM::for_table('user_note')->where('user_id', $this->user->id)->find_one();
        $this->view->user_note = $note?$note->note:'';
        $this->view->pick('user/notepad');
    }

    public function postNotepad()
    {
        $note = $this->request->getPost('note');
        $dbNote = ORM::for_table('user_note')->where('user_id', $this->user->id)->find_one();
        if (!$dbNote) {
            $dbNote = ORM::for_table('user_note')->create();
            $dbNote->user_id = $this->user->id;
        }
        $dbNote->note = $note;
        $dbNote->save();
        return $this->response->redirect(getUrl('notepad'));
    }

    public function getSettings()
    {
        $this->view->menu_active = 'settings';
        $userDescription = ORM::for_table('user_description')->where('user_id', $this->user->id)->find_one();
        if ($userDescription) {
            $this->view->userDescription = $userDescription->description;
        }
        $this->view->pick('user/settings');
    }

    public function postSettings()
    {
        $parsedBb = parseBBCodes($this->request->get('rpg'));

        $dbDesc = ORM::for_table('user_description')->where('user_id', $this->user->id)->find_one();
        if (!$dbDesc) {
            $dbDesc = ORM::for_table('user_description')->create();
            $dbDesc->user_id = $this->user->id;
        }
        $dbDesc->description = $this->request->get('rpg');
        $dbDesc->descriptionHtml = $parsedBb;
        $dbDesc->save();
        return $this->response->redirect(getUrl('user/settings'));
    }

    public function getLogout()
    {
        $this->session->remove('user_id');
        return $this->response->redirect(getUrl(''));
    }
}
